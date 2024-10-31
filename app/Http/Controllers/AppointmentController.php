<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Data\AppointmentData;
use App\Interfaces\AppointmentSaverInterface;
use Illuminate\Support\Facades\Log;
use App\Services\CreateTimeArray;
use App\Services\GetReservedTimeArray;



class AppointmentController extends Controller
{

    protected $appointmentSaver;
    protected $getReservedTimeArray;
    protected $createTimeArray;
    
    public function __construct(AppointmentSaverInterface $appointmentSaver, GetReservedTimeArray $getReservedTimeArray,CreateTimeArray $createTimeArray)
    {
        $this->appointmentSaver = $appointmentSaver; 
        $this->getReservedTimeArray = $getReservedTimeArray;
        $this->createTimeArray = $createTimeArray; 
    }
     /**
     * 予約の作成
     *
     * @param StoreAppointmentRequest $request
     * @return \Illuminate\Http\JsonResponse 予約結果をJSONで返却
     */
    public function createAppointment(StoreAppointmentRequest $request)
    {
        $appointmentData = new AppointmentData(
            itemIds: $request->item_id,
            customerId: $request->customer_id,
            staffId: $request->staff_id,
            appointmentDate: $request->appointment_date,
            appointmentTime: $request->appointment_time
        );
        $appointmentResult = $this->appointmentSaver->saveAppointments($appointmentData);

        return response()->json($appointmentResult->appointments);
    }

    /**
     * 予約可能な時間を表示/スタッフが一人の時に使用
     *
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTimes(Request $request)
    {
    
    //time取得 getTime
    $appointDate = $request->query('date');
    $times = $this->createTimeArray->createTimeArray();
    $reservedTimes = $this->getReservedTimeArray->getReservedTime($appointDate);

    $availableTimes = array_diff($times, $reservedTimes);

        //jsonでデータを返す
        return response()->json([
            'availableTimes' => array_values($availableTimes),
        ]); 
    }

    /**
     * 予約可能なスタッフとアイテムを表示
     */
    public function getAvailableStaffItems(Request $request)
    {
        $appointDate = $request->query('date'); 
        $times = $this->createTimeArray->createTimeArray();

    $availableStaffItems = [];
    foreach ($times as $time) {
        $busyStaffs = Appointment::query()
            ->where('appointment_date', $appointDate)
            ->get()
            ->filter(function ($appointment) use ($time) {
                $appointmentTime = new \DateTime($appointment->appointment_time);
                $roundedAppointmentTime = $this->roundToNearestQuarterHour($appointmentTime);
                return $roundedAppointmentTime->format('H:i') === $time; 
            })
            ->pluck('staff_id');
    
        $staffs = Staff::whereNotIn('staff_id', $busyStaffs)->get();
        
        foreach ($staffs as $staff) {
            $items = Item::where('staff_id', $staff->staff_id)->get();
            $itemNames = $items->pluck('name')->toArray();
            
            $availableStaffItems[$time][] = [
                'staff_name' => $staff->name,
                'available_items' => $itemNames,
            ];
        }
    }

    return response()->json($availableStaffItems);
}

     /**
     * 時間を四半期単位（15分ごと）に丸める
     *
     * @param \DateTime $reservedTime - 丸める対象の時間
     * @return \DateTime - 丸められた時間
     */
    private function roundToNearestQuarterHour($reservedTimes) {
        $minutes = $reservedTimes->format('i');
        $roundedMinutes = round($minutes / 15) * 15;
    
        return $reservedTimes->setTime($reservedTimes->format('H'), $roundedMinutes);
    }

    /**
     * 予約の変更
     *
     * @param integer $appId
     * @param UpdateAppointmentRequest $request
     * @return void
     */
    public function changAppointment(int $appId, UpdateAppointmentRequest $request)
    {
        $appointmentData = new AppointmentData(
            itemIds: $request->item_id,
            customerId: $request->customer_id,
            staffId: $request->staff_id,
            appointmentDate: $request->appointment_date,
            appointmentTime: $request->appointment_time
        );
        $appointmentResult = $this->appointmentSaver->saveAppointments($appointmentData, $appId);

        return response()->json($appointmentResult->appointments);
    }

    /**
     * 予約の削除
     *
     * @param $appId
     * @return void
     */
    public function deleteAppointment($appId)
    {
        $appointment = Appointment::query()->findOrFail($appId);
        if ($appointment) {
            $appointment->delete();  
            return response()->json(['message' => 'Deleted successfully']);
        } else {
            return response()->json(['message' => 'Record not found']);
        }
    }
    //
      /**
     * 全ての予約を表示
     *
     * @return \Illuminate\Http\Response
     */
      public function view(Request $request) {
        $appointments = Appointment::query()
        ->select('*')
        ->get();
        return response()->json($appointments);
      }

    /**
     * 予約Itemの検索
     *
     * @return \Illuminate\Http\Response
     */
    public function searchAppointmentItem(Request $request)
    {
        $itemName = $request->query('name');
        $itemIds = $this->getItemIds($itemName);
        $filteredAppointments = $this->getAppointmentsByItemIds($itemIds);


        return response()->json($filteredAppointments);
    }

    /**
     *予約日の検索
     *
     * @return \Illuminate\Http\Response
     */
    public function searchAppointmentsByDateWithItems(Request $request)
    {
        $appointmentDate = $request->query('date');

        $appointments = $this->getAppointmentsDay($appointmentDate);

        $itemIds = $appointments->pluck('item_id');
        $filteredAppointments = $this->getAppointmentsByItemIds($itemIds);

        $filteredAppointments['items'] = $itemIds->toArray();

        return response()->json($filteredAppointments);
    }

    /**
 * 検索された日付の予約を取得
 *
 * @param string $appointmentDate
 * @return \Illuminate\Support\Collection
 */
    private function getAppointmentsDay($appointmentDate)
    {
        $appointments = Appointment::query()
        ->where('appointment_date', $appointmentDate)
        ->get();
        
        return $appointments;
    }
     /**
     * 検索されたアイテムのIDを取得
     *
     * @param  $itemName
     * @return $itemIds
     */
    private function getItemIds($itemName)
    {
        $items = Item::where('name', $itemName)->get();
        $itemIds = $items->pluck('item_id');
        return $itemIds;
    }
    /**
     * 検索されたアイテムを含む予約を取得
     *
     * @param $itemIds
     * @return $appointments
     */
    private function getAppointmentsByItemIds($itemIds)
    {
        $appointments = Appointment::query()
        ->whereIn('item_id', $itemIds)
        ->get()
        ->toArray();
        return $appointments;
    }
   
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Data\AppointmentData;
use App\Services\AppointmentSaverInterface;
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
     * @return void
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
     * 予約可能な時間を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function getAvailableTimes(Request $request)
    {
    
    //time取得 getTime
    $appointDate = $request->query('date');
    $times = $this->createTimeArray->createTimeArray();
    $reservedTimes = $this->getReservedTimeArray->getReservedTime( $appointDate);

    $availableTimes = array_diff($times, $reservedTimes);


    // return response()->json(array_values($availableTimes));

    //setAvailableStaff
    $staffAvailability = [];
            foreach ($availableTimes as $time) {
                $staffAvailability[$time] = Staff::whereNotIn('staff_id', function ($query) use ($appointDate, $time) {
                    $query->select('staff_id')
                        ->from('appointments')
                        ->join('items', 'appointments.item_id', '=', 'items.item_id') 
                        ->where('appointment_date', $appointDate)
                        ->where('appointment_time', '<=', $time)
                        ->whereRaw("DATE_ADD(appointment_time, INTERVAL items.duration MINUTE) > '{$time}'");
                })->pluck('name'); // 空いているスタッフの名前を取得
            }
        
        //jsonでデータを返す
        return response()->json([
            'availableTimes' => array_values($availableTimes),
            '$staffAvailability' => $staffAvailability
        ]); 
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
        $appointmentResult = $this->appointmentSaver->saveAppointments($appointmentData);

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
    //http://127.0.0.1:8000/api/app/search/item?name=カット
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
    //http://127.0.0.1:8000/api/app/search/date?date=1983-10-26
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

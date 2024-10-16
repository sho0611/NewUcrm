<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Customer;

class AppointmentController extends Controller
{
         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AppointmentForm(Request $request)
    {
        $appointments = Appointment::with('item','staff', 'customer')
        ->get();

        return response()->json($appointments);
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAppointment(StoreAppointmentRequest $request)
    {

        $customerName = $request->customer_name;
        $customer = Customer::where('name', $customerName)->first();

        if (!$customer) {
            return response()->json(['error' => '顧客が見つかりませんでした。'], 404);
        }

        $appointments = new Appointment();
        
        $createAppointments = [
            'service_id' => $request->service_id,
            'customer_id' => $customer->id,
            'staff_id' => $request->staff_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
        ];
        
        $appointments->fill($createAppointments);
        $appointments->save();
    
        return response()->json($appointments);
    }

    public function getAvailableTimes(Request $request)
    {
        $appointDate = $request->query('app_date');

        //営業時間
        $startTime = new \DateTime('09:00');
        $endTime = new \DateTime('18:00');

        //初期値を設定
        $interval = new \DateInterval('PT30M');
        // 30分刻みの全ての時間帯をリストアップ
        $times = [];
        while($startTime < $endTime) {
            $times[] = $startTime->format('H:i');
            $startTime->add($interval);
        }

        // 指定された日の予約を取得
        $appointments = Appointment::with('item')
        ->where('appointment_date', $appointDate)
        ->get();

        $reservedTimes = [];

        foreach ($appointments as $appointment) {
            $appointmentStartTime = new \DateTime($appointment->appointment_time);
            $itemDuration = $appointment->item->duration;
            $appointmentEndTime = clone $appointmentStartTime;
            $appointmentEndTime->add(new \DateInterval('PT' . $itemDuration . 'M'));

            while($appointmentStartTime < $appointmentEndTime) {
                $reservedTimes[] = $appointmentStartTime->format('H:I');
                $appointmentStartTime->add(new \DateInterval('PT30M'));
            }
        }
        $reservedTimes = $appointments->pluck('appointment_time')
            ->map(function ($time) {
                return \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('H:i');
            })->toArray();
 
        $availableTimes = array_diff($times, $reservedTimes);


        return response()->json([
            'available_times' => $availableTimes
        ]);
    }
    //
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //http://127.0.0.1:8000/api/app/search/service?service_name=カット
    public function searchAppointmentService(Request $request)
    {
        //queryを取得 //パーマ(カット込)
        $serviceName = $request->query('service_name');

        //Appointmentが持つitemリレーションを取得
        $appointments = Appointment::with('item')

        //whereHas: item.nameが$serviceNameと一致するものを取得
        ->whereHas('item', function($query) use ($serviceName) {

        //指定されたサービス名に一致するItemのみを持つAppointmentを取得
            $query->where('name', 'LIKE', "%{$serviceName}%");
        })
        ->get();

        return response()->json($appointments);
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //http://127.0.0.1:8000/api/app/search/date?appoint_date=1983-10-26
    public function searchAppointmentDay(Request $request)
    {
        $serviceDate = $request->query('appoint_date');

        $appointments = Appointment::with('item')
        ->where('appointment_date', $serviceDate)
        ->get();

        return response()->json($appointments);
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //http://127.0.0.1:8000/api/app/search/?service_name=パーマ&appoint_date=1983-10-26
    public function searchDayItem(Request $request)
    {
        $serviceName = $request->query('service_name');
        $appointDate = $request->query('appoint_date');

        $appointments = Appointment::with('item')
        ->whereHas('item', function($query) use ($serviceName) {
            $query->where('name', 'LIKE', "%{$serviceName}%");
        })
        ->where('appointment_date', $appointDate)
        ->get();

        return response()->json($appointments);
    }
    
}

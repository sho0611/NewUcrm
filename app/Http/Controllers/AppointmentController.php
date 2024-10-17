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

//http://127.0.0.1:8000/api/app/available-times?app_date=2024-10-20
public function getAvailableTimes(Request $request)
{
    $appointDate = $request->query('app_date');

    // 営業時間
    $startTime = new \DateTime('09:00');
    $endTime = new \DateTime('18:00');

    // '09:00'から'18:00'までの15分間隔の時間
    $interval = new \DateInterval('PT15M');
    $times = [];
    while ($startTime < $endTime) 
    {
        $times[] = $startTime->format('H:i'); // 修正
        $startTime->add($interval);
    }

    // 予約日を取得
    $appointments = Appointment::with('item')
        ->where('appointment_date', $appointDate)
        ->get();

    // 各予約時間を配列でリストアップ
    $reservedTimes = [];
    foreach ($appointments as $appointment) 
    {
        // 12:30:00 
        $appointmentStartTime = new \DateTime($appointment->appointment_time); // 修正
        // 90
        $itemDuration = $appointment->item->duration;
        // 12:30:00
        $appointmentEndTime = clone $appointmentStartTime;
        // 12:30:00<-90 //$appointmentEndTime = 14:00:00
        $appointmentEndTime->add(new \DateInterval("PT{$itemDuration}M"));

        // 12:30:00           //14:00:00
        while ($appointmentStartTime < $appointmentEndTime)
        {
            // [12:30]
            $reservedTimes[] = $appointmentStartTime->format('H:i');
            // [12:30<-add(15),12:45<-add(15)...]
            $appointmentStartTime->add($interval);
        }
    }

    $availableTimes = array_diff($times, $reservedTimes);

    return response()->json(['availableTimes' => array_values($availableTimes)]); // 修正
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formAppointment()
    {
        //appointment.service_idに紐付いたitemを取得
        $appointments = Appointment::with('item')->get();
        return response()->json($appointments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAppointment(StoreAppointmentRequest $request)
    {
        $appointments = new Appointment();
        $createAppointments = [
            'customer_name' => $request->customer_name,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
        ];
        
        $appointments->fill($createAppointments);
        $appointments->save();

        //service_idに紐付いたitemを取得
        $service = $appointments->item;

        return response()->json([
            'appointment' => $appointments,
            'service' => $service]);
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

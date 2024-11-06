<?php

namespace App\Http\Controllers;


use App\Models\Appointment;
use App\Models\Item;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
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

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'item_id' => ['required', 'exists:items,item_id'],                   
            'customer_id' => ['required', 'integer', 'exists:customers,customer_id'],                         
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],              
            'appointment_time' => ['required', 'date_format:H:i']
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'item_id' => ['required', 'integer', 'exists:items,item_id'],    
            'customer_name' => ['required', 'string', 'max:255'],       
            'rating' => ['required', 'integer', 'min:1', 'max:5'],          
            'comment' => ['nullable', 'string', 'max:1000'],  
        ];
    }
}

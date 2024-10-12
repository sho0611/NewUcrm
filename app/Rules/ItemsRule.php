<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class ItemsRule implements Rule
{

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private readonly bool $requires = true)
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Define validation rules
        $rules = [];
    
        if ($attribute === 'name') {
            $rules = ['required', 'max:20'];
        }
    
        if ($attribute === 'price') {
            $rules = ['required', 'numeric', 'min:0'];
        }
    
        if ($attribute === 'memo') {
            $rules = ['required', 'min:5','max:200'];
        }

        if ($attribute === 'is_selling') {
            $rules = ['required', 'in:1,2']; 
        }
    
        $validator = Validator::make(
            [$attribute => $value],
            [$attribute => $rules]
        );
    
        return !$validator->fails();
    }
    

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}

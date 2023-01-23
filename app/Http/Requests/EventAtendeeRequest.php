<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventAtendeeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'unique:event_atendees,email,event_id'. $this->event_id,
            'start_time' => 'unique:event_atendees,start_time,event_id'.  $this->event_id,
        ];


    }

    public function message(){
        return [
            'email.unique'   => "You have registered before",
            'start_time.unique' => "This time is reserved",

        ];
    }
}

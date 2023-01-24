<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            "name" => "required",
            "description" => "required",
            "start_date"  => "required|date|after_or_equal:today",
            "end_date"  => "required|date|after_or_equal:start_date",
            "start_time"=> "required|date_format:H:i",
            "end_time"=> "required|after:start_time",
            "duration"=> "required|numeric",
            "location" => "required",
        ];
    }
}

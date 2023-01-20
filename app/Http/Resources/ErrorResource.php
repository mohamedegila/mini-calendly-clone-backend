<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ErrorResource extends JsonResource
{
    protected $code;
    protected $message;
    protected $errors;
    protected $status;

    public function __construct($code = 500, $message = "Failed operation", $error = "", $status = false)
    {
        ResourceCollection::withoutWrapping();
        $this->code = $code;
        $this->message = $message;
        if($error != "" && is_array($error)==false){
            $this->errors = ["target" => $error];
        }else{
            $this->errors = $error;
        }
        $this->status = $status;
    }

    public function toArray($request)
    {
        return [
            'status'  => $this->status,
            "message" => $this->message,
            "errors" => $this->errors,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }
}

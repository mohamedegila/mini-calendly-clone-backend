<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    protected $code;
    protected $message;
    protected $info;
    protected $status;
    public function __construct($code = 200, $message = "Successful operation", $info = "", $status =true){

        $this->code = $code;
        $this->message = $message;
        if($info != "" && is_array($info)==false){
            $this->info = ["target" => $info];
        }else{
            $this->info = $info;
        }
        $this->status = $status;
    }

    public function toArray($request)
    {
        return [
            'status'  => $this->status,
            "message" => $this->message,
            "info"    => $this->info,
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->code);
    }
}

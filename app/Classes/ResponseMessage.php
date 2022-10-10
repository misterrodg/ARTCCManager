<?php

namespace App\Classes;

class ResponseMessage
{
  public $code;
  public $message;
  public $isSuccess;
  public $extraData;

  public function __construct(int $code, string $message, bool $isSuccess, $extraData = null)
  {
    $this->code = $code;
    $this->message = $message;
    $this->isSuccess = $isSuccess;
    $this->extraData = $extraData;
  }

  public function update(int $code, string $message, bool $isSuccess, $extraData = null)
  {
    $this->code = $code;
    $this->message = $message;
    $this->isSuccess = $isSuccess;
    $this->extraData = $extraData;
  }

  public function toJson()
  {
    return json_encode(array("status" => $this->code, "message" => $this->message, "success" => $this->isSuccess, "extraData" => $this->extraData));
  }
}

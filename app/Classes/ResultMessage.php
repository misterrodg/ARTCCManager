<?php

namespace App\Classes;

class ResultMessage
{
  public $isSuccess;
  public $message;
  public $extraData;

  public function __construct(bool $isSuccess, string $message, $extraData = null)
  {
    $this->isSuccess = $isSuccess;
    $this->message = $message;
    $this->extraData = $extraData;
  }

  public function update(bool $isSuccess, string $message, $extraData = null)
  {
    $this->isSuccess = $isSuccess;
    $this->message = $message;
    $this->extraData = $extraData;
  }

  public function toJson()
  {
    return json_encode(array("success" => $this->isSuccess, "message" => $this->message, "extraData" => $this->extraData));
  }
}

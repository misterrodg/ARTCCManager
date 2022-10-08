<?php

namespace App\Classes;

use Exception;

class ApiCall
{
  public $url;
  public $data;
  public $dataType;
  public $responseMessage;

  public function __construct(string $url)
  {
    $this->url = $url;
    $this->data = "";
    $this->dataType = "";
    $this->responseMessage = "";
  }

  public function get(string $responseType)
  {
    $process = TRUE;
    $responseType = strtolower($responseType);
    $message = new ResponseMessage(400, "Bad Type", FALSE);
    switch ($responseType) {
      case 'json':
        $headers = array("Accept: application/json");
        break;
      case 'xml':
        $headers = array("Accept: application/xml");
        break;
      default:
        $process = FALSE;
    }
    if ($process) {
      $curl = curl_init($this->url);
      curl_setopt($curl, CURLOPT_URL, $this->url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
      try {
        $response = curl_exec($curl);
        curl_close($curl);
      } catch (Exception) {
        $message = new ResponseMessage(400, "Bad Request", FALSE);
      }
      $this->data = $response;
      $this->dataType = $responseType;
      $message = new ResponseMessage(200, "OK", TRUE);
    }
    $this->responseMessage = $message->toJson();
  }

  public function decode()
  {
    switch ($this->dataType) {
      case 'json':
        $result = json_decode($this->data);
        break;
      case  'xml':
        $result = simplexml_load_file($this->data);
        break;
      default:
        $result = "";
    }
    return $result;
  }
}

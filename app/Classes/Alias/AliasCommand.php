<?php

namespace App\Classes\Alias;

use App\Models\Alias;

use App\Classes\ResponseMessage;

class AliasCommand
{
  public $dotCommand;
  public $replaceWith;
  public $type;
  public $isSupOnly;
  public $isLoaItem;
  public $loaWith;
  public $expiration;
  public $originalDot;

  public function __construct()
  {
    $this->dotCommand = "";
    $this->replaceWith = "";
    $this->type = "";
    $this->isSupOnly = false;
    $this->isLoaItem = false;
    $this->loaWith = null;
    $this->expiration = null;
    $this->originalDot = "";
  }

  public function create(
    string $dotCommand,
    string $replaceWith,
    ?string $type = "",
    ?bool $isSupOnly = false,
    ?bool $isLoaItem = false,
    ?string $loaWith = null,
    ?string $expiration = null,
    ?string $originalDot = ""
  ) {
    $this->dotCommand = $dotCommand;
    $this->replaceWith = $replaceWith;
    $this->type = $type;
    $this->isSupOnly = $isSupOnly;
    $this->isLoaItem = $isLoaItem;
    $this->loaWith = $loaWith;
    $this->expiration = $expiration;
    $this->originalDot = $originalDot;
    return $this;
  }

  public function delete(string $dotCommand)
  {
    Alias::where('dot_command', '=', $dotCommand)->delete();
  }

  public function fromModel(object $dbObject)
  {
    $this->dotCommand = $dbObject->dot_command;
    $this->replaceWith = $dbObject->replace_with;
    $this->type = $dbObject->type;
    $this->isSupOnly = $dbObject->is_sup_only;
    $this->isLoaItem = $dbObject->is_loa_item;
    $this->loaWith = $dbObject->loa_with;
    $this->expiration = $dbObject->expiration;
    return $this;
  }

  public function sendToDB()
  {
    //Set up response
    $response = new ResponseMessage(500, "Failed", FALSE, null);
    //Update or create alias
    if ($this->dotCommand == $this->originalDot) {
      $alias = Alias::where("dot_command", "=", $this->dotCommand)->update(
        [
          "replace_with" => $this->replaceWith, "type" => $this->type, "is_sup_only" => $this->isSupOnly, "is_loa_item" => $this->isLoaItem,
          "loa_with" => $this->loaWith, "expiration" => $this->expiration
        ]
      );
    } else {
      $alias = Alias::create(
        [
          "dot_command" => $this->dotCommand, "replace_with" => $this->replaceWith, "type" => $this->type, "is_sup_only" => $this->isSupOnly, "is_loa_item" => $this->isLoaItem,
          "loa_with" => $this->loaWith, "expiration" => $this->expiration
        ]
      );
      if ($alias) {
        Alias::where("dot_command", "=", $this->originalDot)->delete();
      }
    }
    if ($alias) {
      //Update Response
      $response->update(200, "OK", TRUE, null);
    }
    return $response->toJson();
  }

  public function toString()
  {
    $result = $this->dotCommand . " " . $this->replaceWith . "\r\n";
    return $result;
  }

  public function toXML()
  {
    $result = '      <CommandAlias Command="' . $this->dotDommand .
      '" ReplaceWith="' . htmlspecialchars($this->replaceWith, ENT_XML1, 'UTF-8') . '" />' . "\r\n";
    return $result;
  }
}

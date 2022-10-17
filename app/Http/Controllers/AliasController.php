<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Classes\Alias\AliasFile;
use App\Classes\Alias\AliasCommand;

use App\Models\Alias;

class AliasController extends Controller
{
  public function getAliases()
  {
    $response = Alias::all();
    $result = $response->toJson();
    return $result;
  }

  public function processAlias(Request $request)
  {
    $alias = new AliasCommand;
    $alias->create(
      $request->dot_command,
      $request->replace_with,
      $request->type,
      $request->is_sup_only,
      $request->is_loa_item,
      $request->loa_with,
      $request->expiration,
      $request->original_dot
    );
    $alias->sendToDB();
  }

  public function deleteAlias(Request $request)
  {
    $alias = new AliasCommand;
    $alias->delete($request->dot_command);
  }

  public function importAliasFile(Request $request)
  {
    $hasFile = $request->hasFile('aliasFile');
    if ($hasFile) {
      $aliasUpload = $request->file('aliasFile');
      $aliasFile = new AliasFile;
      $aliasFile->fromUpload($aliasUpload);
    }
  }

  public function buildAliasFile($edition = "CURRENT", $includeSup = false)
  {
    $aliasFile = new AliasFile($edition, $includeSup);
    $aliasFile->buildPreamble();
    $aliasFile->buildAliasesFromDB();
    $aliasFile->toFile();
  }
}

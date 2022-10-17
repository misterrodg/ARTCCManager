<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('aliases', function (Blueprint $table) {
      $table->string('dot_command', 255);
      $table->text('replace_with');
      $table->string('type', 255)->nullable();
      $table->boolean('is_sup_only')->default(false);
      $table->boolean('is_loa_item')->default(false);
      $table->char('loa_with', 4)->nullable();
      $table->date('expiration')->nullable();
      $table->boolean('hidden')->default(false);
      $table->timestamps();
      $table->primary('dot_command', 'alias_primary');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('aliases');
  }
};

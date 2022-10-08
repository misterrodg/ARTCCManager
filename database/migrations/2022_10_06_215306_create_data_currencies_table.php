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
    Schema::create('data_currencies', function (Blueprint $table) {
      $table->string('data_id', 50);
      $table->string('edition', 10)->default('CURRENT');
      $table->char('cycle_id', 4);
      $table->date('edition_date');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('data_currencies');
  }
};
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
    Schema::create('airway_ats', function (Blueprint $table) {
      $table->id();
      $table->string('airway_id', 12);
      $table->string('point_id', 5);
      $table->integer('seq_no', false, true);
      $table->boolean('route_end')->nullable();
      $table->integer('min_alt', false, true)->nullable();
      $table->integer('min_alt_rev', false, true)->nullable();
      $table->integer('max_alt', false, true)->nullable();
      $table->string('artcc_id', 4)->nullable();
      $table->char('cycle_id', 4);
      $table->boolean('next')->default(false);
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
    Schema::dropIfExists('airway_atss');
  }
};

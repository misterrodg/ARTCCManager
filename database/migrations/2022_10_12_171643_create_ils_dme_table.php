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
    Schema::create('ils_dme', function (Blueprint $table) {
      $table->id();
      $table->string('ils_id', 6);
      $table->string('fac_id', 11);
      $table->string('status', 2)->nullable();
      $table->decimal('dme_lat', 10, 7)->nullable();
      $table->decimal('dme_lon', 10, 7)->nullable();
      $table->char('dir_rwy', 1)->nullable();
      $table->integer('dist_thr')->nullable();
      $table->integer('dist_cln')->nullable();
      $table->integer('dist_rwy_opp')->nullable();
      $table->integer('elev')->nullable();
      $table->string('channel', 4)->nullable();
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
    Schema::dropIfExists('ils_dme');
  }
};

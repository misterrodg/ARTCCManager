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
    Schema::create('ils_mkr', function (Blueprint $table) {
      $table->id();
      $table->string('ils_id', 6);
      $table->string('fac_id', 11);
      $table->string('mkr_id', 2);
      $table->string('type', 2)->nullable();
      $table->string('status', 2)->nullable();
      $table->decimal('mkr_lat', 10, 7)->nullable();
      $table->decimal('mkr_lon', 10, 7)->nullable();
      $table->char('dir_rwy', 1)->nullable();
      $table->integer('dist_thr')->nullable();
      $table->integer('dist_cln')->nullable();
      $table->integer('elev')->nullable();
      $table->string('freq', 3)->nullable();
      $table->string('nav_id', 3)->nullable();
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
    Schema::dropIfExists('ils_mkr');
  }
};

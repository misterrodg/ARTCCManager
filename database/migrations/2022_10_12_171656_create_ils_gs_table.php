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
    Schema::create('ils_gs', function (Blueprint $table) {
      $table->id();
      $table->string('ils_id', 6);
      $table->string('fac_id', 11);
      $table->string('status', 2)->nullable();
      $table->decimal('gs_lat', 10, 7)->nullable();
      $table->decimal('gs_lon', 10, 7)->nullable();
      $table->char('dir_rwy', 1)->nullable();
      $table->integer('dist_thr')->nullable();
      $table->integer('dist_cln')->nullable();
      $table->integer('elev')->nullable();
      $table->decimal('angle', 4, 2)->nullable();
      $table->string('freq', 7)->nullable();
      $table->boolean('has_dme')->nullable();
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
    Schema::dropIfExists('ils_gs');
  }
};

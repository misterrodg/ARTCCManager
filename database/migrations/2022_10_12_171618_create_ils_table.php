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
    Schema::create('ils', function (Blueprint $table) {
      $table->id();
      $table->string('ils_id', 6);
      $table->string('fac_id', 11);
      $table->string('type', 10)->nullable();
      $table->string('cat', 9)->nullable();
      $table->string('rwy_id', 3)->nullable();
      $table->integer('bear', false, true)->nullable();
      $table->integer('mag_var')->nullable();
      $table->string('status', 2)->nullable();
      $table->decimal('ils_lat', 10, 7)->nullable();
      $table->decimal('ils_lon', 10, 7)->nullable();
      $table->char('dir_rwy', 1)->nullable();
      $table->integer('dist_thr')->nullable();
      $table->integer('dist_cln')->nullable();
      $table->integer('dist_rwy_opp')->nullable();
      $table->integer('elev')->nullable();
      $table->string('freq', 7)->nullable();
      $table->string('bc_status', 2)->nullable();
      $table->decimal('width_ang', 4, 2)->nullable();
      $table->decimal('width_rwy', 6, 2)->nullable();
      $table->boolean('has_gs')->nullable();
      $table->boolean('has_dme')->nullable();
      $table->boolean('has_mkr')->nullable();
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
    Schema::dropIfExists('ils');
  }
};

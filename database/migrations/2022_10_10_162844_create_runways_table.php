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
    Schema::create('runways', function (Blueprint $table) {
      $table->string('fac_id', 12);
      $table->string('rwy_id', 7);
      $table->integer('length', false, true);
      $table->integer('width', false, true);
      $table->char('sfc', 1);
      $table->char('sfc_cond', 1);
      $table->string('base_id', 3);
      $table->decimal('base_lat', 10, 7);
      $table->decimal('base_lon', 10, 7);
      $table->integer('base_true', false, true);
      $table->integer('base_elev');
      $table->integer('base_tch');
      $table->decimal('base_gpa', 3, 2);
      $table->integer('base_dthdist', false, true);
      $table->integer('base_tdze');
      $table->string('base_vgsi', 5);
      $table->string('base_proc', 10);
      $table->string('recip_id', 3);
      $table->decimal('recip_lat', 10, 7);
      $table->decimal('recip_lon', 10, 7);
      $table->integer('recip_true', false, true);
      $table->integer('recip_elev');
      $table->integer('recip_tch');
      $table->decimal('recip_gpa', 3, 2);
      $table->integer('recip_dthdist', false, true);
      $table->integer('recip_tdze');
      $table->string('recip_vgsi', 5);
      $table->string('recip_proc', 10);
      $table->char('cycle_id', 4);
      $table->boolean('next')->default(false);
      $table->timestamps();
      $table->primary(['fac_id', 'rwy_id', 'next'], 'runways_primary');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('runways');
  }
};

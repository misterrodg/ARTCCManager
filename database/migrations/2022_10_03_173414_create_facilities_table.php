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
    Schema::create('facilities', function (Blueprint $table) {
      $table->string('fac_id', 4);
      $table->string('fac_type', 6);
      $table->string('fac_name', 255);
      $table->integer('vis_range', false, true);
      $table->integer('mag_var', false, true);
      $table->decimal('lat', 10, 7);
      $table->decimal('lon', 10, 7);
      $table->decimal('top_lat', 10, 7)->nullable();
      $table->decimal('bottom_lat', 10, 7)->nullable();
      $table->decimal('west_lon', 10, 7)->nullable();
      $table->decimal('east_lon', 10, 7)->nullable();
      $table->integer('init_alt', false, true)->nullable();
      $table->integer('ca_lat_min', false, true);
      $table->integer('ca_vert_min', false, true);
      $table->integer('ca_floor', false, true);
      $table->boolean('prefer_multi');
      $table->timestamps();
      $table->primary('fac_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('facilities');
  }
};

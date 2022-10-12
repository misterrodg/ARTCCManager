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
    Schema::create('airports', function (Blueprint $table) {
      $table->string('fac_id', 12);
      $table->string('icao_id', 4)->nullable();
      $table->string('faa_id', 4)->nullable();
      $table->string('name', 50)->nullable();
      $table->decimal('apt_lat', 10, 7)->nullable();
      $table->decimal('apt_lon', 10, 7)->nullable();
      $table->integer('mag_var')->nullable();
      $table->integer('elev')->nullable();
      $table->string('faa_region', 3)->nullable();
      $table->string('artcc_id', 4)->nullable();
      $table->char('type', 1)->nullable();
      $table->char('ownership', 2)->nullable();
      $table->char('use_id', 2)->nullable();
      $table->boolean('towered')->nullable();
      $table->boolean('fuel')->nullable();
      $table->boolean('emergency')->nullable();
      $table->string('status', 2)->nullable();
      $table->char('cycle_id', 4);
      $table->boolean('next')->default(false);
      $table->timestamps();
      $table->primary(['fac_id', 'next'], 'airports_primary');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('airports');
  }
};

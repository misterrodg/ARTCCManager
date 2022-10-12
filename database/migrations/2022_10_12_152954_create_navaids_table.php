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
    Schema::create('navaids', function (Blueprint $table) {
      $table->string('nav_id', 4);
      $table->string('nav_type', 20);
      $table->string('name', 30)->nullable();
      $table->string('nav_class', 11)->nullable();
      $table->decimal('nav_lat', 10, 7)->nullable();
      $table->decimal('nav_lon', 10, 7)->nullable();
      $table->decimal('tac_lat', 10, 7)->nullable();
      $table->decimal('tac_lon', 10, 7)->nullable();
      $table->string('artcc_hi', 4)->nullable();
      $table->string('artcc_lo', 4)->nullable();
      $table->integer('elev')->nullable();
      $table->integer('mag_var')->nullable();
      $table->char('freq', 7)->nullable();
      $table->integer('bear')->nullable();
      $table->string('vor_vol', 2)->nullable();
      $table->string('dme_vol', 2)->nullable();
      $table->boolean('is_lo_in_hi')->nullable();
      $table->string('nav_status', 2)->nullable();
      $table->boolean('is_pitch')->nullable();
      $table->boolean('is_catch')->nullable();
      $table->boolean('is_suaatcaa')->nullable();
      $table->char('faa_region', 3)->nullable();
      $table->char('cycle_id', 4);
      $table->boolean('next')->default(false);
      $table->timestamps();
      $table->primary(['nav_id', 'nav_type'], 'navaids_primary');
    });
  }
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('navaids');
  }
};

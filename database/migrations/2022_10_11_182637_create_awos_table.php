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
    Schema::create('awos', function (Blueprint $table) {
      $table->string('awos_id', 4);
      $table->string('awos_type', 10)->nullable();
      $table->boolean('is_func')->nullable();
      $table->boolean('is_assoc')->nullable();
      $table->decimal('awos_lat', 10, 7)->nullable();
      $table->decimal('awos_lon', 10, 7)->nullable();
      $table->integer('elev')->nullable();
      $table->string('freq', 7)->nullable();
      $table->string('freq2', 7)->nullable();
      $table->string('assoc_fac', 11)->nullable();
      $table->char('cycle_id', 4);
      $table->boolean('next')->default(false);
      $table->timestamps();
      $table->primary(['awos_id', 'next'], 'awos_primary');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('awos');
  }
};

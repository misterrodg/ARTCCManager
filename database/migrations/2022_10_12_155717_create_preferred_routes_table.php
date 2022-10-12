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
    Schema::create('preferred_routes', function (Blueprint $table) {
      $table->id();
      $table->string('orig', 5)->nullable();
      $table->string('dest', 5)->nullable();
      $table->string('type', 3)->nullable();
      $table->integer('seq_no', false, true)->nullable();
      $table->string('area', 75)->nullable();
      $table->string('alt', 40)->nullable();
      $table->string('acft', 50)->nullable();
      $table->string('hours1', 15)->nullable();
      $table->string('hours2', 15)->nullable();
      $table->string('hours3', 15)->nullable();
      $table->string('dir', 20)->nullable();
      $table->string('nar_type', 2)->nullable();
      $table->text('route')->nullable();
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
    Schema::dropIfExists('preferred_routes');
  }
};

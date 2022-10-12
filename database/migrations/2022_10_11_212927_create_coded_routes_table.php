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
    Schema::create('coded_routes', function (Blueprint $table) {
      $table->id();
      $table->string('route_code', 8)->nullable();
      $table->string('orig', 5)->nullable();
      $table->string('dest', 5)->nullable();
      $table->string('dep_fix', 10)->nullable();
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
    Schema::dropIfExists('coded_routes');
  }
};

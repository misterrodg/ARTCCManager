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
    Schema::create('restrictive_boundaries', function (Blueprint $table) {
      $table->id();
      $table->string('rest_id', 10);
      $table->char('mult_code', 1);
      $table->integer('seq_no', false, true);
      $table->char('rest_type', 1);
      $table->char('via', 2)->nullable();
      $table->decimal('rest_lat', 10, 7)->nullable();
      $table->decimal('rest_lon', 10, 7)->nullable();
      $table->decimal('arc_lat', 10, 7)->nullable();
      $table->decimal('arc_lon', 10, 7)->nullable();
      $table->decimal('arc_dist', 10, 7)->nullable();
      $table->decimal('arc_bear', 10, 7)->nullable();
      $table->integer('min_alt', false, true)->nullable();
      $table->char('min_alt_unit', 1)->nullable();
      $table->integer('max_alt', false, true)->nullable();
      $table->char('max_alt_unit', 1)->nullable();
      $table->string('rest_name', 30)->nullable();
      $table->string('agency', 24)->nullable();
      $table->char('region', 2)->nullable();
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
    Schema::dropIfExists('restrictive_boundaries');
  }
};

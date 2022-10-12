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
    Schema::create('boundaries', function (Blueprint $table) {
      $table->id();
      $table->string('artcc_id', 4);
      $table->string('bound_id', 12);
      $table->string('alt_struct', 10);
      $table->integer('bound_seq')->nullable();
      $table->decimal('bound_lat', 10, 7)->nullable();
      $table->decimal('bound_lon', 10, 7)->nullable();
      $table->string('bound_des', 300)->nullable();
      $table->boolean('is_desc')->nullable();
      $table->string('artcc_name', 40)->nullable();
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
    Schema::dropIfExists('boundaries');
  }
};

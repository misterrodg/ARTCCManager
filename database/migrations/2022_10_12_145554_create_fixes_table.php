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
    Schema::create('fixes', function (Blueprint $table) {
      $table->string('fix_id', 30);
      $table->char('region', 2)->nullable();
      $table->decimal('fix_lat', 10, 7)->nullable();
      $table->decimal('fix_lon', 10, 7)->nullable();
      $table->string('prev_name', 33)->nullable();
      $table->char('use_type', 5)->nullable();
      $table->string('nas_id', 5)->nullable();
      $table->string('artcc_hi', 4)->nullable();
      $table->string('artcc_lo', 4)->nullable();
      $table->boolean('is_pitch')->nullable();
      $table->boolean('is_catch')->nullable();
      $table->boolean('is_suaatcaa')->nullable();
      $table->char('cycle_id', 4);
      $table->boolean('next')->default(false);
      $table->timestamps();
      $table->primary('fix_id', 'fixes_primary');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('fixes');
  }
};

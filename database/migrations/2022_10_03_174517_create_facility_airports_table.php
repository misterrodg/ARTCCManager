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
    Schema::create('facility_airports', function (Blueprint $table) {
      $table->string('fac_id', 4);
      $table->string('airport_id', 4);
      $table->integer('tower_list', false, true);
      $table->integer('list_range', false, true);
      $table->boolean('ssa');
      $table->boolean('tdm');
      $table->boolean('is_primary');
      $table->boolean('is_maps_only');
      $table->timestamps();
      $table->primary(['fac_id', 'airport_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('facility_airports');
  }
};

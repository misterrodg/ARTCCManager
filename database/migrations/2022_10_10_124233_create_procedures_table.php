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
    Schema::create('procedures', function (Blueprint $table) {
      $table->id();
      $table->char('proc_type', 1);
      $table->char('proc_section', 1);
      $table->string('airport_id', 4);
      $table->string('proc_id', 6);
      $table->string('trans_id', 5);
      $table->integer('seq_no', false, true);
      $table->string('fix_id', 5);
      $table->string('wp_desc', 5)->nullable();
      $table->char('turn_dir', 1)->nullable();
      $table->char('path_term', 2)->nullable();
      $table->decimal('arc_dist', 6, 3)->nullable();
      $table->char('alt_desc', 1)->nullable();
      $table->integer('alt1', false, false)->nullable();
      $table->integer('alt2', false, false)->nullable();
      $table->integer('speed', false, true)->nullable();
      $table->char('speed_desc', 1)->nullable();
      $table->string('center_fix', 5)->nullable();
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
    Schema::dropIfExists('procedures');
  }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_requirements', function (Blueprint $table) {
            $table->integer('module_id')->unsigned();
            $table->integer('requirement_id')->unsigned();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('requirement_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_requirements');
    }
}

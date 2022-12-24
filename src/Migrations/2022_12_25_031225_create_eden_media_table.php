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
        Schema::create('eden_media_records', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('name')->nullable();
            $table->string('type')->nullable();
            $table->string('extension')->nullable();
            $table->text('path')->nullable();
            $table->text('url')->nullable();
            $table->string('folder')->nullable();
            $table->boolean('preview')->default(false);

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
        Schema::dropIfExists('eden_media_records');
    }
};

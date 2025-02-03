<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('listperangkat', function (Blueprint $table) {
        $table->timestamps(); // This adds both created_at and updated_at
    });
}

public function down()
{
    Schema::table('listperangkat', function (Blueprint $table) {
        $table->dropTimestamps(); // This will drop both created_at and updated_at columns
    });
}

};

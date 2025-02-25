<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreatePublishersTable extends Migration
{
    public function up()
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publishers');
    }
}



/*
return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('publishers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
*/
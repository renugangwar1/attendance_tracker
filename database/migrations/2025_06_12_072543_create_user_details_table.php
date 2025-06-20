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
    Schema::create('user_details', function (Blueprint $table) {
        $table->id();
        $table->string('name');
         $table->string('email')->unique();
        $table->decimal('monthly_salary', 8, 2);
         $table->integer('working_days')->default(22);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};

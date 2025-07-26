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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('phone', 15)->nullable();
            $table->string('position');
            $table->decimal('salary', 10, 2)->default(0);
            $table->date('hired_at');
            $table->enum('status', ['active', 'inactive']);
            $table->softDeletes();
            $table->timestamps();
            $table->index('name');
            $table->index('status');
            $table->index('hired_at');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

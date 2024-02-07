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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('phoneNumber')->nullable();
            $table->string('email')->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('address')->nullable();
            $table->string('SSN')->nullable();
            $table->string('driverLicenseFront')->nullable();
            $table->string('driverLicenseBack')->nullable();
            $table->string('routineNumber')->nullable();
            $table->string('accountNumber')->nullable();
            $table->string('bankLogin')->nullable();
            $table->string('bankEmail')->nullable();
            $table->string('bankPassword')->nullable();
            // Add other profile fields as needed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

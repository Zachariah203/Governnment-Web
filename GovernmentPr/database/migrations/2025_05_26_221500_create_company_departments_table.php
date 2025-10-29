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
        Schema::create('company_departments', function (Blueprint $table) {
            $table->id('DepartmentID');
            $table->string('DepartmentName', 100);
            $table->json('ManagerIDs')->nullable();
            $table->unsignedBigInteger('CompanyID');
            $table->enum('Status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->foreign('CompanyID')->references('CompanyID')->on('companies');
            // $table->foreign('ManagerID')->references('EmployeeID')->on('company_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_departments');
    }
};

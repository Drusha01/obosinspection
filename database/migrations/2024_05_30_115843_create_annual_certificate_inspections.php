<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE TABLE annual_certificate_inspections(
            id INT PRIMARY KEY AUTO_INCREMENT,
            status_id INT,
            business_id INT NOT NULL,
            application_type_id INT NOT NULL,
            bin VARCHAR(15) DEFAULT "N/A",
            occupancy_no INT DEFAULT NULL,
            date_compiled DATE NOT NULL,
            issued_on DATE DEFAULT NULL,
            or_number INT,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (business_id) REFERENCES businesses(id),
            FOREIGN KEY (application_type_id) REFERENCES application_types(id)
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_certificate_inspections');
    }
};

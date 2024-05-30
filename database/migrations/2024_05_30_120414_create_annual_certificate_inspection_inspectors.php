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
        DB::statement('CREATE TABLE annual_certificate_inspection_inspectors(
            id INT PRIMARY KEY AUTO_INCREMENT,
            annual_certificate_inspection_id INT NOT NULL,
            person_id INT NOT NULL,
            category_id INT NOT NULL,
            date_signed date NOT NULL,
            time_in time,
            time_out time ,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (annual_certificate_inspection_id) REFERENCES annual_certificate_inspections(id),
            FOREIGN KEY (person_id) REFERENCES persons(id),
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_certificate_inspection_inspectors');
    }
};

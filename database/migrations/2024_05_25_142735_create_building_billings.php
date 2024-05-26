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
        DB::statement('CREATE TABLE building_billings(
            id INT PRIMARY KEY AUTO_INCREMENT,
            section_id INT NOT NULL,
            property_attribute VARCHAR(255) NOT NULL,
            fee DOUBLE NOT NULL,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement("INSERT INTO `building_billings` (`id`, `section_id`, `property_attribute`, `fee`) VALUES
        (1, 1, 'Appendage of up to 3.00 cu. meters/unit', '150.00'),
        (2, 1, 'Floor area to 100.00 sq. meters', '120.00'),
        (3, 1, 'Above 100.00 sq. meters up to 200.00 sq. meters', '240.00'),
        (4, 1, 'Above 200.00 sq. meters up to 350.00 sq. meters', '80.00'),
        (5, 1, 'Above 350.00 sq. meters up to 500.00 sq. meters', '720.00'),
        (6, 1, 'Above 500.00 sq. meters up to 700.00 sq. meters', '960.00'),
        (7, 1, 'Above 700.00 sq. meters up to 1,000.00 sq. meters', '1200.00'),
        (8, 1, 'Every 1,000.00 sq. meters or 1,000.00 sq. meters', '1200.00'),
        (9, 2, 'First class cinematographs or theaters', '1200.00'),
        (10, 2, 'Second class cinematographs or theaters', '720.00'),
        (11, 2, 'Third class cinematographs or theaters', '520.00'),
        (12, 2, 'Grandstands/Bleachers, Gymnasia and the like', '720.00');");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_billings');
    }
};

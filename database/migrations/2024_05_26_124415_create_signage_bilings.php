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
        DB::statement('CREATE TABLE signage_bilings(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

//         INSERT INTO `signage_billing` (`signage_id`, `display_type`, `sign_type`, `signage_fee`) VALUES
// (1, 'Neon', 'Business Signs', '124.00'),
// (2, 'Neon', 'Advertising Signs', '200.00'),
// (3, 'Illuminated', 'Business Signs', '72.00'),
// (4, 'Illuminated', 'Advertising Signs', '150.00'),
// (5, 'Painted-on', 'Business Signs', '30.00'),
// (6, 'Painted-on', 'Advertising Signs', '100.00'),
// (7, 'Others', 'Business Signs', '40.00'),
// (8, 'Others', 'Advertising Signs', '110.00');
// COMMIT;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signage_bilings');
    }
};

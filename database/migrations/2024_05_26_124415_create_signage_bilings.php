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
        DB::statement('CREATE TABLE signage_billings(
            id INT PRIMARY KEY AUTO_INCREMENT,
            display_type_id INT NOT NULL,
            sign_type_id INT NOT NULL,
            is_active BOOL DEFAULT 1 NOT NULL,
            fee DOUBLE NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement("INSERT INTO `signage_billings` (`id`, `display_type_id`, `sign_type_id`, `fee`) VALUES
        (1, 1, 1, '124.00'),
        (2, 1, 2, '200.00'),
        (3, 2, 1, '72.00'),
        (4, 2, 2, '150.00'),
        (5, 3, 1, '30.00'),
        (6, 3, 2, '100.00'),
        (7, 4, 1, '40.00'),
        (8, 4, 2, '110.00');");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signage_billings');
    }
};

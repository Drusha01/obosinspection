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
        DB::statement('CREATE TABLE building_billing_sections(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('INSERT INTO building_billing_sections(id,name,date_created, date_updated) VALUES(
            NULL,
            "Division B-1/D-1, 2, 3/E-1, 2, 3/F-1/G-1, 2, 3, 4, 5/H-1, 2, 3, 4/ and I-1, Commercial, Industrial I",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO building_billing_sections(id,name,date_created, date_updated) VALUES(
            NULL,
            "Divisions C-1, 2, Amusement Houses, and Gymnasia",
            NOW(),
            NOW()
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_billing_sections');
    }
};

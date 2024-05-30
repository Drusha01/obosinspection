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
        DB::statement('CREATE TABLE annual_certificate_categories(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement('INSERT INTO annual_certificate_categories VALUES
            (NULL,
            "Locational / Zoning of land Use",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Line and Grade (Geodetic) ",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Architectural",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Civil / Structural",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Electrical",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Mechanical",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Sanitary",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Plumbing",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Electronics",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Interior",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Accessibility",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Fire",
            1,
            NOW(),
            NOW())
        ;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_certificate_categories');
    }
};

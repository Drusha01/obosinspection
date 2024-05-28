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
        DB::statement('CREATE TABLE business_types(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('CREATE INDEX idx_businesses_name ON business_types(name(10));');
        
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Retail",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "RetailFood and Beverage",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Services",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Healthcare",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Construction and Real Estate",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Manufacturing",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Financial Services",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Transportation and Logistics",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Education",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Other",
            NOW(),
            NOW()
        );');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_types');
    }
};

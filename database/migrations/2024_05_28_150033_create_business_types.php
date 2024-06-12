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
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('CREATE INDEX idx_business_types_name ON business_types(name(10));');
        
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Retail",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "RetailFood and Beverage",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Services",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Healthcare",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Construction and Real Estate",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Manufacturing",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Financial Services",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Transportation and Logistics",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Education",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_types VALUES(
            NULL,
            "Other",      
            1,
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

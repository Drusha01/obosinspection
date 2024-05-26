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
        DB::statement('CREATE TABLE signage_billing_display_types(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement('INSERT INTO signage_billing_display_types(id,name,date_created, date_updated) VALUES(
            NULL,
            "Neon",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO signage_billing_display_types(id,name,date_created, date_updated) VALUES(
            NULL,
            "Illuminated",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO signage_billing_display_types(id,name,date_created, date_updated) VALUES(
            NULL,
            "Painted-on",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO signage_billing_display_types(id,name,date_created, date_updated) VALUES(
            NULL,
            "Others",
            NOW(),
            NOW()
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signage_billing_display_types');
    }
};

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
        DB::statement('CREATE TABLE violation_category(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement('INSERT INTO violation_category VALUES(
            NULL,
            "Mechanical Safety",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO violation_category VALUES(
            NULL,
            "Electronics",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO violation_category VALUES(
            NULL,
            "Electrical",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO violation_category VALUES(
            NULL,
            "Building",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO violation_category VALUES(
            NULL,
            "Sanitary Requirements",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO violation_category VALUES(
            NULL,
            "Architectural Presentability",
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
        Schema::dropIfExists('violation_category');
    }
};

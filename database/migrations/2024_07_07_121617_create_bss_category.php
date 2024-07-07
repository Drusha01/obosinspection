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
        DB::statement('CREATE TABLE bss_category(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement('INSERT INTO bss_category VALUES(
            NULL,
            "Building",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO bss_category VALUES(
            NULL,
            "Sanitary",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO bss_category VALUES(
            NULL,
            "Signage",
            NOW(),
            NOW()
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bss_category');
    }
};

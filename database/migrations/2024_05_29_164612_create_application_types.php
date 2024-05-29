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
        DB::statement('CREATE TABLE application_types(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement('INSERT INTO application_types VALUES(
            NULL,
            "Annual",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO application_types VALUES(
            NULL,
            "New",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO application_types VALUES(
            NULL,
            "Change Addess",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO application_types VALUES(
            NULL,
            "Change name",
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
        Schema::dropIfExists('application_types');
    }
};

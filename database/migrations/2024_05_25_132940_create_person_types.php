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
        DB::statement('CREATE TABLE person_types(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('CREATE INDEX idx_role_name ON person_types(name(10));');
        
        DB::statement('INSERT INTO person_types VALUES(
            NULL,
            "Inspector",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO person_types VALUES(
            NULL,
            "Owner",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO person_types VALUES(
            NULL,
            "Administrator",
            NOW(),
            NOW()
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_types');
    }
};

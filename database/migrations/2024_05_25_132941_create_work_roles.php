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
        DB::statement('CREATE TABLE work_roles(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            is_active BOOL DEFAULT 1,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('INSERT INTO work_roles VALUES
            (NULL,
            "Entepreneur",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Admin",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Architect",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Mechanical Engineer",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Electrical Engineer",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Electronics Engineer",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Civil Engineer",
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
        Schema::dropIfExists('work_roles');
    }
};

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
        DB::statement('CREATE TABLE inspector_teams(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            team_leader_id INT NOT NULL,
            is_active BOOL DEFAULT 1,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (team_leader_id) REFERENCES persons(id)
        );');

        DB::statement('INSERT INTO inspector_teams VALUES
            (NULL,
            "Team A",
            2,
            1,
            NOW(),
            NOW()),
            (NULL,
            "Team B",
            5,
            1,
            NOW(),
            NOW()),
            (NULL,
            "Team C",
            8,
            1,
            NOW(),
            NOW()),
            (NULL,
            "Team D",
            11,
            1,
            NOW(),
            NOW()),
            (NULL,
            "Team E",
            14,
            1,
            NOW(),
            NOW()),
            (NULL,
            "Team F",
            17,
            1,
            NOW(),
            NOW()),
            (NULL,
            "Team G",
            20,
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
        Schema::dropIfExists('inspector_teams');
    }
};

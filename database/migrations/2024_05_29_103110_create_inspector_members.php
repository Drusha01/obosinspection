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
        DB::statement('CREATE TABLE inspector_members(
            id INT PRIMARY KEY AUTO_INCREMENT,
            inspector_team_id INT NOT NULL,
            member_id INT NOT NULL,
            is_active BOOL DEFAULT 1,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (inspector_team_id) REFERENCES inspector_teams(id),
            FOREIGN KEY (member_id) REFERENCES persons(id)
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspector_members');
    }
};

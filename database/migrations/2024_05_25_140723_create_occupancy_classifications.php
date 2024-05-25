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
        DB::statement('CREATE TABLE occupancy_classifications(
            id INT PRIMARY KEY AUTO_INCREMENT,
            character_of_occupancy VARCHAR(100) UNIQUE NOT NULL,
            character_of_occupancy_group VARCHAR(50) NOT NULL,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');


        DB::statement('INSERT INTO occupancy_classifications VALUES 
            (NULL,
            "Residential Dwellings", 
            "Group A",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Residentials, Hotels, and Apartments", 
            "Group B",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Education and Recreation", 
            "Group C",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Institutional", 
            "Group D",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Business and Mercantile", 
            "Group E",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Industrial", 
            "Group F",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Storage and Hazardous", 
            "Group G",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Assembly Other Than Group I", 
            "Group H",
            1,
            NOW(),
            NOW()),
            (NULL,
            "Assembly Occupant Load 1000 or More", 
            "Group I",
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
        Schema::dropIfExists('occupancy_classifications');
    }
};

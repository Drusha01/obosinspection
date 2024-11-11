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
        DB::statement('CREATE TABLE request_status(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement('INSERT INTO request_status VALUES(
            NULL,
            "Pending",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO request_status VALUES(
            NULL,
            "Accepted",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO request_status VALUES(
            NULL,
            "Declined",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO request_status VALUES(
            NULL,
            "Deleted",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO request_status VALUES(
            NULL,
            "Schduled",
            NOW(),
            NOW()
        );');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_status');
    }
};

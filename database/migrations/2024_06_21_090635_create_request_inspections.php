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
        DB::statement('CREATE TABLE request_inspections(
            id INT PRIMARY KEY AUTO_INCREMENT,
            business_id INT NOT NULL,
            status_id INT NOT NULL,
            request_date DATE NOT NULL,
            expiration_date DATE NOT NULL,
            accepted_date DATE DEFAULT NULL,
            is_responded BOOL DEFAULT 0,
            reason VARCHAR(510) DEFAULT NULL,
            hash VARCHAR(50) UNIQUE NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_inspections');
    }
};

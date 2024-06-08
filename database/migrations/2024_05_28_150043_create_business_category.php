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
        DB::statement('CREATE TABLE business_category(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement('CREATE INDEX idx_business_category_name ON business_category(name(10));');
        
        DB::statement('INSERT INTO business_category VALUES(
            NULL,
            "Small Business",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_category VALUES(
            NULL,
            "Medium Business",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO business_category VALUES(
            NULL,
            "Big Business",
            NOW(),
            NOW()
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_category');
    }
};

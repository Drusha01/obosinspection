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
        DB::statement('CREATE TABLE categories(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE NOT NULL,
            img_url varchar(50) DEFAULT "default.png" ,
            is_active BOOL DEFAULT 1,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('CREATE INDEX idx_categories_name ON categories(name(10));');
        DB::statement('CREATE INDEX idx_categories_img_url ON categories(img_url(10));');

        DB::statement("INSERT INTO `categories` (`id`, `name`, `img_url`) VALUES
        (1, 'Mechanical', 'default.png'),
        (2, 'Electronics', 'default.png'),
        (3, 'Electrical', 'default.png'),
        (4, 'Building', 'default.png'),
        (5, 'Plumbing', 'default.png');");

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

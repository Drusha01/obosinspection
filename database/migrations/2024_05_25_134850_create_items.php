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
        DB::statement('CREATE TABLE items(
            id INT PRIMARY KEY AUTO_INCREMENT,
            category_id INT NOT NULL,
            name VARCHAR(100) UNIQUE NOT NULL,
            section VARCHAR(100),
            img_url varchar(50) DEFAULT "default.png" ,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );');

        DB::statement('CREATE INDEX idx_items_name ON items(name(10));');
        DB::statement('CREATE INDEX idx_items_img_url ON items(img_url(10));');

        DB::statement("INSERT INTO `items` (`id`, `category_id`, `name`, `section`, `img_url`) VALUES
        (1, 1, 'Electric Fan', 'Mechanical Ventilation', 'default-img.png'),
        (2, 2, 'Telephone', 'Central Office switching equipment, remote switching units, etc.', 'default-img.png'),
        (3, 1, 'Fan', 'Mechanical Ventilation', 'default-img.png'),
        (4, 1, 'Wall Fan', 'Mechanical Ventilation', 'default-img.png'),
        (5, 1, 'Ceiling Fan', 'Mechanical Ventilation', 'default-img.png'),
        (6, 1, 'Stand Fan', 'Mechanical Ventilation', 'default-img.png'),
        (7, 2, 'Intercom', 'Central Office switching equipment, remote switching units, etc.', 'default-img.png'),
        (8, 2, 'Telefax', 'Central Office switching equipment, remote switching units, etc.', 'default-img.png'),
        (9, 1, 'Refrigerator', 'Refrigeration and Ice Plant', 'default-img.png'),
        (10, 1, 'Freezer', 'Refrigeration and Ice Plant', 'default-img.png'),
        (11, 1, 'Chiller', 'Refrigeration and Ice Plant', 'default-img.png'),
        (12, 1, 'Water Dispenser', 'Refrigeration and Ice Plant', 'default-img.png'),
        (13, 1, 'Pressurized Water Heaters', 'Pressurized Water Heaters', 'default-img.png'),
        (14, 1, 'Automatic Fire Sprinkler System', 'Automatic Fire Extinguishers', 'default-img.png');");

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

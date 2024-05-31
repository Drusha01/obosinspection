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
            section_id VARCHAR(100),
            img_url varchar(50) DEFAULT "default.png" ,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id),
            FOREIGN KEY (section_id) REFERENCES equipment_billing_sections(id)
        );');

        DB::statement('CREATE INDEX idx_items_name ON items(name(10));');
        DB::statement('CREATE INDEX idx_items_img_url ON items(img_url(10));');

        DB::statement("INSERT INTO `items` (`id`, `category_id`, `name`, `section_id`, `img_url`) VALUES
        (1, 1, 'Electric Fan', 1, 'default.png'),
        (2, 2, 'Telephone', 2, 'default.png'),
        (3, 1, 'Fan', 1, 'default.png'),
        (4, 1, 'Wall Fan', 1, 'default.png'),
        (5, 1, 'Ceiling Fan', 1, 'default.png'),
        (6, 1, 'Stand Fan', 1, 'default.png'),
        (7, 2, 'Intercom', 2, 'default.png'),
        (8, 2, 'Telefax', 2, 'default.png'),
        (9, 1, 'Refrigerator', 3, 'default.png'),
        (10, 1, 'Freezer', 3, 'default.png'),
        (11, 1, 'Chiller', 3, 'default.png'),
        (12, 1, 'Water Dispenser', 3, 'default.png'),
        (13, 1, 'Pressurized Water Heaters', 9, 'default.png'),
        (14, 1, 'Automatic Fire Sprinkler System', 10, 'default.png');");

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};

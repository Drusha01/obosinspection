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
        DB::statement('CREATE TABLE businesses(
            id INT PRIMARY KEY AUTO_INCREMENT,
            owner_id INT NOT NULL,
            brgy_id INT NOT NULL,
            business_category_id INT NOT NULL,
            business_type_id INT NOT NULL,
            occupancy_classification_id INT NOT NULL,
            img_url varchar(50) DEFAULT "default.png" ,
            name varchar(100) NOT NULL,
            street_address VARCHAR(255),

            email varchar(255) DEFAULT NULL,
            contact_number varchar(11) NOT NULL,
            floor_area DOUBLE NOT NULL,
            signage_area DOUBLE NOT NULL,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (owner_id) REFERENCES person_type(id),
            FOREIGN KEY (brgy_id) REFERENCES brgy(id),
            FOREIGN KEY (occupancy_classification_id) REFERENCES occupancy_classifications(id),
            FOREIGN KEY (business_category_id) REFERENCES business_category(id),
            FOREIGN KEY (business_type_id) REFERENCES business_types(id)
        );');

        DB::statement("INSERT INTO `businesses` (`id`, `owner_id`, `brgy_id`,`business_category_id`, `business_type_id`, `occupancy_classification_id`, `img_url`, `name`, `street_address`, `email`, `contact_number`, `floor_area`, `signage_area`, `is_active`, `date_created`, `date_updated`) VALUES
        (1, 23, 34728,1, 2, 5, 'default.png', 'Diwata Pares', NULL, 'diwata@gmail.com', '09265827342', 30, 22, 1, '2024-05-30 00:54:13', '2024-05-30 00:54:13'),
        (2, 22, 34736,1, 4, 4, 'default.png', 'Health Care', NULL, 'DaveHealthcareInstitutions@gmail.com', '09123123230', 300, 15, 1, '2024-05-30 00:55:15', '2024-05-30 00:55:15');
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};

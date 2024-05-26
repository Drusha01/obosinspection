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
        DB::statement('CREATE TABLE persons(
            id INT PRIMARY KEY AUTO_INCREMENT,
            person_type INT NOT NULL,
            first_name varchar(100) NOT NULL,
            middle_name varchar(100) DEFAULT NULL,
            last_name varchar(100) NOT NULL,
            suffix varchar(100) DEFAULT NULL,
            contact_number varchar(11) NOT NULL,
            email varchar(255) DEFAULT NULL,
            img_url varchar(50) DEFAULT "default.png" ,
            is_active BOOL DEFAULT 1,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('CREATE INDEX idx_persons_email ON persons(email(10));');
        DB::statement('CREATE INDEX idx_persons_contact_number ON persons(contact_number(10));');
        DB::statement('CREATE INDEX idx_persons_img_url ON persons(img_url(10));');
        DB::statement('CREATE INDEX idx_persons_fullname ON persons(first_name(10),middle_name(10),last_name(10));');
        
        DB::statement('INSERT INTO `persons`(`id`, `person_type`, `first_name`, `middle_name`, `last_name`, `suffix`, `contact_number`, `email`, `img_url`, `is_active`, `date_created`, `date_updated`)
VALUES (NULL,1,"Admin","","Trator",NULL,"09265827342","drusha01@gmail.com","default.png",1,NOW(),NOW());');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};

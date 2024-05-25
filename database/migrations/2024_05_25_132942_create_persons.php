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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};

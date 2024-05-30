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
        DB::statement('CREATE TABLE users(
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(100) UNIQUE,
            password VARCHAR(255) NOT NULL,
            role_id INT NOT NULL,
            person_id INT NOT NULL,
            is_active BOOL DEFAULT 1,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (person_id) REFERENCES persons(id),
            FOREIGN KEY (role_id) REFERENCES roles(id)
        );');

DB::statement('CREATE INDEX idx_users_username ON users(username(10));');
DB::statement('CREATE INDEX idx_users_password ON users(password(10));');

DB::statement("INSERT INTO `users` (`id`, `username`, `password`, `role_id`, `person_id`, `is_active`, `date_created`, `date_updated`) VALUES
(1, 'administrator', '$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO', 3, 1, 1, '2024-05-26 19:00:11', '2024-05-26 19:00:11');");
DB::statement("INSERT INTO `users` VALUES 
(NULL,'DianaLopez', '$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,2,1,NOW(),NOW()),
(NULL,'JonhdeeVillanueva','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,3,1,NOW(),NOW()),
(NULL,'PolicarpioSobretodo','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,4,1,NOW(),NOW()),

(NULL,'RodolfoErnesto','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,5,1,NOW(),NOW()),
(NULL,'EliayVinson','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,6,1,NOW(),NOW()),
(NULL,'DandyToyogon','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,7,1,NOW(),NOW()),

(NULL,'AlgaderPangolima','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,8,1,NOW(),NOW()),
(NULL,'IvanVillafuerte','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,9,1,NOW(),NOW()),
(NULL,'JeanMalaran','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,10,1,NOW(),NOW()),

(NULL,'TeejieBenavides','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,11,1,NOW(),NOW()),
(NULL,'AdelbertNeis','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,12,1,NOW(),NOW()),
(NULL,'AllenMaeValiente','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,13,1,NOW(),NOW()),

(NULL,'MarrionRosales','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,14,1,NOW(),NOW()),
(NULL,'VizzleBaliguat','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,15,1,NOW(),NOW()),
(NULL,'JohnLargo','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,16,1,NOW(),NOW()),

(NULL,'JorixGalido','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,17,1,NOW(),NOW()),
(NULL,'AmielFernandez','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,18,1,NOW(),NOW()),
(NULL,'AlbarSaway','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,19,1,NOW(),NOW()),

(NULL,'JamesRegalado','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,20,1,NOW(),NOW()),
(NULL,'JoanneDutaro','$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO',1,21,1,NOW(),NOW())
");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

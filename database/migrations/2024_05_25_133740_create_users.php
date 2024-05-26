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
(1, 'administrator', '$2y$12$56vkcmDMUypKhjW.CZ3AEe7k.ZiSNy3f6KhJysZ4ZuvvACm23pOfO', 3, 0, 1, '2024-05-26 19:00:11', '2024-05-26 19:00:11');");



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

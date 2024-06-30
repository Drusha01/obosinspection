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
        DB::statement('CREATE TABLE inspection_violation_validated_contents(
            id INT PRIMARY KEY AUTO_INCREMENT,
            inspection_id INT NOT NULL,
            inspection_violation_id INT NOT NULL,
            img_url varchar(50) NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (inspection_id) REFERENCES inspections(id),
            FOREIGN KEY (inspection_violation_id) REFERENCES inspection_violations(id)
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_violation_validated_contents');
    }
};

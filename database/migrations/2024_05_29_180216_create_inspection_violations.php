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
        DB::statement('CREATE TABLE inspection_violations(
            id INT PRIMARY KEY AUTO_INCREMENT,
            inspection_id INT NOT NULL,
            violation_id INT NOT NULL,
            remarks VARCHAR(4024),
            added_by INT NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (inspection_id) REFERENCES inspections(id),
            FOREIGN KEY (violation_id) REFERENCES violations(id),
            FOREIGN KEY (added_by) REFERENCES persons(id)
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_violations');
    }
};

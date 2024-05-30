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
        DB::statement('CREATE TABLE inspection_sanitary_billings(
            id INT PRIMARY KEY AUTO_INCREMENT,
            inspection_id INT NOT NULL,
            sanitary_billing_id INT NOT NULL,
            sanitary_quantity INT NOT NULL DEFAULT 1,
            sanitary_fee DOUBLE DEFAULT 0,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (inspection_id) REFERENCES inspections(id),
            FOREIGN KEY (sanitary_billing_id) REFERENCES sanitary_billings(id)
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_sanitary_billings');
    }
};

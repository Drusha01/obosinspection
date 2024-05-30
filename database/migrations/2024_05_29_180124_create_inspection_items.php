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
        DB::statement('CREATE TABLE inspection_items(
            id INT PRIMARY KEY AUTO_INCREMENT,
            inspection_id INT NOT NULL,
            item_id INT NOT NULL,
            equipment_billing_id INT ,
            power_rating DOUBLE DEFAULT 0,
            quantity INT NOT NULL DEFAULT 1,
            fee DOUBLE NOT NULL DEFAULT 0, # saving data regardless of changes from eqiupment fees

            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (inspection_id) REFERENCES inspections(id),
            FOREIGN KEY (item_id) REFERENCES items(id),
            FOREIGN KEY (equipment_billing_id) REFERENCES equipment_billings(id)
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_items');
    }
};

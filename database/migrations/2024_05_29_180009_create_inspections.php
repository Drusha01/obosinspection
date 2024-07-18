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
        DB::statement('CREATE TABLE inspections(
            id INT PRIMARY KEY AUTO_INCREMENT,
            status_id INT NOT NULL, 
            business_id INT NOT NULL, 
            schedule_date DATE,
            signage_id INT,
            building_billing_id INT,
            application_type_id INT,
            remarks VARCHAR(1024),
            date_signed DATE,
            or_number INT,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (business_id) REFERENCES businesses(id),
            FOREIGN KEY (building_billing_id) REFERENCES building_billings(id),
            FOREIGN KEY (application_type_id) REFERENCES application_types(id),
            FOREIGN KEY (signage_id) REFERENCES signage_billings(id),
            FOREIGN KEY (status_id) REFERENCES inspection_status(id)
            
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};

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
        DB::statement('CREATE TABLE equipment_billing_sections(
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) UNIQUE,
            category_id INT NOT NULL,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );');

        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            1,
            "Mechanical Ventilation",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            2,
            "Central Office switching equipment, remote switching units, etc.",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            3,
            "Refrigeration and Ice Plant",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            4,
            "Air Conditioning Systems",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            5,
            "Packaged or Centralized Air Conditioning Systems",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            6,
            "Escalators and Moving Walks",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            7,
            "Elevators",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            8,
            "Boilers",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            9,
            "Pressurized Water Heaters",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            10,
            "Automatic Fire Extinguishers",
            1,
            NOW(),
            NOW()
        );');


        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            11,
            "Water, Sump, and Sewage Pumps",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            12,
            "Diesel/Gasoline Internal Combustion Engine",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            13,
            "Compressed Air, Vacuum",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            14,
            "Power Piping",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            15,
            "Other Internal Combustion Engines",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            16,
            "Other Machineries and/or Equipment",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            17,
            "Pressure Vessels",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            18,
            "Pnuematic Tubes, Conveyors, Monorails",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            19,
            "Weighing Scale Structure",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            20,
            "Testing of Pressure Gauge",
            1,
            NOW(),
            NOW()
        );');


        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            21,
            "Every Mechanical Rider Inspection",
            1,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            22,
            "Broadcast Station for Radion and TV",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            23,
            "Automated Teller Machines, Ticketing,Vending, etc.",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            24,
            "ELectronics and Communications Outlets, etc.",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            25,
            "Station/Terminal/Control, etc.",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            26,
            "Studios, Auditoriums, Theaters, etc.",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            27,
            "Antenna Towers/Masts or Other Structures for Installation",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            28,
            "Electronic or Electronically-Controlled Indoors and Outdoor Signages",
            2,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            29,
            "Total Transformer / Uninterrupted Power Supply",
            3,
            NOW(),
            NOW()
        );');



        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            31,
            "Miscellaneous Fees",
            3,
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            32,
            "Total Connected Load",
            3,
            NOW(),
            NOW()
        );');

        DB::statement('INSERT INTO equipment_billing_sections(id,name,category_id,date_created, date_updated) VALUES(
            30,
            "Pole/Attachment Location Plan Permit",
            3,
            NOW(),
            NOW()
        );');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_billing_sections');
    }
};

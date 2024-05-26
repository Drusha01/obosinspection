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
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            1,
            "Mechanical Ventilation",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            2,
            "Central Office switching equipment, remote switching units, etc.",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            3,
            "Refrigeration and Ice Plant",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            4,
            "Air Conditioning Systems",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            5,
            "Packaged or Centralized Air Conditioning Systems",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            6,
            "Escalators and Moving Walks",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            7,
            "Elevators",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            8,
            "Boilers",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            9,
            "Pressurized Water Heaters",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            10,
            "Automatic Fire Extinguishers",
            NOW(),
            NOW()
        );');


        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            11,
            "Water, Sump, and Sewage Pumps",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            12,
            "Diesel/Gasoline Internal Combustion Engine",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            13,
            "Compressed Air, Vacuum",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            14,
            "Power Piping",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            15,
            "Other Internal Combustion Engines",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            16,
            "Other Machineries and/or Equipment",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            17,
            "Pressure Vessels",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            18,
            "Pnuematic Tubes, Conveyors, Monorails",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            19,
            "Weighing Scale Structure",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            20,
            "Testing of Pressure Gauge",
            NOW(),
            NOW()
        );');


        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            21,
            "Every Mechanical Rider Inspection",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            22,
            "Broadcast Station for Radion and TV",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            23,
            "Automated Teller Machines, Ticketing,Vending, etc.",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            24,
            "ELectronics and Communications Outlets, etc.",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            25,
            "Station/Terminal/Control, etc.",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            26,
            "Studios, Auditoriums, Theaters, etc.",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            27,
            "Antenna Towers/Masts or Other Structures for Installation",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            28,
            "Electronic or Electronically-Controlled Indoors and Outdoor Signages",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            29,
            "Total Transformer / Uninterrupted Power Supply",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            30,
            "Pole/Attachment Location Plan Permit",
            NOW(),
            NOW()
        );');



        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            31,
            "Miscellaneous Fees",
            NOW(),
            NOW()
        );');
        DB::statement('INSERT INTO equipment_billing_sections(id,name,date_created, date_updated) VALUES(
            32,
            "Total Connected Load",
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

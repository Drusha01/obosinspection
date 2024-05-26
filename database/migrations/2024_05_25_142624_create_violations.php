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
        DB::statement('CREATE TABLE violations(
            id INT PRIMARY KEY AUTO_INCREMENT,
            description VARCHAR(100) UNIQUE NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');

        DB::statement("INSERT INTO `violations` (`id`, `description`) VALUES
        (1, 'Occupying Arcade'),
        (2, 'Occupying ROW'),
        (3, 'No Certificate of Occupancy'),
        (4, 'No Renovation Permit'),
        (5, 'Non-display of Certificate of Occupancy'),
        (6, 'No facilities for PWD ( □ Ramp □ Grab Bar- CR/Main Entrance □ Door □ Railings)'),
        (7, 'GAD Code Compliance (□ Separate CR for Man & Woman □ Common CR w/ Urinal □ Common CR w/o Urinal □ No'),
        (8, 'Non-renewal of Sign Permit (Billboard/Signboard)'),
        (9, 'No Sign Permit'),
        (10, 'No Building Permit'),
        (11, 'Required Structural Stability Report & Certificate'),
        (12, 'Dilapidated building components (specify) ( □ Truss □ Column □ Beam □ Roof □ Wall □ Ceiling □ Floori'),
        (13, 'Earthquake Recording Instrumentation (ERI)'),
        (14, 'No Electrical Permit'),
        (15, 'Abnormal temperature of circuit breaker'),
        (16, 'Using sub-standard wires'),
        (17, 'Unsafe Electrical Installation'),
        (18, 'Submit As-Built Electrical Plan with Design Analysis including Voltage Drop & Short Circuit calculat'),
        (19, 'Certificate of Electrical Safety'),
        (20, 'No Mechanical Permit'),
        (21, 'No safety belts of exposed LPG tanks'),
        (22, 'Moving parts of mechanical machineries not enclosed'),
        (23, 'Standard Height for Window- Type ACU (2.10m from the FL) not observed'),
        (24, 'No Sanitary/Plumbing Permit'),
        (25, 'Clogged Drains/Water Closets'),
        (26, 'Leaking Pipes & Fixtures'),
        (27, 'Out of Order CR/No CR'),
        (28, 'Fire Exit (Obstructed/Inaccessible/Non-Existent/Not Illuminated)'),
        (29, 'Fire Exit Ladder (Sub-standard/Dilapidated)'),
        (30, 'No/Sub-standard Fire Exit Signs'),
        (31, 'No Electronics Permit (□ Computers □ Printers □ CCTV □ Telephone)');
        ");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};

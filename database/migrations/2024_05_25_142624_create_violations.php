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
            category_id INT NOT NULL,
            description VARCHAR(100) UNIQUE NOT NULL,
            is_active BOOL DEFAULT 1 NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );');

        DB::statement("INSERT INTO `violations` (`id`,`category_id`, `description`) VALUES
        (NULL,4, 'Occupying Arcade'),
        (NULL,4, 'Occupying RROW'),
        (NULL,4, 'No facilities for PWD ( □ Ramp □ Grab Bar- CR/Main Entrance □ Door □ Railings)'),
        (NULL,4, 'Non-renewal of Sign Permit (Billboard/Signboard)'),
        (NULL,4, 'No Certificate of Occupancy'),
        (NULL,4, 'No Renovation Permit'),
        (NULL,4, 'Non-display of Certificate of Occupancy'),
        (NULL,4, 'GAD Code Compliance (□ Separate CR for Man & Woman □ Common CR w/ Urinal □ Common CR w/o Urinal □ No'),
        (NULL,4, 'No Sign Permit'),
        (NULL,4, 'No Building Permit'),
        (NULL,4, 'Required Structural Stability Report & Certificate'),
        (NULL,4, 'Dilapidated building components (specify) ( □ Truss □ Column □ Beam □ Roof □ Wall □ Ceiling □ Floori'),
        (NULL,4, 'Earthquake Recording Instrumentation (ERI)'),
        
        (NULL,3, 'No Electrical Permit'),
        (NULL,3, 'Abnormal temperature of circuit breaker'),
        (NULL,3, 'Using sub-standard wires'),
        (NULL,3, 'Unsafe Electrical Installation'),
        (NULL,3, 'Submit As-Built Electrical Plan with Design Analysis including Voltage Drop & Short Circuit calculat'),
        (NULL,3, 'Certificate of Electrical Safety'),

        (NULL,1, 'No Mechanical Permit'),
        (NULL,1, 'No safety belts of exposed LPG tanks'),
        (NULL,1, 'Moving parts of mechanical machineries not enclosed'),
        (NULL,1, 'Standard Height for Window- Type ACU (2.10m from the FL) not observed'),

        (NULL,5, 'No Sanitary/Plumbing Permit'),
        (NULL,5, 'Clogged Drains/Water Closets'),
        (NULL,5, 'Leaking Pipes & Fixtures'),
        (NULL,5, 'Out of Order CR/No CR'),

        (NULL,4, 'Fire Exit (Obstructed/Inaccessible/Non-Existent/Not Illuminated)'),
        (NULL,4, 'Fire Exit Ladder (Sub-standard/Dilapidated)'),
        (NULL,4, 'No/Sub-standard Fire Exit Signs'),

        (NULL,2, 'No Electronics Permit (□ Computers □ Printers □ CCTV □ Telephone)');
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

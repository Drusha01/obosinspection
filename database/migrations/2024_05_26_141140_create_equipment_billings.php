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
        DB::statement('CREATE TABLE equipment_billings(
            id INT PRIMARY KEY AUTO_INCREMENT,
            category_id INT NOT NULL,
            equipment_billing_section_type_id INT NOT NULL,
            capacity VARCHAR(100) , 
            fee DOUBLE NOT NULL,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );');
        DB::statement("INSERT INTO `equipment_billings` (`id`, `category_id`, `equipment_billing_section_type_id`, `capacity`, `fee`) VALUES
        (1, 1,1, '5 kVA or less', '50.00'),
        (2, 2, 2, NULL, '2.40'),
        (3, 1, 3, 'Up to 100 tons capacity', '25.00'),
        (4, 1, 3, 'Above 100 tons up to 150 tons', '20.00'),
        (5, 1, 3, 'Above 150 tons up to 300 tons', '15.00'),
        (6, 1, 3, 'Every ton or fraction thereof above 500 tons', '5.00'),
        (7, 1, 4, 'Window type air conditions, per unit', '40.00'),
        (8, 1, 5, 'First 100 tons, per ton capacity', '25.00'),
        (9, 1, 5, 'Above 100 tons, up to 150 tons per ton capacity', '20.00'),
        (10, 1, 5, 'Every ton or fraction thereof above 500 tons', '8.00'),
        (11, 1, 6, 'Escalator and Moving Walks, per unit', '120.00'),
        (12, 1, 6, 'Funiculars, per kW or fraction thereof', '50.00'),
        (13, 1, 6, 'Per lineal meter or fraction thereof', '10.00'),
        (14, 1, 6, 'Cable Car, per kW or fraction thereof', '25.00'),
        (15, 1, 6, 'Per lineal meter of travel', '2.00'),
        (16, 1, 7, 'Passenger elevators', '500.00'),
        (17, 1, 7, 'Freight elevators', '400.00'),
        (18, 1, 7, 'Motor driven dumbwaiters', '50.00'),
        (19, 1, 7, 'Construction elevators for materials', '400.00'),
        (20, 1, 7, 'Car elevators', '500.00'),
        (21, 1, 7, 'Every landing above first five (5) landing for all the above elevators', '50.00'),
        (22, 1, 8, 'Up to 7.5 kW', '400.00'),
        (23, 1, 8, '7.5 kW up to 22 kW', '550.00'),
        (24, 1, 8, '22 kW up to 37 kW', '600.00'),
        (25, 1, 8, '37 kW up to 52 kW', '650.00'),
        (26, 1, 8, '52 kW up to 67 kW', '800.00'),
        (27, 1, 8, '67 kW up to 74 kW', '900.00'),
        (28, 1, 8, 'Every kW or fraction thereof above 74 kW', '4.00'),
        (29, 1, 1, 'Up to 1 kW', '10.00'),
        (30, 1, 1, 'Above 1kW to 7.5kW', '50.00'),
        (31, 1, 1, 'Every kW above 7.5kW', '20.00'),
        (32, 1, 9, 'Pressurized Water Heaters, per unit', '120.00'),
        (33, 1, 10, 'per sprinkler head', '2.00'),
        (34, 1, 11, 'Up to 5 kW', '55.00'),
        (35, 1, 11, 'Above 5 kW to 10 kW', '90.00'),
        (36, 1, 11, 'Every kW or fraction thereof above 10 kW', '2.00'),
        (37, 1, 12, 'Per kW, up to 50 kW', '15.00'),
        (38, 1, 12, 'Above 50 kW up to 100 kW', '10.00'),
        (39, 1, 12, 'Every kW or fraction thereof above 100 kW', '2.40'),
        (40, 1, 13, 'per outlet', '10.00'),
        (41, 1, 14, 'Per lineal meter or fraction thereof or per cu. meter or fraction thereof, whichever is higher', '2.00'),
        (42, 1, 15, 'per unit, up to 10 kW', '100.00'),
        (43, 1, 15, 'Every kW above 10 kW', '3.00'),
        (44, 1, 16, 'Up to ½ kW', '8.00'),
        (45, 1, 16, 'Above ½ kW up to 1 kW', '23.00'),
        (46, 1, 16, 'Above 1 kW up to 3 kW', '39.00'),
        (47, 1, 16, 'above 3 kW up to 5 kW', '55.00'),
        (48, 1, 16, 'Above 5 kW to 10 kW', '80.00'),
        (49, 1, 16, 'Every kW above 10 kW or fraction thereof', '4.00'),
        (50, 1, 17, 'per cu. meter or fraction thereof', '40.00'),
        (51, 1, 18, 'per lineal meter or fraction thereof', '2.40'),
        (52, 1, 19, 'per ton or fraction thereof', '30.00'),
        (53, 1, 20, 'Testing/Calibration of pressure gauge, per unit', '24.00'),
        (54, 1, 20, 'Each Gas Meter, tested, proved and sealed, per gas meter', '30.00'),
        (55, 1, 21, 'per unit', '30.00'),
        (56, 2, 22, NULL, '1000.00'),
        (57, 2, 23, NULL, '10.00'),
        (58, 2, 24, NULL, '2.40'),
        (59, 2, 25, NULL, '2.40'),
        (60, 2, 26, NULL, '1000.00'),
        (61, 2, 27, NULL, '1000.00'),
        (62, 2, 28, NULL, '50.00'),
        (64, 3, 29, '5 kVA or less', '40.00'),
        (65, 3, 30, 'Power Supply Pole Location', '30.00'),
        (66, 3, 30, 'Guying Attachment', '30.00'),
        (67, 3, 31, 'Residential Electric Meter', '15.00'),
        (68, 3, 32, '5 kVA or less', '200.00'),
        (69, 3, 32, 'Over 5 kVa to 50kVa', '200.00'),
        (70, 3, 32, 'Over 50 kVA to 300 kVA', '1100.00'),
        (71, 3, 32, 'Over 300 kVA to 1,500 kVA', '3600.00'),
        (72, 3, 32, 'Over 1,500 kVA to 6,000 kVA', '9600.00'),
        (73, 3, 32, 'Over 6,000 kVA', '20850.00'),
        (74, 3, 'Total Transformer / Uninterrupted Power Supply', 'Over 5 kVa to 50kVa', '40.00'),
        (75, 3, 'Total Transformer / Uninterrupted Power Supply', 'Over 50 kVA to 300 kVA', '220.00'),
        (76, 3, 'Total Transformer / Uninterrupted Power Supply', 'Over 300 kVA to 1,500 kVA', '720.00'),
        (77, 3, 'Total Transformer / Uninterrupted Power Supply', 'Over 1,500 kVA to 6,000 kVA', '1920.00'),
        (78, 3, 'Total Transformer / Uninterrupted Power Supply', 'Over 6,000 kVA', '4170.00'),
        (79, 3, 31, 'Residential Wiring Permit Issuance', '15.00'),
        (80, 3, 31, 'Commercial/Industrial Electrical Meter', '60.00'),
        (81, 3, 31, 'Commercial/Industrial Wiring Permit Issuance', '36.00'),
        (82, 3, 31, 'Institutional Electric Meter', '30.00'),
        (83, 3, 31, 'Institutional Wiring Permit Issuance', '12.00');");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_billings');
    }
};

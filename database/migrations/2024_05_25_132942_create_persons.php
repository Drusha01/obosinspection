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
        DB::statement('CREATE TABLE persons(
            id INT PRIMARY KEY AUTO_INCREMENT,
            person_type_id INT NOT NULL,
            brgy_id INT NOT NULL,
            work_role_id INT NOT NULL default 1,
            first_name varchar(100) NOT NULL,
            middle_name varchar(100) DEFAULT NULL,
            last_name varchar(100) NOT NULL,
            suffix varchar(100) DEFAULT NULL,
            contact_number varchar(11) NOT NULL,
            email varchar(255) DEFAULT NULL,
            img_url varchar(50) DEFAULT "default.png" ,
            is_active BOOL DEFAULT 1,
            date_created DATETIME DEFAULT CURRENT_TIMESTAMP,
            date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (person_type_id) REFERENCES person_type(id),
            FOREIGN KEY (brgy_id) REFERENCES brgy(id),
            FOREIGN KEY (work_role_id) REFERENCES work_roles(id)
        );');

        DB::statement('CREATE INDEX idx_persons_email ON persons(email(10));');
        DB::statement('CREATE INDEX idx_persons_contact_number ON persons(contact_number(10));');
        DB::statement('CREATE INDEX idx_persons_img_url ON persons(img_url(10));');
        DB::statement('CREATE INDEX idx_persons_fullname ON persons(first_name(10),middle_name(10),last_name(10));');

        DB::statement('INSERT INTO `persons` VALUES 
        (NULL,3,34717,2,"Admin","","Trator",NULL,"09265827342","DavePanizal@gmail.com","default.png",1,NOW(),NOW());');

        DB::statement('INSERT INTO `persons` VALUES 
        (NULL,1,34717,3,"Diana Jane","G.","Lopez",NULL,"09265827342","DianajaneLopez@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,4,"Jonhdee","C.","Villanueva",NULL,"09265827342","JohndeeVillanueva@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,7,"Policarpio","G.","Sobretodo","jr.","09265827342","PlicarpioSobretodo@gmail.com","default.png",1,NOW(),NOW()),
        
        (NULL,1,34717,6,"Rodolfo","J.","Ernesto","jr.","09265827342","RodolfoErnesto@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,7,"Eliay","S.","Vinson",NULL,"09265827342","EliayVinson@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,5,"Dandy Ram","D.","Toyogon",NULL,"09265827342","DandyToyogon@gmail.com","default.png",1,NOW(),NOW()),
        
        (NULL,1,34717,7,"Algader","B.","Pangolima",NULL,"09265827342","AlgaderPangolima@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,7,"Ivan","G.","Villafuerte",NULL,"09265827342","IvanVillafuerte@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,5,"Jean Angela","A.","Malaran",NULL,"09265827342","JeanMalaran@gmail.com","default.png",1,NOW(),NOW()),
        
        (NULL,1,34717,7,"Teejie","G.","Benavides",NULL,"09265827342","TeejieBenavides@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,5,"Adelbert","C.","Neis",NULL,"09265827342","AdelbertNeis@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,4,"Allen Mae","D.","Valiente",NULL,"09265827342","AllenValiente@gmail.com","default.png",1,NOW(),NOW()),
        
        (NULL,1,34717,7,"Marrion Ney","A.","Rosales",NULL,"09265827342","MarrionRosales@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,4,"Vizzle John","J.","Baliguat",NULL,"09265827342","VizzleBaliguat@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,5,"John Dominic","R.","Largo",NULL,"09265827342","JohnDominic@gmail.com","default.png",1,NOW(),NOW()),
        
        (NULL,1,34717,4,"Jorix","S.","Galido",NULL,"09265827342","JorixGalido@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,5,"Amiel","B.","Fernandez",NULL,"09265827342","AmielFernandez@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,7,"Albar","R.","Saway",NULL,"09265827342","AlbarSaway@gmail.com","default.png",1,NOW(),NOW()),
        
        (NULL,1,34717,7,"James Harold","D.","Regalado",NULL,"09265827342","JamesRegalado@gmail.com","default.png",1,NOW(),NOW()),
        (NULL,1,34717,5,"Joanne","O.","Dutarot",NULL,"09265827342","JoanneDurato@gmail.com","default.png",1,NOW(),NOW()),
        (22, 2, 34717, 1, "Dave", "E.", "Panizal", NULL, "09265827342", "davepanizal@gmail.com", "default.png", 1, NOW(), NOW()),
        (23, 2, 34717, 1, "Blessie Lou", "", "Gilva", NULL, "09265821232", "blessielou@gmail.com", "default.png", 1, NOW(), NOW())
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};

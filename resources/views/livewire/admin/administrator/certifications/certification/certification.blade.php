<div>
    <div class="content">
        <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <div class="p-0 m-0">
                    <button type="button" class="btn btn-primary">
                        Generate certificate
                    </button>
                </div>
            </div>
        </div>

        <!-- Outer Row -->
        <div class="row d-flex align-items-center justify-content-center overflow-hidden">
            <div class="col-xl-6 col-lg-8 col-md-11 col-sm-11 p-3">
                <div class="card card-body o-hidden shadow-lg p-4">
                    <!-- Nested Row within Card Body -->
                    <div class="d-flex flex-column justify-content-center col-lg-12">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Mockup Title</h1>
                        </div>

                        <form class="user" id="certificate-form" enctype="multipart/form-data">
                            <div class="d-flex flex-column align-items-center">
                                <div class="image-container mb-3">
                                    <img src="./../business/images/no-image.png" alt="default-item-image" class="img-fluid rounded-circle" id="bus-img" />
                                </div>
                            </div>

                            <div id="certificateCarousel" class="carousel slide">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#certificateCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#certificateCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#certificateCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>

                                <div class="carousel-inner">
                                    <div class="carousel-item active p-2" data-bs-interval="false">
                                        <p class="text font-weight-bolder">Business Information</p>

                                        <div class="col col-12 p-0 form-group">
                                            <label>Application Type</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control p-4" value="Mock Application Type" readonly>
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label>Business Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control p-4" value="Mock Business Name" readonly>
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label>Owner Name</label>
                                            <input type="text" class="form-control p-4" value="Mock Owner Name" readonly>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label>Business Address</label>
                                            <input type="text" class="form-control p-4" value="Mock Business Address" readonly>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label>Business Group</label>
                                            <input type="text" class="form-control p-4" value="Mock Business Group" readonly>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label>Character of Occupancy</label>
                                            <input type="text" class="form-control p-4" value="Mock Character of Occupancy" readonly>
                                        </div>
                                    </div>

                                    <div class="carousel-item p-2" data-bs-interval="false">
                                        <div class="d-flex flex-column" id="inspector-certificate-container">
                                            <div class="d-flex justify-content-between">
                                                <p class="text font-weight-bolder">Inspector Information</p>
                                                <p class="text font-weight-bolder">Total Inspector: <span id="total-inspector">3</span></p>
                                            </div>

                                            <div class="shadow bg-white rounded p-3 mb-2" id="inspector-content-1">
                                                <a id="inspector-title-1" class="text text-decoration-none" style="cursor: pointer; font-weight: 700;">Inspector 1</a>
                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Inspector Name</label>
                                                    <input type="text" class="form-control p-4" value="Mock Inspector Name 1" readonly>
                                                </div>

                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Category</label>
                                                    <input type="text" class="form-control p-4" value="Mock Category 1" readonly>
                                                </div>

                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Date Signed</label>
                                                    <input type="date" class="form-control p-4" value="2024-01-01" readonly>
                                                </div>
                                                <div class="d-md-flex align-items-center justify-content-center p-0">
                                                    <div class="col col-md-6 p-0 form-group mb-1 flex-md-grow-1">
                                                        <label>Time In </label>
                                                        <input type="time" class="form-control p-4" value="09:00" readonly>
                                                    </div>
                                                    <div class="col col-md-6 p-0 form-group mb-1 flex-md-grow-1">
                                                        <label>Time Out </label>
                                                        <input type="time" class="form-control p-4" value="17:00" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="shadow bg-white rounded p-3 mb-2" id="inspector-content-2">
                                                <a id="inspector-title-2" class="text text-decoration-none" style="cursor: pointer; font-weight: 700;">Inspector 2</a>
                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Inspector Name</label>
                                                    <input type="text" class="form-control p-4" value="Mock Inspector Name 2" readonly>
                                                </div>

                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Category</label>
                                                    <input type="text" class="form-control p-4" value="Mock Category 2" readonly>
                                                </div>

                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Date Signed</label>
                                                    <input type="date" class="form-control p-4" value="2024-01-01" readonly>
                                                </div>
                                                <div class="d-md-flex align-items-center justify-content-center p-0">
                                                    <div class="col col-md-6 p-0 form-group mb-1 flex-md-grow-1">
                                                        <label>Time In </label>
                                                        <input type="time" class="form-control p-4" value="09:00" readonly>
                                                    </div>
                                                    <div class="col col-md-6 p-0 form-group mb-1 flex-md-grow-1">
                                                        <label>Time Out </label>
                                                        <input type="time" class="form-control p-4" value="17:00" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="shadow bg-white rounded p-3 mb-2" id="inspector-content-3">
                                                <a id="inspector-title-3" class="text text-decoration-none" style="cursor: pointer; font-weight: 700;">Inspector 3</a>
                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Inspector Name</label>
                                                    <input type="text" class="form-control p-4" value="Mock Inspector Name 3" readonly>
                                                </div>

                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Category</label>
                                                    <input type="text" class="form-control p-4" value="Mock Category 3" readonly>
                                                </div>

                                                <div class="col col-12 p-0 form-group mb-1">
                                                    <label>Date Signed</label>
                                                    <input type="date" class="form-control p-4" value="2024-01-01" readonly>
                                                </div>
                                                <div class="d-md-flex align-items-center justify-content-center p-0">
                                                    <div class="col col-md-6 p-0 form-group mb-1 flex-md-grow-1">
                                                        <label>Time In </label>
                                                        <input type="time" class="form-control p-4" value="09:00" readonly>
                                                    </div>
                                                    <div class="col col-md-6 p-0 form-group mb-1 flex-md-grow-1">
                                                        <label>Time Out </label>
                                                        <input type="time" class="form-control p-4" value="17:00" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="carousel-item p-2" data-bs-interval="false">
                                        <p class="text font-weight-bolder">Other Certificate Information</p>

                                        <div class="col col-12 p-0 form-group">
                                            <label>BIN</label>
                                            <div class="input-group">
                                                <input type="text" name="bin" class="form-control p-4" value="Mock BIN" readonly>
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label>Occupancy No.</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control p-4" value="Mock Occupancy No." readonly>
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label for="date-compiled">Date Complied</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control p-4" value="2024-01-01" readonly>
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label for="issued-on">Issued On</label>
                                            <div class="input-group">
                                                <input type="datetime" class="form-control p-4" value="2024-01-01T12:00" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <div class="previous-container invisible">
                                        <button class="d-flex justify-content-center align-items-center border-0 bg-dark p-2 previous carousel-button" data-bs-target="#certificateCarousel" role="button" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                    <div class="next-container">
                                        <button class="d-flex justify-content-center align-items-center border-0 bg-dark p-2 next carousel-button" data-bs-target="#certificateCarousel" role="button" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outer Row -->
        <div class="row d-flex align-items-center justify-content-center overflow-hidden">
            <div class="col-xl-6 col-lg-8 col-md-11 col-sm-11 p-3">
                <div class="card card-body o-hidden shadow-lg p-4">
                    <!-- Nested Row within Card Body -->
                    <div class="d-flex flex-column justify-content-center col-lg-12">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">title/h1>
                        </div>
                        <form action="./generate/annual-certificate.php" method="POST" class="user" id="certificate-form" enctype="multipart/form-data">
                            <div class="d-flex flex-column align-items-center">
                                <div class="image-container mb-3">
                                    <img src="./../business/images/no-image.png" alt="default-item-image" class="img-fluid rounded-circle" id="bus-img" />
                                </div>
                            </div>

                            <div id="certificateCarousel" class="carousel slide">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#certificateCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#certificateCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#certificateCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active p-2" data-bs-interval="false">

                                        <p class="text font-weight-bolder">Business Information</p>

                                        <div class="form-group d-flex flex-column flex-md-grow-1">
                                            <label for="application-type">Application Type <span class="text-danger">*</span>
                                            </label>
                                            <div class="d-flex align-items-center justify-content-center select-container">
                                                <select name="application_type" id="application-type" class="form-control form-select px-3" required>
                                                    <option selected disabled hidden value="">Select</option>
                                                    <option value="Annual">Annual</option>
                                                    <option value="New">New</option>
                                                    <option value="Change Address">Change Address</option>
                                                    <option value="Change Name">Change Name</option>


                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group d-flex flex-column flex-md-grow-1">
                                            <label for="business-id">Business Name <span class="text-danger">*</span>
                                            </label>
                                            <div class="d-flex align-items-center justify-content-center select-container">
                                                <select name="business_id" id="certificate-business-id" class="form-control form-select px-3" required>
                                                    <option selected disabled hidden value="">Select</option>

                                                </select>
                                            </div>
                                        </div>

                                        <input type="hidden" name="bus_name" class="form-control p-4" id="bus-name" required readonly>

                                        <div class="col col-12 p-0 form-group d-none">
                                            <label for="owner-name">Owner Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="owner_name" class="form-control p-4" id="owner-name" required readonly>
                                            <input type="hidden" id="owner-id" name="owner_id">
                                        </div>

                                        <div class="col col-12 p-0 form-group d-none">
                                            <label for="bus-address">Business Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="bus_address" class="form-control p-4" id="bus-address" required readonly>
                                        </div>


                                        <div class="col col-12 p-0 form-group d-none">
                                            <label for="bus-group">Business Group <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="occupancy_group" class="form-control p-4" id="bus-group" required readonly>
                                        </div>

                                        <div class="col col-12 p-0 form-group d-none">
                                            <label for="character-of-occupancy">Character of Occupancy <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="character_of_occupancy" class="form-control p-4" id="character-of-occupancy" required readonly>
                                        </div>
                                    </div>

                                    <div class="carousel-item p-2" data-bs-interval="false">
                                        <div class="d-flex flex-column" id="inspector-certificate-container">
                                            <div class="d-flex justify-content-between">
                                                <p class="text font-weight-bolder">Inspector Information</p>
                                                <p class="text font-weight-bolder">Total
                                                    Inspector: <span id="total-inspector">0</span>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end my-4">
                                            <a class="btn btn-primary btn-md-block px-3" data-bs-target="#inspector-list" data-bs-toggle="modal">Add
                                                Inspector</a>
                                            <a class="btn btn-danger btn-md-block px-3 d-none" id="delete-inspector">Delete
                                                Inspector</a>
                                        </div>
                                    </div>

                                    <div class="carousel-item p-2" data-bs-interval="false">

                                        <p class="text font-weight-bolder">Other Certificate Information</p>

                                        <div class="col col-12 p-0 form-group">
                                            <label for="bin">BIN</label>

                                            <div class="input-group">
                                                <input type="text" name="bin" class="form-control p-4" id="bin" placeholder="Enter BIN...">
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label for="occupancy-no">Occupancy No. </label>

                                            <div class="input-group">
                                                <input type="text" name="occupancy_no" class="form-control p-4" id="occupancy-no" placeholder="Enter Occupancy No...">
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label for="date-compiled">Date Complied <span class="text-danger">*</span>
                                            </label>

                                            <div class="input-group">
                                                <input type="date" name="date_complied" class="form-control p-4" id="date-complied" max="<?php echo date('Y-m-d') ?>" placeholder="Enter Date Compiled..." required>
                                            </div>
                                        </div>

                                        <div class="col col-12 p-0 form-group">
                                            <label for="issued-on">Issued On</label>

                                            <div class="input-group">
                                                <input type="date" name="issued_on" class="form-control p-4" id="issued-on" max="<?php echo date('Y-m-d') ?>" placeholder="Enter Issued On...">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <div class="previous-container invisible">
                                        <button class="d-flex justify-content-center align-items-center border-0 bg-dark p-2 previous carousel-button" data-bs-target="#certificateCarousel" role="button" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                    <div class="next-container">
                                        <button class="d-flex justify-content-center align-items-center border-0 bg-dark p-2 next carousel-button" data-bs-target="#certificateCarousel" role="button" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4 d-none formSubmit">
                                <input type="submit" name="submit" class="btn btn-primary btn-user btn-block mt-3" value="Add">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <form >
            <div class="container-fluid d-flex justify-content-center py-5">
                <div class="annual-container">
                    <div class="annual-sheet">
                        <img src="./../../../assets/img/annual-certificate.jpg" alt="annual-certificate" class="annual-sheet-image">
                        <div class="annual-sheet-left">
                            <div class="d-flex justify-content-center annual-header annual-business-name mb-2 pb-1 annual-data">
                                owner + business name
                            </div>

                            <div class="d-flex justify-content-center annual-header annual-business-address pb-1 mb-1 annual-data">
                                business address
                            </div>

                            <div class="w-100 d-flex justify-content-between flex-gap annual-owner-wrapper">
                                <div class=" w-50 d-flex flex-column justify-content-center align-items-center ">
                                    <div class="w-100 d-flex justify-content-center annual-owner p-1 annual-data">
                                        character occupancy
                                    </div>
                                    <p class="w-100 m-0 text-center annual-owner-title">CHARACTER OF OCCUPANCY</p>
                                </div>


                                <div class="w-50 d-flex flex-column justify-content-center align-items-center">
                                    <div class="w-100 d-flex justify-content-center annual-group p-1 annual-data">
                                        group substr
                                    </div>
                                    <p class="w-100 m-0 text-center annual-group-title">Group</p>
                                </div>
                            </div>

                            <div class="annual-certification-description">
                                A CERTIFICATION DULY SIGNED AND SEALED FROM A DULY LICENSED ARCHITECT/CIVIL ENGINEER,
                                PROFESSIONAL ELECTRICAL ENGINEER/ ELECTRONICS ENGINEER/PROFESSIONAL MECHANICAL ENGINEER,
                                MASTER PLUMBER AND SANITARY ENGINEER HIRED BY THE OWNER WAS SUBMITTED AND WHO UNDERTOOK
                                THE
                                ANNUAL INSPECTION THAT THE BUILDING/STRUCTURE IS ARCHITECTURALLY PRESENTABLE,
                                STRUCTURALLY
                                SAFE, THE ELECTRICAL
                            </div>

                            <div class="d-flex flex-column align-items-center mb-2">
                                <div class="verified-title mb-1">
                                    VERIFIED AS TO THE FOLLOWING
                                </div>

                                <div class="verified-by-wrapper w-100 d-flex justify-content-center flex-wrap">
 
                                        <div class="verified-by-container">
                                            <div class="verified-by-names w-100 d-flex justify-content-center annual-data">
                                                inspector name
                                            </div>
                                            <p class="w-100 m-0 text-center verified-by-position">category</p>
                                        </div>
                                  
                                </div>
                            </div>

                            <div class="annual-recommended-description">
                                THE ABOVE-DESCRIBED BUILDING/STRUCTURE COVERED BY CERTIFICATE OF OCCUPANCY NO.
                                <u>
                                    occupancy no.</u>
                                ISSUED
                                ON <u>issued on</u> HAS BEEN VERIFIED AND
                                FOUND
                                SUBSTANTIALLY
                                SATISFACTORY COMPLIED, THEREFORE
                                THE
                                “CERTIFICATE OF ANNUAL INSPECTION” IS HEREBY RECOMMENDED FOR ISSUANCE.
                            </div>

                            <div class="annual-footer d-flex justify-content-center">
                                <div class="w-50 d-flex flex-column align-items-center">
                                    <div class="chief-name"><u>ENGR. GREGORY KARL D. SAN JUAN</u></div>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="chief-title">SECTION CHIEF</div>
                                        <div class="inspection-division-title">
                                            INSPECTION AND ENFORCEMENT DIVISION
                                        </div>
                                        <div class="signature-title">
                                            (SIGNATURE OVER PRINTED NAME)
                                        </div>
                                    </div>
                                    <div class="annual-date d-flex justify-content-center w-100">
                                        <p class="m-0">DATE</p> <span class="annual-date-underline">
                                        </span>
                                    </div>
                                </div>
                                <div class="w-50 d-flex flex-column align-items-center">
                                    <div class="chief-name"><u>ENGR. EMMANUEL T. AWAYAN</u></div>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="chief-title">DIVISION CHIEF</div>
                                        <div class="inspection-division-title">
                                            INSPECTION AND ENFORCEMENT DIVISION
                                        </div>
                                        <div class="signature-title">
                                            (SIGNATURE OVER PRINTED NAME)
                                        </div>
                                    </div>
                                    <div class="annual-date d-flex justify-content-center w-100">
                                        <p class="m-0">DATE</p> <span class="annual-date-underline">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="annual-sheet-right">
                            <div class="d-flex justify-content-end official-bin">BIN: </div>
                            <table class="table table-bordered mb-2 table-one">
                                <thead>
                                    <tr class="font-seven border-0">
                                        <th colspan="2" class="text-left">CERTIFICATE ANNUAL INSPECTION</th>
                                        <th colspan="2">DATE INSPECTED: </th>
                                    </tr>
                                    <tr>
                                        <th class="font-seven p-2 text-center">NAME OF LESSEE</th>
                                        <th colspan="3" class="lessee-name text-center">
                                            ownder name + business
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="font-seven p-3">LOCATION</th>
                                        <th colspan="3" class="location text-center align-middle"> business address
                                        </th>
                                    </tr>

                                    <tr class="font-seven inspector_head">
                                        <th class="align-middle" style="width: 15%">DATE SIGNED</th>
                                        <th class="align-middle" style="width: 55%">NAME OF INSPECTOR</th>
                                        <th style="width: 15%">TIME IN (SIGNED)</th>
                                        <th style="width: 15%">TIME OUT (SIGNED)</th>
                                    </tr>
                                </thead>
                                <tbody class="inspector_body">

                                            <tr>
                                                <td>date signed format</td>
                                                <td>inspector name</td>
                                                <td>time in</td>
                                                <td>time out</td>
                                            </tr>

                                            <input type="hidden" name="inspectors_id[]" value="">
                                            <input type="hidden" name="inspectors_name[]" value="">
                                            <input type="hidden" name="categories[]" value="">
                                            <input type="hidden" name="dates_signed[]" value=">
                                            <input type="hidden" name="time_ins[]" value="">
                                            <input type="hidden" name="time_outs[]" value="">

                                </tbody>
                            </table>

                            <div class="d-flex justify-content-between inspector">
                                <div>
                                    ANNUAL INSPECTION TEAM:
                                    <b>
                                 
                                    </b>

                                </div>
                                <div>
                                    DATE COMPLIED: <span><b>
                                           date complied
                                        </b></span>
                                </div>
                            </div>

                            <div class="checkbox-container w-100 d-flex justify-content-center pl-5">
                                <div class="w-75 d-flex flex-wrap">
                                    <div class="d-flex w-50 flex-gap">
                                        <div class="box d-flex justify-content-center align-items-center">
                                            application type
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                        </div>
                                        <div>NEW</div>
                                    </div>
                                    <div class="d-flex w-50 flex-gap mb-2">
                                        <div class="box d-flex justify-content-center align-items-center">
                                            application type
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                        </div>
                                        <div>ANNUAL</div>
                                    </div>
                                    <div class="d-flex w-50 flex-gap">
                                        <div class="box d-flex justify-content-center align-items-center">
                                            application type
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                        </div>
                                        <div>ADDITIONAL LINE</div>
                                    </div>
                                    <div class="d-flex w-50 flex-gap">
                                        <div class="box d-flex justify-content-center align-items-center">
                                            address
                                                <i class="fa fa-check" aria-hidden="true"></i>
                                        </div>
                                        <div>CHANGE ADDRESS</div>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="p-0 m-0">
                                        <td style="width: 15%"></td>
                                        <td class="text-center m-0" style="width: 55%">ENGR. GREGORY KARL D. SAN JUAN
                                        </td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center" style="width: 55%">INSPECTION AND ENFORCEMENT SECTION
                                            CHIEF</td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center no-data" style="width: 55%"></td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center" style="width: 55%">ENGR. EMMANUEL T. AWAYAN</td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center" style="width: 55%">INSPECTION AND ENFORCEMENT DIVISION
                                            CHIEF</td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center no-data" style="width: 55%"></td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center" style="width: 55%">ENGR. AUREA M. PASCUAL</td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center" style="width: 55%"> BUILDING OFFICIAL</td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                    <tr>
                                        <td style="width: 15%"></td>
                                        <td class="text-center no-data" style="width: 55%"></td>
                                        <td style="width: 15%"></td>
                                        <td style="width: 15%"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <section class="position-absolute left bottom-0 end-0 d-print-none">
                            <button class="btn btn-primary btn-md-block mr-3 px-3" id="print-button">Print
                                Report</a>
                        </section>
                    </div>

                </div>

            </div>

            <input type="hidden" name="bus_id" value="">
            <input type="hidden" name="owner_id" value="">
            <input type="hidden" name="bin" value="">
            <input type="hidden" name="application_type" value="">
            <input type="hidden" name="occupancy_no" value="">
            <input type="hidden" name="issued_on" value="">
            <input type="hidden" name="date_complied" value="">

        </form>

        <div class='msgalert alert--success' id='alert'>
            <div class='alert__message'>
                Annual Certificate Created Successfully
        </div>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>


    </div>
</div>

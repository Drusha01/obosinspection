<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <div class="p-0 m-0" wire:click="add('addModaltoggler')" >
                </div>
            </div>
            
            <form class="">
                <div class="container-fluid d-flex justify-content-center py-5">
                    <div class="annual-container">
                        <div class="annual-sheet">
                            <img src="./../../../assets/img/annual-certificate.jpg" alt="annual-certificate" class="annual-sheet-image">
                            <div class="annual-sheet-left">
                                <div class="d-flex justify-content-center annual-header annual-business-name mb-2 pb-1 annual-data">
                                    {{strtoupper($generate['annual_certificate_inspections']->first_name.' '.
                                        $generate['annual_certificate_inspections']->middle_name.' '.
                                        $generate['annual_certificate_inspections']->last_name.' '.
                                        $generate['annual_certificate_inspections']->suffix
                                        .' / '.$generate['annual_certificate_inspections']->name.' ')}}
                                    
                                </div>

                                <div class="d-flex justify-content-center annual-header annual-business-address pb-1 mb-1 annual-data">
                                    @if($generate['annual_certificate_inspections']->street_address) {{strtoupper($generate['annual_certificate_inspections']->street_address.', ')}}
                                    @endif {{strtoupper($generate['annual_certificate_inspections']->barangay)}}, GENERAL SANTOS CITY, SOUTH COTABATO
                                </div>

                                <div class="w-100 d-flex justify-content-between flex-gap annual-owner-wrapper">
                                    <div class=" w-50 d-flex flex-column justify-content-center align-items-center ">
                                        <div class="w-100 d-flex justify-content-center annual-owner p-1 annual-data">
                                            {{strtoupper($generate['annual_certificate_inspections']->occupancy_classification_name)}}
                                        </div>
                                        <p class="w-100 m-0 text-center annual-owner-title">CHARACTER OF OCCUPANCY</p>
                                    </div>


                                    <div class="w-50 d-flex flex-column justify-content-center align-items-center">
                                        <div class="w-100 d-flex justify-content-center annual-group p-1 annual-data">
                                            {{strtoupper($generate['annual_certificate_inspections']->character_of_occupancy_group)}}
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
                                        @foreach($generate['annual_certificate_inspection_inspectors'] as $key => $value)
                                            <div class="verified-by-container">
                                                <div class="verified-by-names w-100 d-flex justify-content-center annual-data">
                                                    {{strtoupper($value->first_name[0].' '.$value->last_name.' '.$value->suffix)}}
                                                </div>
                                                <p class="w-100 m-0 text-center verified-by-position">{{strtoupper($value->category_name)}}</p>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                <div class="annual-recommended-description">
                                    THE ABOVE-DESCRIBED BUILDING/STRUCTURE COVERED BY CERTIFICATE OF OCCUPANCY NO.
                                    <u>
                                        {{($generate['annual_certificate_inspections']->occupancy_no ? strtoupper($generate['annual_certificate_inspections']->occupancy_no) : "N/A")}}
                                    </u>
                                    ISSUED
                                    ON <u> {{($generate['annual_certificate_inspections']->occupancy_no ? strtoupper(date_format(date_create($generate['annual_certificate_inspections']->issued_on),"M d, Y")) : "N/A") }}</u> HAS BEEN VERIFIED AND
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
                                <div class="d-flex justify-content-end official-bin">BIN: @if($generate['annual_certificate_inspections']->bin){{strtoupper($generate['annual_certificate_inspections']->bin)}} @else N/A @endif</div>
                                <table class="table table-bordered mb-2 table-one">
                                    <thead>
                                        <tr class="font-seven border-0">
                                            <th colspan="2" class="text-left">CERTIFICATE ANNUAL INSPECTION</th>
                                            <th colspan="2">DATE INSPECTED: {{strtoupper(date_format(date_create($generate['annual_certificate_inspections']->date_updated),"M d, Y")) }}</th>
                                        </tr>
                                        <tr>
                                            <th class="font-seven p-2 text-center">NAME OF LESSEE</th>
                                            <th colspan="3" class="lessee-name text-center">
                                            {{strtoupper($generate['annual_certificate_inspections']->first_name.' '.
                                                $generate['annual_certificate_inspections']->middle_name.' '.
                                                $generate['annual_certificate_inspections']->last_name.' '.
                                                $generate['annual_certificate_inspections']->suffix
                                                .' / '.$generate['annual_certificate_inspections']->name)}}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="font-seven p-3">LOCATION</th>
                                            <th colspan="3" class="location text-center align-middle"> 
                                            @if($generate['annual_certificate_inspections']->street_address) {{strtoupper($generate['annual_certificate_inspections']->street_address.', ')}}@endif {{strtoupper($generate['annual_certificate_inspections']->barangay)}}, GENERAL SANTOS CITY, SOUTH COTABATO
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
                                            @foreach($generate['unique_annual_certificate_inspection_inspectors'] as $key => $value)
                                                <tr>
                                                    <td></td>
                                                    <td> {{strtoupper($value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix)}}</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                @endforeach

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
                                            @foreach($generate['unique_annual_certificate_inspection_inspectors'] as $key => $value)
                                                @if($loop->last)
                                                    {{strtoupper($value->last_name)}}
                                                @else 
                                                    {{strtoupper($value->last_name)}}, 
                                                @endif
                                            @endforeach
                                        </b>

                                    </div>
                                    <div>
                                        DATE COMPLIED: 
                                        <span>
                                            <b>
                                            {{date_format(date_create($generate['annual_certificate_inspections']->date_compiled),"M d, Y"); }}
                                            </b>
                                        </span>
                                    </div>
                                </div>

                                <div class="checkbox-container w-100 d-flex justify-content-center pl-5">
                                    <div class="w-75 d-flex flex-wrap">
                                        <div class="d-flex w-50 flex-gap">
                                            <div class="box d-flex justify-content-center align-items-center">
                                                @if($generate['annual_certificate_inspections']->application_type_name == 'New') 
                                                    <i class="fa fa-check" aria-hidden="true"></i> 
                                                @endif
                                            </div>
                                            <div>NEW</div>
                                        </div>
                                        <div class="d-flex w-50 flex-gap mb-2">
                                            <div class="box d-flex justify-content-center align-items-center">
                                                @if($generate['annual_certificate_inspections']->application_type_name == 'Annual') 
                                                    <i class="fa fa-check" aria-hidden="true"></i> 
                                                @endif
                                                    
                                            </div>
                                            <div>ANNUAL</div>
                                        </div>
                                        <div class="d-flex w-50 flex-gap">
                                            <div class="box d-flex justify-content-center align-items-center">
                                                @if($generate['annual_certificate_inspections']->application_type_name == 'Change name') 
                                                    <i class="fa fa-check" aria-hidden="true"></i> 
                                                @endif
                                            </div>
                                            <div>ADDITIONAL LINE</div>
                                        </div>
                                        <div class="d-flex w-50 flex-gap">
                                            <div class="box d-flex justify-content-center align-items-center">
                                                @if($generate['annual_certificate_inspections']->application_type_name == 'Change Addess') 
                                                    <i class="fa fa-check" aria-hidden="true"></i> 
                                                @endif
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
                            <div style="margin:10px;">
                                <section class="position-absolute bottom-0 end-0 d-print-none justify-content-center">
                                    <button class="btn btn-primary btn-md-block mr-3 px-3" id="print-button" onclick="window.print()">Print
                                    </button>
                                </section>
                            </div>
                          
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
            
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

        </div>
    </div>
</div>
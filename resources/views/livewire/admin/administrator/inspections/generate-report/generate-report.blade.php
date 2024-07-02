<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
            </div>
            <form >
                <section class="sheet-container">
                    <section class="sheet d-flex position-relative">
                        <img src="./../../../../../assets/img/header.jpg" alt="list-of-equipment" class="sheet-image">
                        <section class="section d-flex flex-column" style="margin-top:3.7cm">
                            <div class="section-header">
                                <div class="d-flex justify-content-center w-100 m-0 p-0 text-uppercase h-5">
                                    ANNUAL INSPECTION REPORT
                                </div>
                            </div>
                            <div class="row m-0 p-0 ">
                                <div class="col-3 ">
                                    <p class="m-0 p-0">
                                        Date of Inspection:
                                    </p>
                                    <p class="m-0 p-0">
                                        Owner of Building:
                                    </p>
                                    <!-- <p class="m-0 p-0">
                                        Name of Lessee:
                                    </p> -->
                                    <p class="m-0 p-0">
                                        Location of Building:
                                    </p>
                                    <p class="m-0 p-0">
                                         BIN No. :
                                    </p>
                                </div>
                                <div class="col-9 m-0 p-0">
                                    <p class="m-0 p-0 underline">
                                        {{date_format(date_create($issue_inspection['inspection']->schedule_date),"M d, Y")}}
                                    </p>
                                    <p class="m-0 p-0 underline">
                                        {{$issue_inspection['inspection_business_name']}}
                                    </p>
                                    <!-- <p class="m-0 p-0 underline">
                                        {{$issue_inspection['inspection']->first_name.' '.$issue_inspection['inspection']->middle_name.' '.$issue_inspection['inspection']->last_name.' '.$issue_inspection['inspection']->suffix}}
                                    </p> -->
                                    <p class="m-0 p-0 underline">
                                        @if(isset($issue_inspection['inspection']->street_address)) {{$issue_inspection['inspection']->street_address}}, @endif {{$issue_inspection['inspection']->barangay}} , GENERAL SANTOS CITY
                                    </p>
                                    <p class="m-0 p-0 underline">
                                        &nbsp;  
                                    </p>
                                </div>
                            </div>
                           
                            <hr class="bg-dark mx-0 mb-2" >
                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex justify-content-center w-100 m-0 p-0 text-uppercase h-5">
                                        INSPECTOR
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="d-flex justify-content-center w-100 m-0 p-0 text-uppercase h-5">
                                        REMARKS
                                    </div>
                                </div>
                            </div>
                            <?php 
                                $prev_category = null;
                                $current_category = null;
                               
                            ?>
                            @foreach($issue_inspection['categories'] as $key => $value )
                                <div class="row" wire:key="row-{{$key}}">
                                    <div class="col-3">
                                        <div class="d-flex justify-content-start w-100 m-0 p-0 ">
                                            {{$value->name}} :
                                        </div>
                                        @foreach($issue_inspection['inspection_inspector_team_leaders'] as $v_key => $v_value )
                                            @if($v_value->category_id == $value->id)
                                                <div class="d-flex justify-content-center w-100 mx-0 my-2 p-0 text-uppercase h-5 underline">
                                                    {{$v_value->first_name.' '.$v_value->middle_name.' '.$v_value->last_name.' '.$v_value->suffix}}
                                                </div>
                                            @endif
                                        @endforeach
                                        @foreach($issue_inspection['inspection_inspector_members'] as $v_key => $v_value )
                                            @if($v_value->category_id == $value->id)
                                                <div class="d-flex justify-content-center w-100 mx-0 my-2 p-0 text-uppercase h-5 underline">
                                                    {{$v_value->first_name.' '.$v_value->middle_name.' '.$v_value->last_name.' '.$v_value->suffix}}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-9">
                                        <div class="row m-0 mt-4 p-0" style="font-size:12px;">
                                            @foreach($issue_inspection['violations'] as $v_key => $v_value )
                                                @if($v_value->category_id == $value->id)
                                                <div class="col-6 d-flex text-break" >
                                                    <div style="padding:4px;margin-top:3px;margin-right:5px;5px;max-width:8px;max-height:12px;border:solid;border-width:thin">
                                                        </div>
                                                        @foreach($issue_inspection['inspection_violations'] as $iv_key => $iv_value )
                                                            @if($iv_value['violation_id'] == $v_value->id)
                                                                <i class="fa-solid fa-check" style="position:relative;left:-15px;top:-2px;font-size:16px;"></i>
                                                            @endif
                                                        @endforeach
                                                    {{$v_value->description}}
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr class="bg-dark m-0 p-0" >
                            @endforeach
                            <hr class="bg-dark m-0 p-0" >
                            <div class="row m-0 p-0 ">
                                <div class="col-3 ">
                                    <p>
                                        CONFORME :
                                    </p>
                                    <p class="m-0 p-0 ">
                                        _________________________
                                    </p>
                                    <p class="m-0 p-0 ">
                                        _________________________
                                    </p>
                                    <p class="m-0 p-0 " style="font-size:12px">
                                        Owner/Authorized Representative
                                    </p>
                                    <p class="m-0 p-0 " style="font-size:12px">
                                        (Printed name over signature)
                                    </p>
                                    <p class=" ">

                                    </p>
                                    <p class="m-0 p-0 ">
                                        Date: ____________________
                                    </p>
                                    <p class="m-0 p-0 ">
                                        Time: ____________________
                                    </p>
                                </div>
                                <div class="col-9 m-0 p-0" >
                                    <p class="m-0 mt-3 p-0 "style="font-size:16px;font-weight:bold">
                                        IMPORTANT:
                                    </p>
                                    <div class="row m-1 " style="border:solid;border-width:thin">
                                        <div class="row m-0 p-0">
                                            <div class="col-1 m-0" >
                                                <div class="row m-4 p-2 text-end align-middle" style="border:solid;border-width:thin;max-height:16px;max-width:16px;">
                                                    @if(count($issue_inspection['inspection_violations'])==0)
                                                        <i class="fa-solid fa-check" style="position:relative;left:-20px;top:-15px;font-size:25px;"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-11">
                                                <div class="row mx-3">
                                                    <p class="m-0 mt-2 p-0">
                                                        YOU MAY CLAIM YOUR CERTIFICATE OF ANNUAL INSPECTION AT THE
                                                        OFFICE OF THE BUILDING OFFICIAL ON @if(count($issue_inspection['inspection_violations'])==0) <strong class="underline">{{date_format(date_add(date_create($issue_inspection['inspection']->schedule_date),date_interval_create_from_date_string("3 days")),"M d, Y")}} </strong> @else ______________ @endif
                                                    </p>
                                                    <p class="m-0 p-0" style="font-size:12px;font-weight: bold;">
                                                        Bring photocopy of OBO O.R. of Business Permit & Annual Inspection Report.
                                                    </p>
    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row m-0 p-0">
                                            <div class="col-1 m-0" >
                                                <div class="row m-4 p-2 text-end align-middle" style="border:solid;border-width:thin;max-height:16px;max-width:16px;">
                                                    @if(count($issue_inspection['inspection_violations'])>0)
                                                        <i class="fa-solid fa-check" style="position:relative;left:-20px;top:-15px;font-size:25px;"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-11">
                                                <div class="row mx-3 d-flex">
                                                    <p class="m-0 mt-2 p-0 ">
                                                        THIS SERVES AS YOUR FINAL NOTICE OF VIOLATION. COMPLY ON OR 
                                                        BEFORE &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  <strong class="underline">{{date_format(date_add(date_create($issue_inspection['inspection']->schedule_date),date_interval_create_from_date_string("90 days")),"M d, Y")}} </strong> FOR THE ISSUANCE OF ANNUAL CERTIFICATE OF
                                                        ANNUAL INSPECTION AS A REQUIREMENT FOR THE RENEWAL OF YOUR BUSINESS PERMIT.
                                                    </p>
    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </section>
                        <section class="position-absolute left bottom-0 start-50 translate-middle d-print-none">
                            <button class="btn btn-primary btn-md-block mr-3 px-3" id="print-button" onclick="window.print()">Print
                            </button>
                        </section>
                    </section>
                </section>
            </form>

            <a class="scroll-to-top rounded d-print-none" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

        </div>
    </div>
</div>

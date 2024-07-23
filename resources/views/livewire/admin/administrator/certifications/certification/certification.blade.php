<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row d-flex mt-4 mb-4">
                <div class="col">
                    <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                </div>
                <div class="col-2">
                    <div class=" d-flex ">
                        <span for="rows" class="align-middle mt-2">Brgy</span>
                            <select name="" id=""  class="form-select" wire:model.live.debounce="search.brgy_id">
                                <option value="">Select Barangay</option>
                                @foreach($brgy as $key => $value)
                                    <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                @endforeach
                            </select>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class=" d-flex ">
                        <span for="rows" class="align-middle mt-2">Show</span>
                        <select name="" id="rows" class="form-select text-center"  style="min:width:40px;" wire:change="save_filter()" wire:model.defer="table_filter.table_rows" >
                            <option value="5">5</option>
                            <option selected value="10">10</option>
                            <option value="30">30</option>
                            <option value="30">50</option>
                        </select>
                        <button id="column-filter" class="mx-2 btn btn-outline-secondary d-flex"  data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="bi bi-funnel mr-2"></i>
                            <span for="column-filter">Columns</span>
                        </butto@>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-between my-3">
                <div class="col-8">
                    <div class="row d-flex">
                        <div class="col-lg-6 col-md-12">
                            <input type="text" name="" id=""class="form-control" wire:model.live.debounce.500ms="search.search" placeholder="Search ... " wire.change="">
                        </div>
                       <div class="col-lg-2 col-md-4 col-sm-4">
                            <select name="" id="rows" class="form-select" wire:model.live.debouce.500ms="search.type">
                                @foreach($search_by as $key => $value)
                                    <option @if($key == 0) selected @endif value="{{$value['column_name']}}" >{{$value['name']}}</option>
                                @endforeach
                            </select>
                       </div>
                    </div>
                </div>

                <div wire:ignore.self class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Column Filter</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form wire:submit.prevent="save_filter()">
                                <div class="modal-body">
                                    <div class="row">
                                    @foreach($table_filter['filter'] as $filter_key => $filter_value)
                                        <div class="form-check mx-4">
                                            <input class="form-check-input" type="checkbox" id="filter-{{$filter_key}}" wire:model.defer="table_filter.filter.{{$filter_key}}.active">
                                            <label class="form-check-label" for="filter-{{$filter_key}}">
                                                {{$filter_value['name']}}
                                            </label>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            @foreach($table_filter['filter'] as $filter_key => $filter_value)
                                @if($filter_value['active'])
                                    @if($filter_value['name'] == 'Action')
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Inspection Details')
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Claim' && $filter_value['active'])
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'iSClaimed?' && $filter_value['active'])
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @else 
                                        <th scope="col">{{$filter_value['name']}}</th>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($table_data as $key => $value)
                            <tr>
                                @foreach($table_filter['filter'] as $filter_key => $filter_value)
                                    @if($filter_value['name'] == '#' && $filter_value['active'])
                                        <th class="align-middle">{{($table_data->currentPage()-1)*$table_data->perPage()+$key+1 }}</th>
                                    @elseif ($filter_value['name'] == 'Image'  && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <a href="{{asset('storage/content/business/'.$value->{$filter_value['column_name']})}}" target="blank">
                                                <img class="img-fluid"src="{{asset('storage/content/business/'.$value->{$filter_value['column_name']})}}" alt="" style="max-height:50px;max-width:50px; ">
                                            </a>
                                        </td>
                                    @elseif ($filter_value['name'] == 'Owner'  && $filter_value['active'])
                                        <td class="align-middle">
                                            {{
                                                $value->first_name.' '.
                                                $value->middle_name.' '.
                                                $value->last_name.' '.
                                                $value->suffix
                                            }}
                                        </td>
                                       
                                    @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <a class="btn btn-outline-primary my-1" target="_blank" href="/administrator/certifications/generate/{{$value->id}}">
                                                Generate Certificate
                                            </a>
                                        </td>
                                    @elseif($filter_value['name'] == 'Inspection Details' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-primary" wire:click="issue({{$value->id}},'issueModaltoggler')">
                                                Inspection Details
                                            </button>
                                        </td>   
                                    @elseif($filter_value['name'] == 'Date Compiled' && $filter_value['active'])
                                        <td class="align-middle">
                                            {{date_format(date_create($value->date_compiled),"M d, Y")}}
                                        </td>
                                    @elseif($filter_value['name'] == 'Claim' && $filter_value['active'])
                                        <td class="align-middle text-center">
                                            @if(isset($value->or_number))
                                                <button class="btn btn-danger" wire:click="update_or_number_null({{$value->id}},'ORModaltoggler')">
                                                    Un-Claim
                                                </button>
                                            @else 
                                                <button class="btn btn-primary" wire:click="update_or_number({{$value->id}},'ORModaltoggler')">
                                                    Claimed
                                                </button>
                                            @endif
                                        </td>
                                    @elseif($filter_value['name'] == 'iSClaimed?' && $filter_value['active'])
                                        <td class="align-middle text-center">
                                            @if(isset($value->or_number))
                                                <span class="badge text-light p-2 bg-success">Yes</span>
                                            @else 
                                                <span class="badge text-light p-2 bg-danger">No</span>
                                            @endif
                                        </td>
                                    @else
                                        @if($filter_value['active'])
                                            <td class="align-middle">{{ $value->{$filter_value['column_name']} }}</td>
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <th colspan="42" class="text-center">NO DATA</th>
                            </tr>
                        @endforelse 
                    </tbody>
                </table>
            </div>
            
            <div class="container d-flex justify-content-center">
                {{$table_data->links()}}
            </div>

            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" id="editModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#deactivateModal" id="deactivateModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#activateModal" id="activateModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#issueModal" id="issueModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#completeModal" id="completeModaltoggler" style="display:none;"></button>
            

            <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Inspection Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($annual_certificate_inspection['step']/3)*100}}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @if($annual_certificate_inspection['step'] == 1)
                            <div >
                                <div class="mb-3">
                                    <label for="name" class="form-label">Business Name</label>
                                    <div class="mb-3">
                                        <select class="form-select" aria-label="Select Barangay" wire:change="update_business_information()" required wire:model="annual_certificate_inspection.business_id">
                                            <option value="">Select Business</option>
                                            @foreach($businesses as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name.' ('.$value->business_type_name.') brgy: '.$value->barangay}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label"> Application type</label>
                                    <div class="mb-3">
                                        <select class="form-select" aria-label="Select Application type" disabled required wire:model.live="annual_certificate_inspection.application_type_id">
                                            <option value="">Select Application type</option>
                                            @foreach($application_types as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if($annual_certificate_inspection['business_id'])
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Owner</label>
                                                    <input type="text" class="form-control" disabled value="{{$annual_certificate_inspection['business']->first_name.' '.$annual_certificate_inspection['business']->middle_name.' '.$annual_certificate_inspection['business']->last_name.' '.$annual_certificate_inspection['business']->suffix}}">
                                                </div>  
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Address</label>
                                                    <input type="text" class="form-control" disabled value="{{$annual_certificate_inspection['business']->barangay}}">
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Group</label>
                                                    <input type="text" class="form-control" disabled value="{{$annual_certificate_inspection['business']->character_of_occupancy_group}}">
                                                </div>  
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Character of Occupancy</label>
                                                    <input type="text" class="form-control" disabled value="{{$annual_certificate_inspection['business']->occupancy_classification_name}}">
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @elseif($annual_certificate_inspection['step'] == 2)
                            <div>
                                <div class="input-group mb-3">
                                    <select class="form-select" id="teamLeaderSelect"  wire:model="annual_certificate_inspection.inspector_id">
                                        <option value="">Select Team Member</option>
                                        @foreach($annual_certificate_inspection['inspectors'] as $key =>  $value)
                                            <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary" type="button" wire:click="add_annual_inspector()"><i class="bi bi-plus"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                        <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                            <tr>
                                                <th>Name</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($this->annual_certificate_inspection['annual_certificate_inspection_inspector']  as $key =>$value)
                                                <tr>
                                                    <td>{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-danger ">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @elseif($annual_certificate_inspection['step'] == 3)
                            <div>
                                <div class="input-group mb-3">
                                    <select class="form-select" id="teamLeaderSelect"  wire:model="annual_certificate_inspection.inspector_member_id">
                                        <option value="">Select Team Member</option>
                                        @foreach($inspector_members as $key =>  $value)
                                            <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary" type="button" wire:click="add_team_member()"><i class="bi bi-plus"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                        <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                            <tr>
                                                <th>Name</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($annual_certificate_inspection['inspector_members']  as $key =>$value)
                                                <tr>
                                                    <td>{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-danger ">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            @if($annual_certificate_inspection['step'] != 1)
                            <button type="button" id="prevButton" class="btn btn-secondary" wire:click="prev()" >Previous</button>
                            @endif
                            @if($annual_certificate_inspection['step'] < 3)
                                <button type="button" id="nextButton" class="btn btn-primary" wire:click="next('addModaltoggler')">Next</button>
                            @else
                                <button type="button" id="addButton" class="btn btn-success"  wire:click="next('addModaltoggler')">Add</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>



        <form class="d-none">
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
                            <button class="btn btn-primary btn-md-block mr-3 px-3" id="print-button" onclick="window.print()">Print
                            </button>
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
        
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>


    </div>
</div>

<div>
    <div class="content">
        <div class="container-fluid">

            <div class="row d-flex mt-4 mb-4">
                <div class="col-lg-4 col-md-4">
                    <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class=" d-flex ">
                        <span for="rows" class="align-middle mt-2">Status</span>
                            <select name="" id=""  class="form-select" wire:model.live.debounce="search.status_id">
                                @foreach($status as $key => $value)
                                    <option value="{{$value['id']}}">{{$value['name']}}</option>
                                @endforeach
                            </select>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class=" d-flex ">
                        <span for="rows" class="align-middle mt-2">category</span>
                            <select name="" id=""  class="form-select" wire:model.live.debounce="search.business_category_id">
                                <option value="">Select category</option>
                                @foreach($business_categories as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
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
                <div class="col-lg-5 col-xl-2 col-md-6 col-xs-6">
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
                        </button>
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
                <div class="col-4 d-flex justify-content-end ">
                  
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
                                    @elseif($filter_value['name'] == 'Violation' && $filter_value['active'])
                                        @if($search['status_id'] == -1)
                                            <th scope="col" class="text-center">Last Inspected Date</th>
                                            <th scope="col" class="text-center">Days Delayed</th>
                                        @else
                                            <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                        @endif
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
                                    @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                        <td class="text-end align-middle">
                                            @switch($value->status_name)
                                                @case("Pending")
                                                    <button class="btn btn-outline-secondary" wire:click="update_status({{$value->id}},'On-going')">
                                                        On-going
                                                    </button>
                                                    <button class="btn btn-primary" wire:click="issue({{$value->id}},'issueModaltoggler')">
                                                        Inspection Details
                                                    </button>
                                                    @if($value->is_active)
                                                        <button class="btn btn-danger" wire:click="edit({{$value->id}},'deactivateModaltoggler')">
                                                            Delete
                                                        </button>
                                                    @endif
                                                    @break
                                                @case("On-going")
                                                    <button class="btn btn-outline-secondary" wire:click="update_status({{$value->id}},'Pending')">
                                                        Pending
                                                    </button>
                                                    <button class="btn btn-secondary"  wire:click="edit({{$value->id}},'completeModaltoggler')">
                                                        Complete
                                                    </button>
                                                    <button class="btn btn-primary" wire:click="issue({{$value->id}},'issueModaltoggler')">
                                                        Inspection Details
                                                    </button>
                                                    @if($value->is_active)
                                                        <button class="btn btn-danger" wire:click="edit({{$value->id}},'deactivateModaltoggler')">
                                                            Delete
                                                        </button>
                                                    @endif
                                                    @break
                                                @case("Deleted")
                                                    <button class="btn btn-primary" wire:click="issue({{$value->id}},'issueDeletedModaltoggler')">
                                                        Inspection Details
                                                    </button>
                                                    @break
                                                @case("Completed")
                                                    <a class="btn btn-outline-primary my-1" target="_blank" href="/administrator/inspections/generate/{{$value->id}}">
                                                        LOE
                                                    </a>
                                                    <a class="btn btn-outline-primary my-1" target="_blank" href="/administrator/inspections/generate-report/{{$value->id}}">
                                                        Report
                                                    </a>
                                                    <?php 
                                                        $violations = DB::table('inspection_violations as iv')
                                                        ->select(
                                                            'iv.id',
                                                            'description',
                                                            'remarks'
                                                        )
                                                        ->join('violations as v','v.id','iv.violation_id')
                                                        ->where('inspection_id','=',$value->id)
                                                        ->get()
                                                        ->toArray();
                                                    if(count($violations)<=0){
                                                        echo '
                                                            <button class="btn btn-outline-primary" wire:click="generate_cert('.$value->id.',\'certModaltoggler\')" >
                                                                Certificate
                                                            </button>';
                                                    }elseif(count($violations)>0){
                                                        $valid = true;
                                                        foreach ($violations as $key => $value_violation) {
                                                            if(!isset($value_violation->remarks)){
                                                                $valid = false;
                                                            }
                                                        }
                                                        if($valid){
                                                            echo '
                                                             <button class="btn btn-outline-primary" wire:click="generate_cert('.$value->id.',\'certModaltoggler\')" >
                                                                Certificate
                                                            </button>';
                                                        }else{
                                                            echo '
                                                             <button class="btn btn-outline-primary" disabled wire:click="generate_cert('.$value->id.',\'certModaltoggler\')" >
                                                                Certificate
                                                            </button>';
                                                        }
                                                    }
                                                    ?>
                                                    
                                                    <button class="btn btn-primary" wire:click="issue({{$value->id}},'issueCompleteModaltoggler')">
                                                        Inspection Details
                                                    </button>
                                                    @break
                                                @case('Upcoming')
                                                    <button type="button" class="btn btn-primary" wire:click="add('addModaltoggler',{{$value->business_id}})">
                                                        Add Inspection
                                                    </button>
                                                    @break
                                            @endswitch
                                        </td>
                                    @elseif($filter_value['name'] == 'Violation' && $filter_value['active'])
                                        @if($search['status_id'] == -1)
                                            <td class="text-center align-middle">
                                                {{date_format(date_create($value->schedule_date),"M d, Y")}}
                                            </td>   
                                            <td class="align-middle">
                                                @if($value->date_count_minus_year > 0)
                                                    {{$value->date_count_minus_year}} days
                                                @endif
                                            </td>
                                        @else 
                                            <td class="text-center align-middle">
                                                @if($value->status_name == 'Completed' || $value->status_name == 'Deleted' )
                                                    <?php 
                                                        $violations = DB::table('inspection_violations as iv')
                                                            ->select(
                                                                'iv.id',
                                                                'description',
                                                                'remarks'
                                                            )
                                                            ->join('violations as v','v.id','iv.violation_id')
                                                            ->where('inspection_id','=',$value->id)
                                                            ->get()
                                                            ->toArray();
                                                        if(count($violations)<=0){
                                                            echo '<span class="badge text-light p-2 bg-primary">No Violation</span>';
                                                        }elseif(count($violations)>0){
                                                            $valid = true;
                                                            foreach ($violations as $key => $value_violation) {
                                                                if(!isset($value_violation->remarks)){
                                                                    $valid = false;
                                                                }
                                                            }
                                                            if($valid){
                                                                echo '<span class="badge text-light p-2 bg-primary">Complied</span>';
                                                            }else{
                                                                echo '<span class="badge text-light p-2 bg-warning">Un-complied</span>';
                                                            }
                                                        }
                                                    ?>
                                                @else
                                                    @if($value->{$filter_value['column_name']} == 'With Violation/s')
                                                        <span class="badge text-light p-2 bg-warning">With Violation</span>
                                                    @else
                                                        <span class="badge text-light p-2 bg-primary">No Violation</span>
                                                    @endif
                                                @endif
                                            
                                            </td>
                                        @endif
                                    @elseif($filter_value['name'] == 'Inspection Details' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-primary" wire:click="issue({{$value->id}},'issueModaltoggler')">
                                                Inspection Details
                                            </button>
                                        </td>   
                                    @elseif($filter_value['name'] == 'Schedule' && $filter_value['active'])
                                        <td class="align-middle">
                                            {{date_format(date_create($value->schedule_date),"M d, Y")}}
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
            <button type="button" data-bs-toggle="modal" data-bs-target="#issueCompleteModal" id="issueCompleteModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#issueDeletedModal" id="issueDeletedModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#certModal" id="certModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#ProofModal" id="ProofModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#ValidatedProofModal" id="ValidatedProofModaltoggler" style="display:none;"></button>
            
            
            <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Inspection Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($inspection['step']/3)*100}}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @if(isset($inspection['last_inspection']))
                                <div class="row my-2 text-primary">
                                    <p>Last inspected date: {{date_format(date_create($inspection['last_inspection']->schedule_date),"M d, Y")}}</p>
                                </div>
                            @endif
                            @if($inspection['step'] == 1)
                            <div >
                                <h5 class="text-center my-2 text-black">
                                    Inspection Details
                                </h5>
                                @if(isset($inspection['business']))
                                    <div class="row">
                                        <div class="col-12 text-dark">
                                            <p>Business name: {{$inspection['business']->name.' ('.$inspection['business']->business_type_name.' )' }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="inspection_date" class="form-label">Inspection Date  <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="inspection_date" wire:model.live="inspection.schedule_date" required min="{{$inspection['schedule_date']}}">
                                </div>
                            </div>
                            @elseif($inspection['step'] == 2)
                            <div>
                                <h5 class="text-center my-2 text-black">
                                    Team Leaders  <span class="text-danger">*</span> 
                                </h5>
                                <div class="input-group mb-3">
                                    <select class="form-select" id="teamLeaderSelect" wire:model.live="inspection.inspector_leader_id">
                                        <option value="">Select Team Leader</option>
                                        @foreach($inspector_leaders as $key =>  $value)
                                            <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary" type="button" wire:click="add_team_leader()"><i class="bi bi-plus"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                        <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                            <tr>
                                                <th class="align-middle">Name</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection['inspector_leaders']  as $key =>$value)
                                                <tr>
                                                    <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-danger "  wire:click="delete_team_leader({{$key}})">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @elseif($inspection['step'] == 3)
                            <div>
                                <h5 class="text-center my-2 text-black">
                                    Team Members
                                </h5>
                            
                                <div class="input-group mb-3">
                                    <select class="form-select" id="teamLeaderSelect" wire:model.live="inspection.inspector_member_id">
                                        <option value="">Select Team Member</option>
                                        @foreach($inspector_members as $key =>  $value)
                                            <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary" type="button" wire:click="add_team_member()"><i class="bi bi-plus"></i></button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                        <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                            <tr>
                                                <th class="align-middle">Name</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection['inspector_members']  as $key =>$value)
                                                <tr>
                                                    <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-danger "  wire:click="delete_team_member({{$key}})">
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
                            @if($inspection['step'] != 1)
                            <button type="button" id="prevButton" class="btn btn-secondary" wire:click="prev()" >Previous</button>
                            @endif
                            @if($inspection['step'] < 3)
                                <button type="button" id="nextButton" class="btn btn-primary" wire:click="next('addModaltoggler')">Next</button>
                            @else
                                <button type="button" id="addButton" class="btn btn-success"  wire:click="next('addModaltoggler')">Add</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="issueModalLabel">Ongoing Inspection</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($issue_inspection['step']/8)*100}}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <p>ID: {{$issue_inspection['id']}}</p><br>
                                <p>Business name : {{$issue_inspection['inspection_business_name']}}</p>
                            </div>
                            @if($issue_inspection['step'] == 1)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Inspection Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Application type</label>
                                        <div class="mb-3">
                                            <select class="form-select" aria-label="Select Barangay" wire:change="update_application_type()" required wire:model="issue_inspection.application_type_id">
                                                <option value="">Select Application type</option>
                                                @foreach($issue_inspection['application_types'] as $key => $value)
                                                    @if( $value->id == $issue_inspection['application_type_id'])
                                                        <option selected value="{{$value->id}}">{{$value->name}}</option>
                                                    @else
                                                        <option value="{{$value->id}}">{{$value->name}}</option> 
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Business name</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.inspection_business_name" value="{{$issue_inspection['inspection_business_name']}}" >
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 2)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Team Leader Details
                                    </h5>
                                    <div class="input-group mb-3">
                                        <select class="form-select" id="teamLeaderSelect" wire:model="issue_inspection.inspector_leader_id">
                                            <option value="">Select Team Leader</option>
                                            @foreach($issue_inspection['inspection_inspector_team_leaders'] as $key =>  $value)
                                                <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary" type="button" wire:click="update_inspection_team_leader()"><i class="bi bi-plus"></i></button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                    <th class="align-middle text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspector_team_leaders']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                        <td class="align-middle text-center">
                                                            <button class="btn btn-danger " wire:click="update_delete_team_leaders({{$value->id}})">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 3)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Team Member Details
                                    </h5>
                                    <div class="input-group mb-3">
                                        <select class="form-select" id="teamLeaderSelect" wire:model="issue_inspection.inspector_member_id">
                                            <option value="">Select Team Member</option>
                                            @foreach($issue_inspection['inspector_members'] as $key =>  $value)
                                                <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary" type="button" wire:click="update_inspection_members()"><i class="bi bi-plus"></i></button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                    <th class="align-middle text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_inspector_members']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                        <td class="align-middle text-center">
                                                            <button class="btn btn-danger " wire:click="update_delete_members({{$value->id}})"> 
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 4)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Item Details
                                    </h5>
                                    <div class="input-group mb-3">
                                        @if(count($issue_inspection['items']) >0)
                                            <select class="form-select" id="teamLeaderSelect" wire:model="issue_inspection.item_id">
                                                <option value="">Select Item</option>
                                                @foreach($issue_inspection['items'] as $key =>  $value)
                                                    <option selected value="{{$value->id}}">{{$value->name.' ( '.$value->category_name.' )'.'( '.$value->section_name.' )'}}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary" type="button" wire:click="update_inspection_items()" ><i class="bi bi-plus"></i></button>
                                        @endif
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Item name</th>
                                                    <th class="align-middle">Category</th>
                                                    <th class="align-middle">Section</th>
                                                    <th class="align-middle" colspan="3" >Capacity</th>
                                                    <th class="align-middle" colspan="1">Quantity</th>
                                                    <th class="align-middle"> Power Rating</th>
                                                    <th class="align-middle">Fee</th>
                                                    <th class="align-middle text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_items']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['name']}}</td>
                                                        <td class="align-middle">{{$value['category_name']}}</td>
                                                        <td class="align-middle">{{$value['section_name']}}</td>
                                                        <td class="align-middle" colspan="3">

                                                            <?php 
                                                                $equipments_billing = DB::table('equipment_billings as eb')
                                                                    ->select(
                                                                        'eb.id',
                                                                        'eb.capacity'
                                                                        )
                                                                    ->join('equipment_billing_sections as ebs','ebs.id','eb.section_id')
                                                                    // ->orderBy('eb.id','desc')
                                                                    ->where('ebs.category_id','=',$value['category_id'])
                                                                    ->where('ebs.id','=',$value['section_id'])
                                                                    ->get()
                                                                    ->toArray();
                                                            ?>
                                                                <select class="form-select" id="teamLeaderSelect" wire:change="update_equipment_billing({{$value['id']}},{{$key}})" wire:model="issue_inspection.inspection_items.{{$key}}.equipment_billing_id">
                                                                    <option value="">Select Capacity</option>
                                                                    @foreach($equipments_billing as $eb_key => $eb_value)
                                                                        <option value="{{$eb_value->id}}">{{$eb_value->capacity}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </td>
                                                        <td class="align-middle"  colspan="1">
                                                            <input type="number" class="form-control" wire:change="update_item_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_items.{{$key}}.quantity">
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="number" step="0.01" class="form-control" wire:change="update_item_power_rating({{$value['id']}},{{$key}})" min="0.01" wire:model="issue_inspection.inspection_items.{{$key}}.power_rating">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['quantity']}}</td>
                                                        <td class="align-middle text-center">
                                                            <button class="btn btn-danger " wire:click="update_delete_item({{$value['id']}})">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 5)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Building Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Floor area</label>
                                        <input type="number" class="form-control" wire:model="issue_inspection.floor_area" wire:change="update_floor_area()">
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Building Information</label>
                                        <div class="mb-3">
                                            <select class="form-select" aria-label="Select Select Building Billing" wire:change="update_building_billing()" required wire:model="issue_inspection.building_billing_id">
                                                <option value="">Select Building billing</option>
                                                @foreach($issue_inspection['building_billings'] as $key => $value)
                                                    @if( $value['id'] == $issue_inspection['building_billing_id'])
                                                        <option selected value="{{$value['id']}}">{{$value['section_name'].' ( '.$value['property_attribute'].' )'}}</option>
                                                    @else
                                                        <option selected value="{{$value['id']}}">{{$value['section_name'].' ( '.$value['property_attribute'].' )'}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Fee</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.building_billing_fee"  >
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 6)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Sanitary Details
                                    </h5>
                                    <div class="input-group mb-3">
                                        <select class="form-select" id="teamLeaderSelect" wire:model="issue_inspection.sanitary_billing_id">
                                            <option value="">Select Sanitary Item</option>
                                            @foreach($issue_inspection['sanitary_billings'] as $key =>  $value)
                                                <option selected value="{{$value['id']}}">{{$value['name']}}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary" type="button" wire:click="update_inspection_sanitary_billings()" ><i class="bi bi-plus"></i></button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Fee</th>
                                                    <th class="align-middle text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_sanitary_billings']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['sanitary_name']}}</td>
                                                        <td class="align-middle">
                                                            <input type="number" class="form-control" wire:change="update_sanitary_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_sanitary_billings.{{$key}}.sanitary_quantity">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['sanitary_quantity']}}</td>
                                                        <td class="align-middle text-center">
                                                            <button class="btn btn-danger " wire:click="update_delete_sanitary({{$value['id']}})">
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 7)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Signage Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Signage area</label>
                                        <input type="number" class="form-control" wire:model="issue_inspection.signage_area" wire:change="update_signage_area()">
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Signage Information</label>
                                        <select class="form-select" aria-label="Select Select Signage Billing" wire:change="update_signage_billing()" required wire:model="issue_inspection.signage_id">
                                            <option value="">Select Signage billing</option>
                                            @foreach($issue_inspection['signage_billings'] as $key => $value)
                                                @if( $value['id'] == $issue_inspection['building_billing_id'])
                                                    <option selected value="{{$value['id']}}">{{$value['display_type_name'].' ( '.$value['sign_type_name'].' )'}}</option>
                                                @else
                                                    <option selected value="{{$value['id']}}">{{$value['display_type_name'].' ( '.$value['sign_type_name'].' )'}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Fee</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.signage_billing_fee">
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 8)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Violation Details
                                    </h5>
                                    @if(count($issue_inspection['violations'])>0)
                                        <div class="input-group mb-3">
                                            <select class="form-select" id="teamLeaderSelect" wire:model="issue_inspection.violation_id">
                                                <option value="">Select Violation</option>
                                                @foreach($issue_inspection['violations'] as $key =>  $value)
                                                    <option selected value="{{$value->id}}">{{$value->description.' ( '.$value->category_name. ' ) '}}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary" type="button" wire:click="update_inspection_violation()" ><i class="bi bi-plus"></i></button>
                                        </div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-center">Has Proof</th>
                                                    <th>
                                                        Proof
                                                    </th>
                                                    <th class="align-middle text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_violations']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['description'].' ( '.$value['category_name']. ' ) '}}</td>
                                                        <td class="align-middle text-center">
                                                            <?php
                                                                if(DB::table('inspection_violation_contents')
                                                                    ->where('inspection_violation_id','=',$value['id'])
                                                                    ->first()
                                                                ){
                                                                    echo '<span class="badge text-light p-2 bg-primary">W/ Proof</span>';
                                                                }else{
                                                                    echo '<span class="badge text-light p-2 bg-warning">W/out Proof</span>';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary "wire:click="view_violation_proof({{$value['id']}},'ProofModaltoggler')"> 
                                                                View
                                                            </button>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <button class="btn btn-danger "wire:click="update_delete_violation({{$value['id']}})"> 
                                                                Delete
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <hr>
                            <div class="row ">
                                <div class="col-lg-12 d-flex justify-content-center">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-12">
                                            @foreach($issue_inspection['steps'] as $key =>$value)
                                                    @if(($issue_inspection['step']-1) == $key)
                                                        <button type="button" id="prevButton" class="btn btn-secondary m-1" wire:click="go_issue({{$key+1}})" >{{$value['name']}}</button>                                      
                                                    @else
                                                        <button type="button" id="prevButton" class="btn btn-outline-secondary m-1" wire:click="go_issue({{$key+1}})" >{{$value['name']}}</button>                                      
                                                    @endif
                                            @endforeach                                      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="modal-footer">
                                @if($issue_inspection['step'] != 1)
                                    <button type="button" id="prevButton" class="btn btn-secondary" wire:click="prev_issue()" >Previous</button>
                                @else
                                    <button type="button" disabled id="prevButton" class="btn btn-secondary" wire:click="prev_issue()" >Previous</button>
                                @endif
                                @if($issue_inspection['step'] != count($issue_inspection['steps']))
                                    <button type="button" id="nextButton" class="btn btn-primary" wire:click="next_issue()">Next</button>
                                @else
                                    <button type="button" disabled id="nextButton" class="btn btn-primary opacity-0" wire:click="next_issue()">Next</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="issueCompleteModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="issueModalLabel">Completed Inspection </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($issue_inspection['step']/8)*100}}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <p>ID: {{$issue_inspection['id']}}</p><br>
                                <p>Business name : {{$issue_inspection['inspection_business_name']}}</p>
                            </div>
                            @if($issue_inspection['step'] == 1)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Inspection Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Application type</label>
                                        <div class="mb-3">
                                            <select class="form-select" aria-label="Select Barangay" disabled wire:change="update_application_type()" required wire:model="issue_inspection.application_type_id">
                                                <option value="">Select Application type</option>
                                                @foreach($issue_inspection['application_types'] as $key => $value)
                                                    @if( $value->id == $issue_inspection['application_type_id'])
                                                        <option selected value="{{$value->id}}">{{$value->name}}</option>
                                                    @else
                                                        <option value="{{$value->id}}">{{$value->name}}</option> 
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Business name</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.inspection_business_name" value="{{$issue_inspection['inspection_business_name']}}" >
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 2)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Team Leader Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspector_team_leaders']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 3)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Team Member Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_inspector_members']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 4)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Item Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Item name</th>
                                                    <th class="align-middle">Category</th>
                                                    <th class="align-middle">Section</th>
                                                    <th class="align-middle" colspan="3" >Capacity</th>
                                                    <th class="align-middle" colspan="1">Quantity</th>
                                                    <th class="align-middle"> Power Rating</th>
                                                    <th class="align-middle">Fee</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_items']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['name']}}</td>
                                                        <td class="align-middle">{{$value['category_name']}}</td>
                                                        <td class="align-middle">{{$value['section_name']}}</td>
                                                        <td class="align-middle" colspan="3">

                                                            <?php 
                                                                $equipments_billing = DB::table('equipment_billings as eb')
                                                                    ->select(
                                                                        'eb.id',
                                                                        'eb.capacity'
                                                                        )
                                                                    ->join('equipment_billing_sections as ebs','ebs.id','eb.section_id')
                                                                    // ->orderBy('eb.id','desc')
                                                                    ->where('ebs.category_id','=',$value['category_id'])
                                                                    ->where('ebs.id','=',$value['section_id'])
                                                                    ->get()
                                                                    ->toArray();
                                                            ?>
                                                                <select class="form-select" id="teamLeaderSelect" disabled wire:change="update_equipment_billing({{$value['id']}},{{$key}})" wire:model="issue_inspection.inspection_items.{{$key}}.equipment_billing_id">
                                                                    <option value="">Select Capacity</option>
                                                                    @foreach($equipments_billing as $eb_key => $eb_value)
                                                                        <option value="{{$eb_value->id}}">{{$eb_value->capacity}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </td>
                                                        <td class="align-middle"  colspan="1">
                                                            <input type="number" class="form-control"  disabled wire:change="update_item_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_items.{{$key}}.quantity">
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="number" step="0.01" class="form-control" disabled wire:change="update_item_power_rating({{$value['id']}},{{$key}})" min="0.01" wire:model="issue_inspection.inspection_items.{{$key}}.power_rating">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['quantity']}}</td>
                                                        
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 5)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Building Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Floor area</label>
                                        <input type="number" class="form-control" disabled wire:model="issue_inspection.floor_area" wire:change="update_floor_area()">
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Building Information</label>
                                        <div class="mb-3">
                                            <select class="form-select" aria-label="Select Select Building Billing" disabled wire:change="update_building_billing()" required wire:model="issue_inspection.building_billing_id">
                                                <option value="">Select Building billing</option>
                                                @foreach($issue_inspection['building_billings'] as $key => $value)
                                                    @if( $value['id'] == $issue_inspection['building_billing_id'])
                                                        <option selected value="{{$value['id']}}">{{$value['section_name'].' ( '.$value['property_attribute'].' )'}}</option>
                                                    @else
                                                        <option selected value="{{$value['id']}}">{{$value['section_name'].' ( '.$value['property_attribute'].' )'}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Fee</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.building_billing_fee"  >
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 6)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Sanitary Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Fee</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_sanitary_billings']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['sanitary_name']}}</td>
                                                        <td class="align-middle">
                                                            <input type="number" disabled class="form-control" wire:change="update_sanitary_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_sanitary_billings.{{$key}}.sanitary_quantity">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['sanitary_quantity']}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 7)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Signage Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Signage area</label>
                                        <input type="number" class="form-control" disabled wire:model="issue_inspection.signage_area" wire:change="update_signage_area()">
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Signage Information</label>
                                        <select class="form-select" disabled aria-label="Select Select Signage Billing" wire:change="update_signage_billing()" required wire:model="issue_inspection.signage_id">
                                            <option value="">Select Signage billing</option>
                                            @foreach($issue_inspection['signage_billings'] as $key => $value)
                                                @if( $value['id'] == $issue_inspection['building_billing_id'])
                                                    <option selected value="{{$value['id']}}">{{$value['display_type_name'].' ( '.$value['sign_type_name'].' )'}}</option>
                                                @else
                                                    <option selected value="{{$value['id']}}">{{$value['display_type_name'].' ( '.$value['sign_type_name'].' )'}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" disabled class="form-label">Fee</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.signage_billing_fee">
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 8)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Violation Details
                                    </h5>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-center">Has Proof</th>
                                                    <th class="text-center">Proof</th >
                                                    <th class="text-center">Validated Proof</th >
                                                    <th class="text-center">  isValidated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_violations']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['description'].' ( '.$value['category_name']. ' ) '}}</td>
                                                        <td class="align-middle text-center">
                                                            <?php
                                                                if(DB::table('inspection_violation_contents')
                                                                    ->where('inspection_violation_id','=',$value['id'])
                                                                    ->first()
                                                                ){
                                                                    echo '<span class="badge text-light p-2 bg-primary">W/ Proof</span>';
                                                                }else{
                                                                    echo '<span class="badge text-light p-2 bg-warning">W/out Proof</span>';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-primary "wire:click="view_violation_proof({{$value['id']}},'ProofModaltoggler')"> 
                                                                View
                                                            </button>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-primary "wire:click="view_violation_validated_proof({{$value['id']}},'ValidatedProofModaltoggler')"> 
                                                                View
                                                            </button>
                                                        </td>
                                                        
                                                        <td class="text-center align-middle">
                                                            <input type="checkbox"  value="1" @if($value['remarks'])) checked @endif  wire:change="update_complied_violation({{$value['id']}})">
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <hr>
                             <div class="row ">
                                <div class="col-lg-12 d-flex justify-content-center">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-12">
                                            @foreach($issue_inspection['steps'] as $key =>$value)
                                                    @if(($issue_inspection['step']-1) == $key)
                                                        <button type="button" id="prevButton" class="btn btn-secondary m-1" wire:click="go_issue({{$key+1}})" >{{$value['name']}}</button>                                      
                                                    @else
                                                        <button type="button" id="prevButton" class="btn btn-outline-secondary m-1" wire:click="go_issue({{$key+1}})" >{{$value['name']}}</button>                                      
                                                    @endif
                                            @endforeach                                      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="modal-footer">
                                @if($issue_inspection['step'] != 1)
                                    <button type="button" id="prevButton" class="btn btn-secondary" wire:click="prev_issue()" >Previous</button>
                                @else
                                    <button type="button" disabled id="prevButton" class="btn btn-secondary" wire:click="prev_issue()" >Previous</button>
                                @endif
                                @if($issue_inspection['step'] != count($issue_inspection['steps']))
                                    <button type="button" id="nextButton" class="btn btn-primary" wire:click="next_issue()">Next</button>
                                @else
                                    <button type="button" disabled id="nextButton" class="btn btn-primary opacity-0" wire:click="next_issue()">Next</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="issueDeletedModal" tabindex="-1" aria-labelledby="issueDeletedModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="issueDeletedModalLabel">Deleted Inspection</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($issue_inspection['step']/8)*100}}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="row">
                                <p>ID: {{$issue_inspection['id']}}</p><br>
                                <p>Business name : {{$issue_inspection['inspection_business_name']}}</p>
                            </div>
                            @if($issue_inspection['step'] == 1)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Inspection Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Application type</label>
                                        <div class="mb-3">
                                            <select class="form-select" aria-label="Select Barangay" disabled wire:change="update_application_type()" required wire:model="issue_inspection.application_type_id">
                                                <option value="">Select Application type</option>
                                                @foreach($issue_inspection['application_types'] as $key => $value)
                                                    @if( $value->id == $issue_inspection['application_type_id'])
                                                        <option selected value="{{$value->id}}">{{$value->name}}</option>
                                                    @else
                                                        <option value="{{$value->id}}">{{$value->name}}</option> 
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Business name</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.inspection_business_name" value="{{$issue_inspection['inspection_business_name']}}" >
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 2)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Team Leader Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspector_team_leaders']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 3)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Team Member Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_inspector_members']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 4)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Item Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Item name</th>
                                                    <th class="align-middle">Category</th>
                                                    <th class="align-middle">Section</th>
                                                    <th class="align-middle" colspan="3" >Capacity</th>
                                                    <th class="align-middle" colspan="1">Quantity</th>
                                                    <th class="align-middle"> Power Rating</th>
                                                    <th class="align-middle">Fee</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_items']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['name']}}</td>
                                                        <td class="align-middle">{{$value['category_name']}}</td>
                                                        <td class="align-middle">{{$value['section_name']}}</td>
                                                        <td class="align-middle" colspan="3">

                                                            <?php 
                                                                $equipments_billing = DB::table('equipment_billings as eb')
                                                                    ->select(
                                                                        'eb.id',
                                                                        'eb.capacity'
                                                                        )
                                                                    ->join('equipment_billing_sections as ebs','ebs.id','eb.section_id')
                                                                    // ->orderBy('eb.id','desc')
                                                                    ->where('ebs.category_id','=',$value['category_id'])
                                                                    ->where('ebs.id','=',$value['section_id'])
                                                                    ->get()
                                                                    ->toArray();
                                                            ?>
                                                                <select class="form-select" id="teamLeaderSelect" wire:change="update_equipment_billing({{$value['id']}},{{$key}})" wire:model="issue_inspection.inspection_items.{{$key}}.equipment_billing_id">
                                                                    <option value="">Select Capacity</option>
                                                                    @foreach($equipments_billing as $eb_key => $eb_value)
                                                                        <option value="{{$eb_value->id}}">{{$eb_value->capacity}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </td>
                                                        <td class="align-middle"  colspan="1">
                                                            <input type="number" class="form-control"  disabled wire:change="update_item_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_items.{{$key}}.quantity">
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="number" step="0.01" class="form-control" disabled wire:change="update_item_power_rating({{$value['id']}},{{$key}})" min="0.01" wire:model="issue_inspection.inspection_items.{{$key}}.power_rating">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['quantity']}}</td>
                                                        
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 5)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Building Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Floor area</label>
                                        <input type="number" class="form-control" disabled wire:model="issue_inspection.floor_area" wire:change="update_floor_area()">
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Building Information</label>
                                        <div class="mb-3">
                                            <select class="form-select" aria-label="Select Select Building Billing" disabled wire:change="update_building_billing()" required wire:model="issue_inspection.building_billing_id">
                                                <option value="">Select Building billing</option>
                                                @foreach($issue_inspection['building_billings'] as $key => $value)
                                                    @if( $value['id'] == $issue_inspection['building_billing_id'])
                                                        <option selected value="{{$value['id']}}">{{$value['section_name'].' ( '.$value['property_attribute'].' )'}}</option>
                                                    @else
                                                        <option selected value="{{$value['id']}}">{{$value['section_name'].' ( '.$value['property_attribute'].' )'}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Fee</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.building_billing_fee"  >
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 6)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Sanitary Details
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Quantity</th>
                                                    <th>Fee</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_sanitary_billings']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['sanitary_name']}}</td>
                                                        <td class="align-middle">
                                                            <input type="number" disabled class="form-control" wire:change="update_sanitary_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_sanitary_billings.{{$key}}.sanitary_quantity">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['sanitary_quantity']}}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 7)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Signage Details
                                    </h5>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Signage area</label>
                                        <input type="number" class="form-control"  disabled wire:model="issue_inspection.signage_area" wire:change="update_signage_area()">
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Signage Information</label>
                                        <select class="form-select" disabled aria-label="Select Select Signage Billing" wire:change="update_signage_billing()" required wire:model="issue_inspection.signage_id">
                                            <option value="">Select Signage billing</option>
                                            @foreach($issue_inspection['signage_billings'] as $key => $value)
                                                @if( $value['id'] == $issue_inspection['building_billing_id'])
                                                    <option selected value="{{$value['id']}}">{{$value['display_type_name'].' ( '.$value['sign_type_name'].' )'}}</option>
                                                @else
                                                    <option selected value="{{$value['id']}}">{{$value['display_type_name'].' ( '.$value['sign_type_name'].' )'}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" disabled class="form-label">Fee</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.signage_billing_fee">
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 8)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <h5 class="text-center my-2 text-black">
                                        Violation Details
                                    </h5>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-center">Has Proof</th>
                                                    <th>
                                                        Proof
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($issue_inspection['inspection_violations']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['description'].' ( '.$value['category_name']. ' ) '}}</td>
                                                        <td class="align-middle text-center">
                                                            <?php
                                                                if(DB::table('inspection_violation_contents')
                                                                    ->where('inspection_violation_id','=',$value['id'])
                                                                    ->first()
                                                                ){
                                                                    echo '<span class="badge text-light p-2 bg-primary">W/ Proof</span>';
                                                                }else{
                                                                    echo '<span class="badge text-light p-2 bg-warning">W/out Proof</span>';
                                                                }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary "wire:click="view_violation_proof({{$value['id']}},'ProofModaltoggler')"> 
                                                                View
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <th colspan="42" class="text-center">NO DATA</th>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <hr>
                             <div class="row ">
                                <div class="col-lg-12 d-flex justify-content-center">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-12">
                                            @foreach($issue_inspection['steps'] as $key =>$value)
                                                    @if(($issue_inspection['step']-1) == $key)
                                                        <button type="button" id="prevButton" class="btn btn-secondary m-1" wire:click="go_issue({{$key+1}})" >{{$value['name']}}</button>                                      
                                                    @else
                                                        <button type="button" id="prevButton" class="btn btn-outline-secondary m-1" wire:click="go_issue({{$key+1}})" >{{$value['name']}}</button>                                      
                                                    @endif
                                            @endforeach                                      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="modal-footer">
                                @if($issue_inspection['step'] != 1)
                                    <button type="button" id="prevButton" class="btn btn-secondary" wire:click="prev_issue()" >Previous</button>
                                @else
                                    <button type="button" disabled id="prevButton" class="btn btn-secondary" wire:click="prev_issue()" >Previous</button>
                                @endif
                                @if($issue_inspection['step'] != 8)
                                    <button type="button" id="nextButton" class="btn btn-primary" wire:click="next_issue()">Next</button>
                                @else
                                    <button type="button" disabled id="nextButton" class="btn btn-primary opacity-0" wire:click="next_issue()">Next</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deactivateModalLabel">Delete Inspection</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="save_deactivate({{$inspection['id']}},'deactivateModaltoggler')">
                            <div class="modal-body">
                                <div>Are you sure you want to delete this inspection?</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="completeModal" tabindex="-1" aria-labelledby="completeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="completeModalLabel">Complete Inspection</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="save_complete({{$inspection['id']}},'completeModaltoggler')">
                            <div class="modal-body">
                                <input type="checkbox" name="" id="" wire:model.live="email">
                                <label class="form-check-label text-primary" for="">
                                    Email the business?
                                </label>
                                <div>Are you sure you want to complete this inspection?</div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                <button type="submit" class="btn btn-success">Complete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="ProofModal" tabindex="-1" aria-labelledby="ProofModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ProofModalLabel">Inspection Violation Proof</h5>
                            <button type="button" class="btn-close" wire:click="reopenModal()" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>Violation: @if(isset($violation_contents['violation'])) {{$violation_contents['violation']->description}} @endif</div>
                            <form  wire:submit.prevent="upload_photos()">
                                <div class="row d-flex">
                                    <label for="formFileSm" class="form-label text-dark mt-2">Upload Proof</label>
                                    <div class="col-11">
                                        <div class="mb-3">
                                            <input class="form-control form-control" id="formFileSm"   accept="image/jpeg, image/png" wire:model="violation_contents.photos" type="file" multiple>
                                        </div>
                                    </div>
                                    <div class="col-1 ">
                                        <button class="btn btn-primary">
                                            Upload
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <tr>
                                            <th>#</th>
                                            <th class="align-middle text-center">
                                                Image
                                            </th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody  class="overflow-auto" style="max-height500px">
                                        @forelse($violation_contents['inspection_violation_contents']  as $key => $value)
                                            <tr>
                                                <td class="align-middle">
                                                   {{$key+1}}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a href="{{asset('storage/content/proof/'.$value->img_url)}}" target="blank">
                                                        <img class="img-fluid"src="{{asset('storage/content/proof/'.$value->img_url)}}" alt="" style="max-height:200px;max-width:200px; ">
                                                    </a>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-danger "wire:click="delete_proof_photo({{$value->id}})"> 
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="42" class="text-center">NO DATA</th>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="ValidatedProofModal" tabindex="-1" aria-labelledby="ValidatedProofModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ValidatedProofModalLabel">Inspection Validated Violation Proof</h5>
                            <button type="button" class="btn-close" wire:click="reopenModal()" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div>Violation: @if(isset($violation_validated_contents['violation'])) {{$violation_validated_contents['violation']->description}} @endif</div>
                            <form  wire:submit.prevent="upload_validated_photos()">
                                <div class="row d-flex">
                                    <label for="formFileSm" class="form-label text-dark mt-2">Upload Proof</label>
                                    <div class="col-11">
                                        <div class="mb-3">
                                            <input class="form-control form-control" id="formFileSm"   accept="image/jpeg, image/png" wire:model="violation_validated_contents.photos" type="file" multiple>
                                        </div>
                                    </div>
                                    <div class="col-1 ">
                                        <button class="btn btn-primary">
                                            Upload
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <tr>
                                            <th>#</th>
                                            <th class="align-middle text-center">
                                                Image
                                            </th>
                                            <th class="align-middle text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody  class="overflow-auto" style="max-height500px">
                                        @forelse($violation_validated_contents['inspection_violation_validated_contents']  as $key => $value)
                                            <tr>
                                                <td class="align-middle">
                                                   {{$key+1}}
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a href="{{asset('storage/content/validatedproof/'.$value->img_url)}}" target="blank">
                                                        <img class="img-fluid"src="{{asset('storage/content/validatedproof/'.$value->img_url)}}" alt="" style="max-height:200px;max-width:200px; ">
                                                    </a>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-danger "wire:click="delete_validated_proof_photo({{$value->id}})"> 
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="42" class="text-center">NO DATA</th>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="certModal" tabindex="-1" aria-labelledby="certModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="certModalLabel">Generate Certificate</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($annual_certificate_inspection['step']/3)*100}}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @if($annual_certificate_inspection['step'] == 1)
                            <div >
                                <div class="mb-3">
                                    <label for="name" class="form-label"> Application type</label>
                                    <div class="mb-3">
                                        <select class="form-select" aria-label="Select Application type" required wire:model.live="annual_certificate_inspection.application_type_id">
                                            <option value="">Select Application type</option>
                                            @foreach($annual_certificate_inspection['application_types'] as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if($annual_certificate_inspection['business_id'])
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Business name</label>
                                                <input type="text" class="form-control" disabled value="{{$annual_certificate_inspection['business']->name}}">
                                            </div>  
                                        </div>
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
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                        <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                            <tr>
                                                <th>Name</th>
                                                <th colspan="1">Category <span class="text-danger">*</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($annual_certificate_inspection['annual_certificate_inspection_inspector']  as $key =>$value)
                                                <tr>
                                                    <td>{{$value['content']['first_name'].' '.$value['content']['middle_name'].' '.$value['content']['last_name'].' '.$value['content']['suffix'].' ( '.$value['content']['work_role_name'].' ) '.(isset($value['content']['inspector_team']) ? '( '.$value['content']['inspector_team'].' )' : '( Not assigend )')}}</td>
                                                    <td>
                                                        <select name="" id="" class="form-select" required wire:model.live="annual_certificate_inspection.annual_certificate_inspection_inspector.{{$key}}.category_id" wire:change="update_inspector_inspection_role({{$value['content']['user_id']}},{{$key}})">
                                                            <option value="" selected>Select Category</option>
                                                            @foreach($annual_certificate_inspection['annual_certificate_categories'] as $ckey => $cvalue)
                                                                <option value="{{$cvalue->id}}">{{$cvalue->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @elseif($annual_certificate_inspection['step'] == 3)
                            <div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">BIN</label>
                                            <input type="text" class="form-control" wire:model.live="annual_certificate_inspection.bin"  >
                                        </div>  
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Occupancy no.</label>
                                            <input type="number" min="1" class="form-control" wire:model.live="annual_certificate_inspection.occupancy_no">
                                        </div>  
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Date Complied <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" required wire:model.live="annual_certificate_inspection.date_compiled">
                                        </div>  
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Issued On</label>
                                            <input type="date" class="form-control" wire:model.live="annual_certificate_inspection.issued_on">
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            @if($annual_certificate_inspection['step'] != 1)
                            <button type="button" id="prevButton" class="btn btn-secondary" wire:click="prev_annual()" >Previous</button>
                            @endif
                            @if($annual_certificate_inspection['step'] < 3)
                                <button type="button" id="nextButton" class="btn btn-primary" wire:click="next_annual('addModaltoggler')">Next</button>
                            @else
                                <button type="button" id="addButton" class="btn btn-success"  wire:click="next_annual('addModaltoggler')">Generate Certificate</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>    
    </div>
</div>
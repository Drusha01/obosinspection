<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row d-flex mt-4 mb-4">
                <div class="col-lg-8 col-md-12">
                    <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                </div>
                <div class="col-3">
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
                <div class="col-lg-2 col-md-2">
                    <div class="col-2 d-flex ">
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
                <div class="col-4 d-flex justify-content-end ">
                    <button type="button" class="btn btn-primary" wire:click="add('addModaltoggler')"  >
                        Add Inspection Schedule
                    </button>
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
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            @foreach($table_filter['filter'] as $filter_key => $filter_value)
                                @if($filter_value['active'])
                                    @if($filter_value['name'] == 'Action')
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Status')
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Inspection Details')
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
                                    @elseif($filter_value['name'] == 'Status' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-success" wire:click="update_status({{$value->id}},'On-going')">
                                                On-going
                                            </button>
                                        </td>
                                    @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <!-- <button class="btn btn-outline-secondary" @if($value->status_name == 'Pending') disabled @else @endif wire:click="edit({{$value->id}},'completeModaltoggler')">
                                                Complete
                                            </button> -->
                                            @if($value->is_active)
                                                <button class="btn btn-danger" wire:click="edit({{$value->id}},'deactivateModaltoggler')">
                                                    Delete
                                                </button>
                                            @endif
                                        </td>
                                    @elseif($filter_value['name'] == 'Inspection Details' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-primary"   wire:click="issue({{$value->id}},'issueModaltoggler')">
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
                                <div class="row d-flex justify-content-end">
                                    <div class="col-3 d-flex">
                                        <span for="business_from" class="align-middle mt-2">From</span>
                                        <select name="" id="business_from" class="form-select" wire:model.live="business_from">
                                            <option value="from-email" >Request Email</option>
                                            <option value="Bypass" selected>Over the counter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="business_search">Search Business</label>
                                        <input type="text" name="" id="business_search" class="form-control" wire:model.live.debounce.500ms="modal.search" placeholder="Search business ... ">
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="business_search">Filter Barangay</label>
                                            <select name="" id=""  class="form-select" wire:model.live.debounce="modal.brgy_id">
                                                <option value="">Select Barangay</option>
                                                @foreach($brgy as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <label for="select_business">Select Business <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" id="select_business" aria-label="Select Member" required wire:change="last_inspection()" wire:model.live="inspection.business_id">
                                                    <option value="">Select Business</option>
                                                @foreach($businesses as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name.' ('.$value->business_type_name.') brgy: '.$value->barangay}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="select_business">Filter Business Category</label>
                                        <div class="mb-3">
                                            <select class="form-select" id="select_business" aria-label="Select Member" wire:change="update_business_id()" wire:model.live="modal.business_category_id">
                                                    <option value="">Select Business Category</option>
                                                    @foreach($business_categories as $key => $value)
                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
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
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                        <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                            <tr>
                                                <th class="align-middle">Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection['inspector_leaders']  as $key =>$value)
                                                <tr>
                                                    <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
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
                            <h5 class="modal-title" id="issueModalLabel">Inspection Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar-2" style="width:{{($issue_inspection['step']/8)*100}} %" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
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
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($issue_inspection['inspector_team_leaders']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    </tr>
                                                @endforeach
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
                                            @foreach($inspector_members as $key =>  $value)
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
                                                @foreach($issue_inspection['inspection_inspector_members']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                        <td class="align-middle text-center">
                                                            <button class="btn btn-danger " wire:click="update_delete_members({{$value->id}})"> 
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
                                @if($issue_inspection['step'] != 3)
                                    <button type="button" id="nextButton" class="btn btn-primary" wire:click="next_issue()">Next</button>
                                @else
                                    <button type="button" id="nextButton" class="btn btn-outline-primary" wire:click="update_status({{$issue_inspection['id']}},'On-going')">Ongoing</button>
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

        </div>    
    </div>
</div>
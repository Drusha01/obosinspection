<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <div class="p-0 m-0"  >
                    
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            @foreach($filter as $filter_key => $filter_value)
                                @if($filter_value['name'] == 'Action')
                                    <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                @elseif($filter_value['name'] == 'Inspection Details')
                                    <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                @else 
                                    <th scope="col">{{$filter_value['name']}}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($table_data as $key => $value)
                            <tr>
                                @foreach($filter as $filter_key => $filter_value)
                                    @if($filter_value['name'] == '#' && $filter_value['active'])
                                        <th class="align-middle">{{($table_data->currentPage()-1)*$table_data->perPage()+$key+1 }}</th>
                                    @elseif ($filter_value['name'] == 'Image'  && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <a href="{{asset('storage/content/business/'.$value->{$filter_value['column_name']})}}" target="blank">
                                                <img class="img-fluid"src="{{asset('storage/content/business/'.$value->{$filter_value['column_name']})}}" alt="" style="max-height:50px;max-width:50px; ">
                                            </a>
                                        </td>
                                    @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-secondary" wire:click="edit({{$value->id}},'completeModaltoggler')">
                                                Complete
                                            </button>
                                            @if($value->is_active)
                                                <button class="btn btn-danger" wire:click="edit({{$value->id}},'deactivateModaltoggler')">
                                                    Delete
                                                </button>
                                            @endif
                                        </td>
                                    @elseif($filter_value['name'] == 'Inspection Details' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-primary" disabled wire:click="issue({{$value->id}},'issueModaltoggler')">
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
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Inspection Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($inspection['step']/3)*100}}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @if($inspection['step'] == 1)
                            <div >
                                <div class="mb-3">
                                    <label for="name" class="form-label">Business Name</label>
                                    <div class="mb-3">
                                        <select class="form-select" aria-label="Select Barangay" required wire:model.live="inspection.business_id">
                                            <option value="">Select Business</option>
                                            @foreach($businesses as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name.' ('.$value->business_type_name.') brgy: '.$value->barangay}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="inspection_date" class="form-label">Inspection Date</label>
                                    <input type="date" class="form-control" id="inspection_date" wire:model.live="inspection.schedule_date" required min="{{$inspection['schedule_date']}}">
                                </div>
                            </div>
                            @elseif($inspection['step'] == 2)
                            <div>
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
                                                <th>Name</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection['inspector_leaders']  as $key =>$value)
                                                <tr>
                                                    <td>{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
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
                            @elseif($inspection['step'] == 3)
                            <div>
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
                                                <th>Name</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection['inspector_members']  as $key =>$value)
                                                <tr>
                                                    <td>{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
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
                                    <div class="input-group mb-3">
                                        <select class="form-select" id="teamLeaderSelect" wire:model="issue_inspection.item_id">
                                            <option value="">Select Item</option>
                                            @foreach($issue_inspection['items'] as $key =>  $value)
                                                <option selected value="{{$value->id}}">{{$value->name.' ( '.$value->category_name.' )'.'( '.$value->section_name.' )'}}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary" type="button" wire:click="update_inspection_items()" ><i class="bi bi-plus"></i></button>
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
                                                @foreach($issue_inspection['inspection_items']  as $key => $value)
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
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 3)
                                <div wire:key="{{$issue_inspection['step']}}">
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
                            @elseif($issue_inspection['step'] == 4)
                                <div wire:key="{{$issue_inspection['step']}}">
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
                                                @foreach($issue_inspection['inspection_sanitary_billings']  as $key => $value)
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
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 5)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Signage Information</label>
                                        <div class="mb-3">
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
                                    </div>
                                    <div class="mb-3">
                                        <label for="inspection_date" class="form-label">Fee</label>
                                        <input type="text" class="form-control" disabled wire:model="issue_inspection.signage_billing_fee">
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 6)
                                <div wire:key="{{$issue_inspection['step']}}">
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
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 7)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th class="align-middle">Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($issue_inspection['inspection_inspector_members']  as $key =>$value)
                                                    <tr>
                                                        <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 8)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <div class="input-group mb-3">
                                        <select class="form-select" id="teamLeaderSelect" wire:model="issue_inspection.violation_id">
                                            <option value="">Select Violation</option>
                                            @foreach($issue_inspection['violations'] as $key =>  $value)
                                                <option selected value="{{$value->id}}">{{$value->description}}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-primary" type="button" wire:click="update_inspection_violation()" ><i class="bi bi-plus"></i></button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="align-middle text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($issue_inspection['inspection_violations']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['description']}}</td>
                                                        <td class="align-middle text-center">
                                                            <button class="btn btn-danger "wire:click="update_delete_violation({{$value['id']}})"> 
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
                                <div class="col d-flex justify-content-center">
                                @for($i=0; $i < 8; $i++)
                                    @if(($issue_inspection['step']-1) == $i)
                                        <button type="button" id="prevButton" class="btn btn-secondary mx-2" wire:click="go_issue({{$i+1}})" >{{$i+1}}</button>                                      
                                    @else
                                        <button type="button" id="prevButton" class="btn btn-outline-secondary mx-2" wire:click="go_issue({{$i+1}})" >{{$i+1}}</button>                                      
                                    @endif
                                @endfor                                         
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
                        <div class="modal-body">
                            <form wire:submit.prevent="save_deactivate({{$inspection['id']}},'deactivateModaltoggler')">
                                <div>Are you sure you want to delete this inspection?</div>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
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
                        <div class="modal-body">
                            <form wire:submit.prevent="save_complete({{$inspection['id']}},'completeModaltoggler')">
                                <div>Are you sure you want to complete this inspection?</div>
                                <button type="submit" class="btn btn-success">Complete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>    
    </div>
</div>
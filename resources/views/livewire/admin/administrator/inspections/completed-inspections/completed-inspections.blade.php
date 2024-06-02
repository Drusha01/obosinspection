<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <div class="p-0 m-0" wire:click="add('addModaltoggler')" >
                    
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
                                @elseif($filter_value['name'] == 'Generate')
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
                                    @elseif($filter_value['name'] == 'Generate' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <a class="btn btn-outline-primary my-1" target="_blank" href="/administrator/inspections/generate/{{$value->id}}">
                                                Generate Equipment PDF
                                            </a>
                                            <button class="btn btn-outline-primary" wire:click="generate_cert({{$value->id}},'certModaltoggler')" >
                                                Generate Certificate
                                            </button>
                                        </td> 
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

            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" id="editModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#deactivateModal" id="deactivateModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#activateModal" id="activateModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#issueModal" id="issueModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#certModal" id="certModaltoggler" style="display:none;"></button>
            
            
            <div wire:ignore.self class="modal fade" id="issueModal" tabindex="-1" aria-labelledby="issueModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="issueModalLabel">Inspection Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" style="width:{{($issue_inspection['step']/8)*100}} %" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @if($issue_inspection['step'] == 1)
                                <div wire:key="{{$issue_inspection['step']}}">
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
                                                                <select class="form-select" id="teamLeaderSelect" disabled wire:change="update_equipment_billing({{$value['id']}},{{$key}})" wire:model="issue_inspection.inspection_items.{{$key}}.equipment_billing_id">
                                                                    <option value="">Select Capacity</option>
                                                                    @foreach($equipments_billing as $eb_key => $eb_value)
                                                                        <option value="{{$eb_value->id}}">{{$eb_value->capacity}}</option>
                                                                    @endforeach
                                                                </select>
                                                        </td>
                                                        <td class="align-middle"  colspan="1">
                                                            <input type="number" class="form-control" disabled wire:change="update_item_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_items.{{$key}}.quantity">
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="number" step="0.01" disabled class="form-control" wire:change="update_item_power_rating({{$value['id']}},{{$key}})" min="0.01" wire:model="issue_inspection.inspection_items.{{$key}}.power_rating">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['quantity']}}</td>
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
                            @elseif($issue_inspection['step'] == 4)
                                <div wire:key="{{$issue_inspection['step']}}">
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
                                                @foreach($issue_inspection['inspection_sanitary_billings']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['sanitary_name']}}</td>
                                                        <td class="align-middle">
                                                            <input type="number" disabled class="form-control" wire:change="update_sanitary_quantity({{$value['id']}},{{$key}})" min="1" wire:model="issue_inspection.inspection_sanitary_billings.{{$key}}.sanitary_quantity">
                                                        </td>
                                                        <td class="align-middle">{{$value['fee']*$value['sanitary_quantity']}}</td>
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
                                            <select class="form-select" aria-label="Select Select Signage Billing" disabled wire:change="update_signage_billing()" required wire:model="issue_inspection.signage_id">
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
                                                    <th>Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($issue_inspection['inspector_team_leaders']  as $key =>$value)
                                                    <tr>
                                                        <td>{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</td>
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
                                                    <th>Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($issue_inspection['inspection_inspector_members']  as $key =>$value)
                                                    <tr>
                                                        <td>{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @elseif($issue_inspection['step'] == 8)
                                <div wire:key="{{$issue_inspection['step']}}">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                            <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-center align-middle">Is Complied</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($issue_inspection['inspection_violations']  as $key => $value)
                                                    <tr>
                                                        <td class="align-middle">{{$value['description']}}</td>
                                                        <td class="text-center align-middle">
                                                            <input type="checkbox" value="1" @if($value['remarks'])) checked @endif  wire:change="update_complied_violation({{$value['id']}})">
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
                                                <th colspan="1">Category <span class="text-danger">*</span></th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($annual_certificate_inspection['annual_certificate_inspection_inspector']  as $key =>$value)
                                                <tr>
                                                    <td>{{$value['content']->first_name.' '.$value['content']->middle_name.' '.$value['content']->last_name.' '.$value['content']->suffix.' ( '.$value['content']->work_role_name.' ) '}}</td>
                                                    <td>
                                                        <select name="" id="" class="form-select" required wire:model.live="annual_certificate_inspection.annual_certificate_inspection_inspector.{{$key}}.category_id">
                                                            <option value="" selected>Select Category</option>
                                                            @foreach($annual_certificate_inspection['annual_certificate_categories'] as $ckey => $cvalue)
                                                                <option value="{{$cvalue->id}}">{{$cvalue->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-danger" wire:click="delete_annual_inspector({{$value['content']->id}})">
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
<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row d-flex mt-4 mb-4">
                <div class="col">
                    <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
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
                <div class="col-4 d-flex justify-content-end ">
                    <button type="button" class="btn btn-primary"  wire:click="add('addModaltoggler')">
                        Add
                    </button>
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
                                    @elseif($filter_value['name'] == 'Members')
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Designated Barangays')
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @else 
                                        <th scope="col">{{$filter_value['name']}}</th>
                                    @endif
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($table_data as $key =>$value)
                            <tr>
                                @foreach($table_filter['filter'] as $filter_key => $filter_value)
                                    @if($filter_value['name'] == '#' && $filter_value['active'])
                                        <th class="align-middle">{{($table_data->currentPage()-1)*$table_data->perPage()+$key+1 }}</th>
                                    @elseif($filter_value['name'] == 'Members' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-primary" wire:click="view_members({{$value->id}},'viewMembersModaltoggler')">
                                                View
                                            </button>
                                        </td>
                                    @elseif($filter_value['name'] == 'Team Leader' && $filter_value['active'])
                                        <td >{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</td>
                                    @elseif($filter_value['name'] == 'Designated Barangays' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-primary" wire:click="add_designation({{$value->id}},'addDesignationModaltoggler')">
                                                View
                                            </button>
                                        </td>
                                    @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            @if($value->is_active)
                                                <button class="btn btn-danger" wire:click="edit({{$value->id}},'deactivateModaltoggler')">
                                                    Deactivate
                                                </button>
                                            @else 
                                                <button class="btn btn-warning" wire:click="edit({{$value->id}},'activateModaltoggler')">
                                                    Activate
                                                </button>
                                            @endif
                                            <button class="btn btn-secondary" wire:click="edit({{$value->id}},'editModaltoggler')">
                                                Edit
                                            </button>
                                        </td>
                                    @elseif ($filter_value['name'] == 'Suffix'  && $filter_value['active'])
                                        <td class="align-middle">{{ $value->suffix }}</td>
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
            <!-- Pagination -->
            <div class="container d-flex justify-content-center">
                {{$table_data->links()}}
            </div>
        </div>

        <!-- Hidden buttons for modal toggling -->
        <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" id="editModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#deactivateModal" id="deactivateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#activateModal" id="activateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#addDesignationModal" id="addDesignationModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#viewDesignationModal" id="viewDesignationModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#viewMembersModal" id="viewMembersModaltoggler" style="display:none;"></button>
        
        
        <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Inspector Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_add('addModaltoggler')">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="username" class="form-label">Group name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required wire:model="inspector_team.name" placeholder="Enter Group name">
                            </div>
                            <div class="mb-3">
                                <label for="brgy_id" class="form-label">Team Leader <span class="text-danger">*</span></label>
                                <select class="form-select" aria-label="Select Barangay" required wire:model="inspector_team.team_leader_id" wire:change="update_temp_members()">
                                    <option value="">Select Team Leader</option>
                                    @foreach($unassigned_inspectors as $key => $value)
                                        <option value="{{$value->id}}">{{'( '.$value->first_name.' ) '.$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <label for="inspectors" class="form-label">Member Inspector</label>
                                <div class="col-8">
                                    <div class="mb-3">
                                        <select class="form-select" id="inspectors" aria-label="Select Member" wire:model="inspector_team.member_id">
                                            <option value="">Select Inspector Member</option>
                                            @foreach($inspector_members as $key => $value)
                                                <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 mx-3" >
                                    <button class="btn btn-primary" type="button" wire:click="add_temp_members()">Add</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <tr>
                                            <th class="align-middle text-center"></th>
                                            <th class="align-middle"> Member</th>
                                            <th class="align-middle text-center">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($temp_members as $key => $value)
                                            <tr>
                                                <td class="align-middle text-center">{{$key+1}}</td>
                                                <td class="mx-2 align-middle">
                                                    {{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-danger" type="button" wire:click="delete_temp_member({{$value->id}})">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Inspector Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_edit({{$inspector_team['id']}},'editModaltoggler')">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="username" class="form-label">Group name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required wire:model="inspector_team.name" placeholder="Enter Group name">
                            </div>
                            <div class="mb-3">
                                <label for="brgy_id" class="form-label">Team Leader <span class="text-danger">*</span></label>
                                <select class="form-select" aria-label="Select Barangay" required wire:model="inspector_team.team_leader_id">
                                    @if(isset($inspector_team['team_leader']))
                                        <option selected value="{{$inspector_team['team_leader']->id}}">{{'( '.$inspector_team['team_leader']->first_name.' ) '.$inspector_team['team_leader']->first_name.' '.$inspector_team['team_leader']->middle_name.' '.$inspector_team['team_leader']->last_name.' '.$inspector_team['team_leader']->suffix.' ( '.$inspector_team['team_leader']->work_role_name.' ) '}}</option>
                                    @endif
                                    @foreach($unassigned_inspectors as $key => $value)
                                        <option value="{{$value->id}}">{{'( '.$value->first_name.' ) '.$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <label for="inspectors" class="form-label">Team Member Inspector </label>
                                <div class="col-8">
                                    <div class="mb-3">
                                        <select class="form-select" id="inspectors" aria-label="Select Member" wire:model="inspector_team.member_id">
                                            <option value="">Select Inspector Member</option>
                                            @foreach($inspector_members as $key => $value)
                                                <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 mx-3" >
                                    <button class="btn btn-primary" type="button" wire:click="save_add_member()">Add</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <tr>
                                            <th class="align-middle text-center"></th>
                                            <th class="align-middle"> Member</th>
                                            <th class="align-middle text-center">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($members as $key => $value)
                                            <tr>
                                                <td class="align-middle text-center">{{$key+1}}</td>
                                                <td class="mx-2 align-middle">
                                                    {{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '}}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-danger" type="button" wire:click="delete_member({{$value->id}},{{$value->member_id}})">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

        <div wire:ignore.self class="modal fade" id="addDesignationModal" tabindex="-1" aria-labelledby="addDesignationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDesignationModalLabel">Add Designation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add_designation()">
                            <div class="row">
                                <label for="designated_brgy" class="form-label">Barangay <span class="text-danger">*</span></label>
                                <div class="col-8">
                                    <div class="mb-3">
                                        <select class="form-select" id="designated_brgy" aria-label="Select Barangay" required wire:model="inspector_team.brgy_id">
                                            <option value="">Select Designated Barangay</option>
                                            @foreach($brgy as $key => $value)
                                                <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 mx-" >
                                    <button class="btn btn-primary" type="submit">Add</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <tr>
                                            <th class="align-middle text-center"></th>
                                            <th class="align-middle"> Barangay</th>
                                            <th class="align-middle text-center">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($designations as $key =>$value)
                                        <tr>
                                            <td class="align-middle text-center">{{$key+1}}</td>
                                            <td class="mx-2 align-middle">
                                                {{$value->brgyDesc}}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="delete_designation({{$value->id}},{{$value->brgy_id}})">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="viewDesignationModal" tabindex="-1" aria-labelledby="viewDesignationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDesignationModalLabel">View Designation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add_designation()">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <tr>
                                            <th class="align-middle text-center"></th>
                                            <th class="align-middle"> Barangay</th>
                                            <th class="align-middle text-center">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($designations as $key =>$value)
                                        <tr>
                                            <td class="align-middle text-center">{{$key+1}}</td>
                                            <td class="mx-2 align-middle">
                                                {{$value->brgyDesc}}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="delete_designation({{$value->id}},{{$value->brgy_id}})">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="viewMembersModal" tabindex="-1" aria-labelledby="viewMembersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewMembersModalLabel">View Members</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add_member()">
                            <div class="row">
                                <label for="inspectors" class="form-label">Inspector <span class="text-danger">*</span></label>
                                <div class="col-8">
                                    <div class="mb-3">
                                        <select class="form-select" id="inspectors" aria-label="Select Member" required wire:model="inspector_team.member_id">
                                            <option value="">Select Inspector Member</option>
                                            @foreach($inspector_members as $key => $value)
                                                <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3 mx-3" >
                                    <button class="btn btn-primary" type="submit">Add</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <tr>
                                            <th class="align-middle text-center"></th>
                                            <th class="align-middle"> Member</th>
                                            <th class="align-middle text-center">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($members as $key => $value)
                                            <tr>
                                                <td class="align-middle text-center">{{$key+1}}</td>
                                                <td class="mx-2 align-middle">
                                                    {{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-danger" type="button" wire:click="delete_member({{$value->id}},{{$value->member_id}})">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Deactivate Modal -->
        <div wire:ignore.self class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deactivateModalLabel">Deactivate Inspector Group </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_deactivate({{$inspector_team['id']}},'deactivateModaltoggler')">
                        <div class="modal-body">
                            <div>Are you sure you want to deactivate this?</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-danger">Deactivate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Activate Modal -->
        <div wire:ignore.self class="modal fade" id="activateModal" tabindex="-1" aria-labelledby="activateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activateModalLabel">Activate Inspector Group </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_activate({{$inspector_team['id']}},'activateModaltoggler')">
                        <div class="modal-body">
                            <div>Are you sure you want to activate this?</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-warning">Activate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        

    </div>
</div>
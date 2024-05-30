<div>
    <div class="content">
        <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                    <h1 class="h3 mb-0 p-0  text-black" >{{$title}}</h1>
                    <div class="p-0 m-0" >
                        <button type="button" class="btn btn-primary" wire:click="add('addModaltoggler')">
                            Add Inspector Group
                        </button>
                    </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            @foreach($filter as $filter_key => $filter_value)
                                @if($filter_value['name'] == 'Action')
                                    <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                @elseif($filter_value['name'] == 'Designated Barangays')
                                <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                @else 
                                    <th scope="col">{{$filter_value['name']}}</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($table_data as $key =>$value)
                            <tr>
                                @foreach($filter as $filter_key => $filter_value)
                                    @if($filter_value['name'] == '#' && $filter_value['active'])
                                        <th class="align-middle">{{($table_data->currentPage()-1)*$table_data->perPage()+$key+1 }}</th>
                                    @elseif($filter_value['name'] == 'Members' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-primary" wire:click="view_members({{$value->id}},'viewMembersModaltoggler')">
                                                View
                                            </button>
                                        </td>
                                    @elseif($filter_value['name'] == 'Designated Barangays' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-primary" wire:click="add_designation({{$value->id}},'addDesignationModaltoggler')">
                                                Add Designation
                                            </button>
                                            <button class="btn btn-outline-primary" wire:click="add_designation({{$value->id}},'viewDesignationModaltoggler')">
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
            <div class="pagination-container mt-3">
                <ul class="d-flex justify-content-center list-unstyled">
                    <li><a class="btn btn-outline-secondary mx-1" href="{{ $table_data->previousPageUrl() }}">Previous</a></li>
                    @foreach ($table_data->getUrlRange(1, $table_data->lastPage()) as $page => $url)
                        <li>
                            <a class="btn mx-1 btn-{{ $page == $table_data->currentPage() ? 'secondary' : 'outline-secondary' }}" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li><a class="btn mx-1 btn-outline-secondary" href="{{ $table_data->nextPageUrl() }}">Next</a></li>
                </ul>
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
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Inspector Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add('addModaltoggler')">
                            <div class="mb-3">
                                <label for="username" class="form-label">Group name</label>
                                <input type="text" class="form-control" required wire:model="inspector_team.name">
                            </div>
                            <div class="mb-3">
                                <label for="brgy_id" class="form-label">Team Leader</label>
                                <select class="form-select" aria-label="Select Barangay" required wire:model="inspector_team.team_leader_id">
                                    <option value="">Select Team Leader</option>
                                    @foreach($unassigned_inspectors as $key => $value)
                                        <option value="{{$value->id}}">{{'( '.$value->first_name.' ) '.$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Inspector Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_edit({{$inspector_team['id']}},'editModaltoggler')">
                            <div class="mb-3">
                                <label for="username" class="form-label">Group name</label>
                                <input type="text" class="form-control" required wire:model="inspector_team.name">
                            </div>
                            <div class="mb-3">
                                <label for="brgy_id" class="form-label">Team Leader</label>
                                <select class="form-select" aria-label="Select Barangay" required wire:model="inspector_team.team_leader_id">
                                    <option value="">Select Team Leader</option>
                                    @foreach($all_inspectors as $key => $value)
                                        @if($value->team_leader_id && $inspector_team['team_leader_id'] ==  $value->team_leader_id)
                                            <option selected value="{{$value->id}}">{{'( '.$value->first_name.' ) '.$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                        @elseif(!isset($value->team_leader_id))
                                            <option value="{{$value->id}}">{{'( '.$value->first_name.' ) '.$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
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
                                <div class="col-8">
                                    <div class="mb-3">
                                        <select class="form-select" aria-label="Select Barangay" required wire:model="inspector_team.brgy_id">
                                            <option value="">Select Designated Barangay</option>
                                            @foreach($brgy as $key => $value)
                                                <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
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
                                            <th class="align-middle"> Barangay</th>
                                            <th class="align-middle text-center">Action </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($designations as $key =>$value)
                                        <tr>
                                            <td class="align-middle text-center">{{$key+1}}</td>
                                            <td class="mx-2">
                                                {{$value->brgyDesc}}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="delete_designation({{$value->id}})">
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
                                            <td class="mx-2">
                                                {{$value->brgyDesc}}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="delete_designation({{$value->id}})">
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
                                <div class="col-8">
                                    <div class="mb-3">
                                        <select class="form-select" aria-label="Select Member" required wire:model="inspector_team.member_id">
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
                                                <td class="mx-2">
                                                    {{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-danger" type="button" wire:click="delete_member({{$value->id}})">
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
                    <div class="modal-body">
                        <form wire:submit.prevent="save_deactivate({{$inspector_team['id']}},'deactivateModaltoggler')">
                            <div>Are you sure you want to deactivate this inspector?</div>
                            <button type="submit" class="btn btn-danger">Deactivate</button>
                        </form>
                    </div>
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
                    <div class="modal-body">
                        <form wire:submit.prevent="save_activate({{$inspector_team['id']}},'activateModaltoggler')">
                            <div>Are you sure you want to activate this inspector?</div>
                            <button type="submit" class="btn btn-warning">Activate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        

    </div>
</div>
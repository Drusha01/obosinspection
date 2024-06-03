<div>
    <div class="content">
        <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                    <h1 class="h3 mb-0 p-0  text-black" >{{$title}}</h1>
                    <div class="p-0 m-0" >
                        <button type="button" class="btn btn-primary" wire:click="add('addModaltoggler')">
                            Add Inspector
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
                                            <button class="btn btn-outline-danger" wire:click="edit({{$value->id}},'recoverModaltoggler')">
                                                Change Password
                                            </button>
                                        </td>
                                    @elseif ($filter_value['name'] == 'Image'  && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <a href="{{asset('storage/content/profile/'.$value->{$filter_value['column_name']})}}" target="blank">
                                                <img class="img-fluid"src="{{asset('storage/content/profile/'.$value->{$filter_value['column_name']})}}" alt="" style="max-height:50px;max-width:50px; ">
                                            </a>
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
        <button type="button" data-bs-toggle="modal" data-bs-target="#recoverModal" id="recoverModaltoggler" style="display:none;"></button>
        

        
        
        <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Inspector</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add('addModaltoggler')">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" wire:model="person.img_url">
                            </div>
                            <div class="mb-3">
                                <label for="brgy_id" class="form-label">Work Role</label>
                                <select class="form-select" aria-label="Select Barangay" required wire:model="person.work_role_id">
                                    <option value="">Select Role</option>
                                    @foreach($work_roles as $key => $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" required wire:model="person.username">
                                    </div>
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" wire:model="person.first_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" wire:model="person.middle_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" required wire:model="person.last_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control" wire:model="person.suffix">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="brgy_id" class="form-label">Barangay</label>
                                        <select class="form-select" aria-label="Select Barangay" required wire:model="person.brgy_id">
                                            <option value="">Select Barangay</option>
                                            @foreach($brgy as $key => $value)
                                                <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" required wire:model="person.contact_number">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" required wire:model="person.email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" required wire:model="person.password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="cpassword" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control"required wire:model="person.cpassword">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

                                                                  
        <div wire:ignore.self class="modal fade" id="recoverModal" tabindex="-1" aria-labelledby="recoverModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="recoverModalLabel">Recover Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_recover_password({{$person['id']}},'recoverModaltoggler')">
                            <h5 class="m-3">@if($person['id']) {{$person['first_name'].' '.$person['middle_name'].' '.$person['last_name'].' '.$person['suffix']}} @endif</h5>
                            <div class="mb-3">
                                <label for="password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" required wire:model="person.current_password">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" required wire:model="person.password">
                            </div>
                            <div class="mb-3">
                                <label for="cpassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control"required wire:model="person.cpassword">
                            </div>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Inspector</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_edit({{$person['id']}},'editModaltoggler')">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" wire:model="person.img_url">
                            </div>
                            <div class="mb-3">
                                <label for="brgy_id" class="form-label">Work Role</label>
                                <select class="form-select" aria-label="Select Barangay" required wire:model="person.work_role_id">
                                    <option value="">Select Role</option>
                                    @foreach($work_roles as $key => $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" required disabled wire:model="person.username">
                                    </div>
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" wire:model="person.first_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" wire:model="person.middle_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" required wire:model="person.last_name">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="brgy_id" class="form-label">Barangay</label>
                                        <select class="form-select" aria-label="Select Barangay" required wire:model="person.brgy_id">
                                            <option value="">Select Barangay</option>
                                            @foreach($brgy as $key => $value)
                                                <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control" required wire:model="person.contact_number">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" required wire:model="person.email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control" wire:model="person.suffix">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
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
                        <h5 class="modal-title" id="deactivateModalLabel">Deactivate Inspector </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_deactivate({{$person['id']}},'deactivateModaltoggler')">
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
                        <h5 class="modal-title" id="activateModalLabel">Activate Inspector </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_activate({{$person['id']}},'activateModaltoggler')">
                            <div>Are you sure you want to activate this inspector?</div>
                            <button type="submit" class="btn btn-warning">Activate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
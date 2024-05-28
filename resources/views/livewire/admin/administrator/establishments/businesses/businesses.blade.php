<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800" >{{$title}}</h1>
            </div>
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center w-50">
                    <label for="search" class="form-label mb-0 mr-2">Search:</label>
                    <input type="text" id="search" class="form-control" placeholder="Enter Name" wire:model="searchTerm">
                </div>
                <div>
                    <button type="button" class="btn btn-primary"  wire:click="add('addModaltoggler')">
                        Add Business
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
                                    @elseif($filter_value['name'] == 'Owner' && $filter_value['active'])
                                        <td >{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</td>
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
        </div>

        <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" id="editModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#deactivateModal" id="deactivateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#activateModal" id="activateModaltoggler" style="display:none;"></button>

        <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add('addModaltoggler')">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" wire:model="business.img_url">
                                </div>
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control" wire:model="business.name">
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Owner" wire:model="business.owner_id">
                                        <option value="">Select Owner</option>
                                        @foreach($owners as $key => $value)
                                            <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Business Type" wire:model="business.business_type_id">
                                        <option value="">Select Business Type</option>
                                        @foreach($business_types as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Barangay" wire:model="business.brgy_id">
                                        <option value="">Select Barangay</option>
                                        @foreach($brgy as $key => $value)
                                            <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="street_address" class="form-label">Street</label>
                                    <input type="text" class="form-control" wire:model="business.street_address">
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Occupation Classification" wire:model="business.occupancy_classification_id">
                                        <option value="">Select Occupation Classification</option>
                                        @foreach($occupancy_classifications as $key => $value)
                                            <option value="{{$value->id}}">{{$value->character_of_occupancy. ' ('.$value->character_of_occupancy_group.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact number</label>
                                    <input type="number" class="form-control" required wire:model="business.contact_number">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" required wire:model="business.email">
                                </div>
                                <div class="mb-3">
                                    <label for="floor_area" class="form-label">Floor area</label>
                                    <input type="number" class="form-control" required wire:model="business.floor_area">
                                </div>
                                <div class="mb-3">
                                    <label for="signage_area" class="form-label">Signage area</label>
                                    <input type="number" class="form-control" required wire:model="business.signage_area">
                                </div>
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
                        <h5 class="modal-title" id="editModalLabel">Edit Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_edit({{$business['id']}},'editModaltoggler')">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" wire:model="business.img_url">
                                </div>
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control" wire:model="business.name">
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Owner" wire:model="business.owner_id">
                                        <option value="">Select Owner</option>
                                        @foreach($owners as $key => $value)
                                            <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Business Type" wire:model="business.business_type_id">
                                        <option value="">Select Business Type</option>
                                        @foreach($business_types as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Barangay" wire:model="business.brgy_id">
                                        <option value="">Select Barangay</option>
                                        @foreach($brgy as $key => $value)
                                            <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="street_address" class="form-label">Street</label>
                                    <input type="text" class="form-control" wire:model="business.street_address">
                                </div>
                                <div class="mb-3">
                                    <select class="form-select" aria-label="Select Occupation Classification" wire:model="business.occupancy_classification_id">
                                        <option value="">Select Occupation Classification</option>
                                        @foreach($occupancy_classifications as $key => $value)
                                            <option value="{{$value->id}}">{{$value->character_of_occupancy. ' ('.$value->character_of_occupancy_group.')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">Contact number</label>
                                    <input type="number" class="form-control" required wire:model="business.contact_number">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" required wire:model="business.email">
                                </div>
                                <div class="mb-3">
                                    <label for="floor_area" class="form-label">Floor area</label>
                                    <input type="number" class="form-control" required wire:model="business.floor_area">
                                </div>
                                <div class="mb-3">
                                    <label for="signage_area" class="form-label">Signage area</label>
                                    <input type="number" class="form-control" required wire:model="business.signage_area">
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
                        <h5 class="modal-title" id="deactivateModalLabel">Deactivate Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_deactivate({{$business['id']}},'deactivateModaltoggler')">
                            <div>Are you sure you want to deactivate this business?</div>
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
                        <h5 class="modal-title" id="activateModalLabel">Activate Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_activate({{$business['id']}},'activateModaltoggler')">
                            <div>Are you sure you want to activate this business?</div>
                            <button type="submit" class="btn btn-warning">Activate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

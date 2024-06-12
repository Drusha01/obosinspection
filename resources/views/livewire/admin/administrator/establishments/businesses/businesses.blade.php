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
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-between my-3">
                <div class="col-8">
                    <div class="row d-flex">
                        <div class="col-lg-6 col-md-12">
                            <input type="text" name="" id=""class="form-control" placeholder="Search ... ">
                        </div>
                       <div class="col-lg-2 col-md-4 col-sm-4">
                            <select name="" id="rows" class="form-select">
                                <option selected value="10">Name</option>
                                <option value="30">Contact #</option>
                                <option value="30">Email</option>
                            </select>
                       </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-end ">
                    <button type="button" class="btn btn-primary"  wire:click="add('addModaltoggler')">
                        Add Business
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
                                @elseif($filter_value['name'] == 'History')
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
                                    @elseif($filter_value['name'] == 'Owner' && $filter_value['active'])
                                        <td >{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</td>
                                    @elseif($filter_value['name'] == 'History' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-secondary" wire:click="viewHistory({{$value->id}},'histModaltoggler')">
                                                History
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
                                    @elseif ($filter_value['name'] == 'Image'  && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <a href="{{asset('storage/content/business/'.$value->{$filter_value['column_name']})}}" target="blank">
                                                <img class="img-fluid"src="{{asset('storage/content/business/'.$value->{$filter_value['column_name']})}}" alt="" style="max-height:50px;max-width:50px; ">
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
        <div class="container d-flex justify-content-center">
            {{$table_data->links()}}
        </div>

        <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" id="editModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#deactivateModal" id="deactivateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#activateModal" id="activateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#histModal" id="histModaltoggler" style="display:none;"></button>
        

        <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add('addModaltoggler')">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="img" class="form-label">Image</label>
                                        <input type="file" id="img" class="form-control" wire:model="business.img_url" accept="image/png, image/jpeg" placeholder="Select Image">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                        <input type="text" required id="business_name"class="form-control" wire:model="business.name" placeholder="Enter Business name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="business_owner" class="form-label">Owner <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="business_owner" aria-label="Select Owner" wire:model="business.owner_id" placeholder="Select owner">
                                                <option value="">Select Owner</option>
                                                @foreach($owners as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="business_category" class="form-label">Business Category <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="business_category" aria-label="Select Business Category" wire:model="business.business_category_id" placeholder="Select Business Category">
                                                <option value="">Select Business Category</option>
                                                @foreach($business_category as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="business_type" class="form-label">Business type <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="business_type" aria-label="Select Business Type" wire:model="business.business_type_id" placeholder="Select Business type">
                                                <option value="">Select Business Type</option>
                                                @foreach($business_types as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="occu_class" class="form-label">Occupation Classification <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="occu_class" aria-label="Select Occupation Classification" wire:model="business.occupancy_classification_id" placeholder="Select Occupation Classification">
                                                <option value="">Select Occupation Classification</option>
                                                @foreach($occupancy_classifications as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->character_of_occupancy. ' ('.$value->character_of_occupancy_group.')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <label for="street_address" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="street_add" wire:model="business.street_address" placeholder="Enter Street address">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="brgy_address" class="form-label">Barangay <span class="text-danger">*</span></label>
                                            <select class="form-select"  required id="brgy_address" aria-label="Select Barangay" wire:model="business.brgy_id" placeholder="Select Barangay address">
                                                <option value="">Select Barangay</option>
                                                @foreach($brgy as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="contact_number" class="form-label">Contact number <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" required wire:model="business.contact_number" placeholder="Enter Contact number">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" required wire:model="business.email" placeholder="Enter email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="floor_area" class="form-label">Floor area <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" required wire:model="business.floor_area" placeholder="Floor area">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="signage_area" class="form-label">Signage area <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" required wire:model="business.signage_area" placeholder="Enter signage area">
                                        </div>
                                    </div>
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
        </div>

        <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_edit({{$business['id']}},'editModaltoggler')">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="img" class="form-label">Image</label>
                                        <input type="file" id="img" class="form-control" wire:model="business.img_url" accept="image/png, image/jpeg" placeholder="Select Image">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="business_name" class="form-label">Business Name <span class="text-danger">*</span></label>
                                        <input type="text" required id="business_name"class="form-control" wire:model="business.name" placeholder="Enter Business name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="business_owner" class="form-label">Owner <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="business_owner" aria-label="Select Owner" wire:model="business.owner_id" placeholder="Select owner">
                                                <option value="">Select Owner</option>
                                                @foreach($owners as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="business_category" class="form-label">Business Category <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="business_category" aria-label="Select Business Category" wire:model="business.business_category_id" placeholder="Select Business Category">
                                                <option value="">Select Business Category</option>
                                                @foreach($business_category as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="business_type" class="form-label">Business type <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="business_type" aria-label="Select Business Type" wire:model="business.business_type_id" placeholder="Select Business type">
                                                <option value="">Select Business Type</option>
                                                @foreach($business_types as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="occu_class" class="form-label">Occupation Classification <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" required id="occu_class" aria-label="Select Occupation Classification" wire:model="business.occupancy_classification_id" placeholder="Select Occupation Classification">
                                                <option value="">Select Occupation Classification</option>
                                                @foreach($occupancy_classifications as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->character_of_occupancy. ' ('.$value->character_of_occupancy_group.')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <label for="street_address" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="street_add" wire:model="business.street_address" placeholder="Enter Street address">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="brgy_address" class="form-label">Barangay <span class="text-danger">*</span></label>
                                            <select class="form-select"  required id="brgy_address" aria-label="Select Barangay" wire:model="business.brgy_id" placeholder="Select Barangay address">
                                                <option value="">Select Barangay</option>
                                                @foreach($brgy as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="contact_number" class="form-label">Contact number <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" required wire:model="business.contact_number" placeholder="Enter Contact number">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" required wire:model="business.email" placeholder="Enter email">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="floor_area" class="form-label">Floor area <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" required wire:model="business.floor_area" placeholder="Floor area">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="signage_area" class="form-label">Signage area <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" required wire:model="business.signage_area" placeholder="Enter signage area">
                                        </div>
                                    </div>
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

        <div wire:ignore.self class="modal fade" id="histModal" tabindex="-1" aria-labelledby="histModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="histModalLabel"> Inspection History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                    <tr>
                                        @foreach($histfilter as $filter_key => $filter_value)
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
                                    @forelse($history as $key => $value)
                                        <tr>
                                            @foreach($histfilter as $filter_key => $filter_value)
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
                    <form wire:submit.prevent="save_deactivate({{$business['id']}},'deactivateModaltoggler')">
                        <div class="modal-body">
                            <div>Are you sure you want to deactivate this business?</div>
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
                        <h5 class="modal-title" id="activateModalLabel">Activate Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_activate({{$business['id']}},'activateModaltoggler')">
                        <div class="modal-body">
                            <div>Are you sure you want to activate this business?</div>
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

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
            <!-- Search bar and Add button -->
            <div class="row justify-content-between my-3">
                <div class="col-8">
                    <div class="row d-flex">
                        <div class="col-lg-6 col-md-12">
                            <input type="text" name="" id=""class="form-control" wire:model.live.debounce.500ms="search.search"placeholder="Search inspector name ... ">
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-end">
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
                <table class="table table-striped table-hover" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            @foreach($table_filter['filter'] as $filter_key => $filter_value)
                                @if($filter_value['active'])
                                    @if($filter_value['name'] == 'Action')
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Category Role')
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
                                    @elseif ($filter_value['name'] == 'ORL Category Role'  && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-primary" wire:click="view_item_category_role(1,{{$value->person_id}},'ItemCatRoleModaltoggler')">
                                                Item Role
                                            </button>
                                            <button class="btn btn-outline-primary" wire:click="view_bss_category_role(1,{{$value->person_id}},'BSSCatRoleModaltoggler')">
                                                BSS Role
                                            </button>
                                            <button class="btn btn-outline-primary" wire:click="view_violation_category_role(1,{{$value->person_id}},'VioCatRoleModaltoggler')">
                                                Violation Role
                                            </button>
                                        </td>
                                    @elseif ($filter_value['name'] == 'NORL Category Role'  && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-outline-primary" wire:click="view_item_category_role(2,{{$value->person_id}},'ItemCatRoleModaltoggler')">
                                                Item Role
                                            </button>
                                            <button class="btn btn-outline-primary" wire:click="view_bss_category_role(2,{{$value->person_id}},'BSSCatRoleModaltoggler')">
                                                BSS Role
                                            </button>
                                            <button class="btn btn-outline-primary" wire:click="view_violation_category_role(2,{{$value->person_id}},'VioCatRoleModaltoggler')">
                                                Violation Role
                                            </button>
                                        </td>
                                    @elseif ($filter_value['name'] == 'E-Signature'  && $filter_value['active'])
                                    <td class="text-center align-middle">
                                        @if($value->{$filter_value['column_name']})
                                            <a href="{{asset('storage/content/signature/'.$value->{$filter_value['column_name']})}}" target="blank">
                                                <img class="img-fluid"src="{{asset('storage/content/signature/'.$value->{$filter_value['column_name']})}}" alt="" style="max-height:50px;max-width:50px; ">
                                            </a>
                                        @else
                                            No E-Signature
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
        <button type="button" data-bs-toggle="modal" data-bs-target="#VioCatRoleModal" id="VioCatRoleModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#ItemCatRoleModal" id="ItemCatRoleModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#BSSCatRoleModal" id="BSSCatRoleModaltoggler" style="display:none;"></button>

        <div wire:ignore.self class="modal fade" id="VioCatRoleModal" tabindex="-1" aria-labelledby="VioCatRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="VioCatRoleModalLabel">View Violation Category Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="inspectors" class="form-label">Violation Category Role <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <div class="mb-3">
                                    <select class="form-select" id="inspectors" aria-label="Select Member" required wire:model="category_role.category_id">
                                        <option value="">Select Category</option>
                                        @foreach($violation_category as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 mx-3" >
                                <button class="btn btn-primary" type="button" wire:click="add_violation_category_role({{$category_role['type_id']}})">Add</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                    <tr>
                                        <th class="align-middle text-center"></th>
                                        <th class="align-middle"> Category</th>
                                        <th class="align-middle text-center">Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inspector_violation_category as $key => $value)
                                        <tr>
                                            <td class="align-middle text-center">{{$key+1}}</td>
                                            <td class="mx-2 align-middle">
                                                {{$value->name}}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="delete_violation_category_role({{$value->id}})">
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

        <div wire:ignore.self class="modal fade" id="ItemCatRoleModal" tabindex="-1" aria-labelledby="ItemCatRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ItemCatRoleModalLabel">View Item Category Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="inspectors" class="form-label">Item Category Role <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <div class="mb-3">
                                    <select class="form-select" id="inspectors" aria-label="Select Member" required wire:model="category_role.category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 mx-3" >
                                <button class="btn btn-primary" type="button" wire:click="add_item_category_role({{$category_role['type_id']}})">Add</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                    <tr>
                                        <th class="align-middle text-center"></th>
                                        <th class="align-middle"> Category</th>
                                        <th class="align-middle text-center">Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inspector_item_category as $key => $value)
                                        <tr>
                                            <td class="align-middle text-center">{{$key+1}}</td>
                                            <td class="mx-2 align-middle">
                                                {{$value->name}}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="delete_item_category_role({{$value->id}})">
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

        <div wire:ignore.self class="modal fade" id="BSSCatRoleModal" tabindex="-1" aria-labelledby="BSSCatRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ItemCatRoleModalLabel">View BSS Category Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="inspectors" class="form-label">BSS Category Role <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <div class="mb-3">
                                    <select class="form-select" id="inspectors" aria-label="Select Member" required wire:model="category_role.category_id">
                                        <option value="">Select Category</option>
                                        @foreach($bss_category as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3 mx-3" >
                                <button class="btn btn-primary" type="button" wire:click="add_bss_category_role({{$category_role['type_id']}})">Add</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                                <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                    <tr>
                                        <th class="align-middle text-center"></th>
                                        <th class="align-middle"> Category</th>
                                        <th class="align-middle text-center">Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inspector_bss_category as $key => $value)
                                        <tr>
                                            <td class="align-middle text-center">{{$key+1}}</td>
                                            <td class="mx-2 align-middle">
                                                {{$value->name}}
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-danger" type="button" wire:click="delete_bss_category_role({{$value->id}})">
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
        
        <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_add('addModaltoggler')">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" wire:model="person.img_url">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">E-signature</label>
                                <input type="file" class="form-control" wire:model="person.signature">
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required wire:model="person.username" placeholder="Enter username">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="brgy_id" class="form-label">Work Role <span class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Select Barangay" required wire:model="person.work_role_id">
                                            <option value="">Select Role</option>
                                            @foreach($work_roles as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required wire:model="person.first_name" placeholder="Enter firstname">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" wire:model="person.middle_name" placeholder="Enter middlename">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required wire:model="person.last_name" placeholder="Enter lastname">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control" wire:model="person.suffix" placeholder="Enter suffix">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required wire:model="person.contact_number" placeholder="Enter contact #">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="brgy_id" class="form-label">Inspector Role <span class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Select Barangay" required wire:model="person.annual_certificate_category_id">
                                            <option value="">Select Inspector Role</option>
                                            @foreach($annual_certificate_categories as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" required wire:model="person.password" placeholder="Enter password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="cpassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control"required wire:model="person.cpassword" placeholder="Retype password">
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

                                                                  
        <div wire:ignore.self class="modal fade" id="recoverModal" tabindex="-1" aria-labelledby="recoverModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="recoverModalLabel">Recover Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_recover_password({{$person['id']}},'recoverModaltoggler')">
                        <div class="modal-body">
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_edit({{$person['id']}},'editModaltoggler')">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" wire:model="person.img_url">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">E-signature</label>
                                <input type="file" class="form-control" wire:model="person.signature">
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required  disabled wire:model="person.username" placeholder="Enter username">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label for="brgy_id" class="form-label">Work Role <span class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Select Barangay" required wire:model="person.work_role_id">
                                            <option value="">Select Role</option>
                                            @foreach($work_roles as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required wire:model="person.first_name" placeholder="Enter firstname">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" wire:model="person.middle_name" placeholder="Enter middlename">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required wire:model="person.last_name" placeholder="Enter lastname">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <input type="text" class="form-control" wire:model="person.suffix" placeholder="Enter suffix">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" required wire:model="person.contact_number" placeholder="Enter contact #">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="brgy_id" class="form-label">Inspector Role <span class="text-danger">*</span></label>
                                        <select class="form-select" aria-label="Select Barangay" required wire:model="person.annual_certificate_category_id">
                                            <option value="">Select Inspector Role</option>
                                            @foreach($annual_certificate_categories as $key => $value)
                                                <option value="{{$value->id}}">{{$value->name}}</option>
                                            @endforeach
                                        </select>
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

        <!-- Deactivate Modal -->
        <div wire:ignore.self class="modal fade" id="deactivateModal" tabindex="-1" aria-labelledby="deactivateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deactivateModalLabel">Deactivate </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_deactivate({{$person['id']}},'deactivateModaltoggler')">
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
                        <h5 class="modal-title" id="activateModalLabel">Activate </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save_activate({{$person['id']}},'activateModaltoggler')">
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
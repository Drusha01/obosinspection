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
                            <input type="text" name="" id=""class="form-control" wire:model.live.debounce.500ms="search.search"placeholder="Search ... ">
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    @if(Request()->route()->getPrefix() == 'administrator/request')
                        <button class="btn btn-primary mr-2" wire:click="request_list_modal('requestCategoryListModaltoggler')">
                            Request Category Lists
                        </button>
                    @endif
                    <button type="button" class="btn btn-primary mr-2"  wire:click="generate_request('requestPDFModaltoggler')">
                        Request PDF
                    </button>
                    <button type="button" class="btn btn-primary"  wire:click="generate_request('requestModaltoggler')">
                        Request
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
            <!-- Table -->
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
                                    @elseif($filter_value['name'] == 'Request Range' && $filter_value['active'])
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
                                    @elseif($filter_value['name'] == 'Request Range' && $filter_value['active'])
                                        <td class="align-middle text-center">
                                            {{date_format(date_create($value->request_date),"M d, Y")}} - {{date_format(date_create($value->expiration_date),"M d, Y") }}
                                        </td>
                                    @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <button class="btn btn-danger" wire:click="edit({{$value->id}},'deleteModaltoggler')">
                                                Delete
                                            </button>
                                            @if(Request()->route()->getPrefix() == 'administrator/request')
                                                <a class="btn btn-outline-primary" target="_blank" href="/administrator/request/generate-request-pdf/{{$value->business_id}}/{{$value->request_date}}/{{$value->expiration_date}}">
                                                    Generate Letter
                                                </a>
                                            @elseif(Request()->route()->getPrefix() == 'inspector-team-leader/request')
                                                <a class="btn btn-outline-primary" target="_blank" href="/inspector-team-leader/request/generate-request-pdf/{{$value->business_id}}/{{$value->request_date}}/{{$value->expiration_date}}">
                                                    Generate Letter
                                                </a>
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
            <div class="container d-flex justify-content-center">
                {{$table_data->links()}}
            </div>

            <button type="button" data-bs-toggle="modal" data-bs-target="#requestModal" id="requestModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#requestPDFModal" id="requestPDFModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteModal" id="deleteModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#requestCategoryListModal" id="requestCategoryListModaltoggler" style="display:none;"></button>

            
            <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="save_delete({{$request['id']}},'deleteModaltoggler')">
                            <div class="modal-body">
                                <div>Are you sure you want to delete this?</div>
                                <div class="text-danger">Once deleted, it cannot be undone</div>
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
            <div wire:ignore.self class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="requestModalLabel">Request Inspection </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="send_request('requestModaltoggler')">
                            <div class="modal-body">
                                <div class="row mb-3">
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
                                    <div class="col-12">
                                        <label for="select_business">Select Business <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" id="select_business" aria-label="Select Member" required wire:model="request.business_id">
                                                    <option value="">Select Business</option>
                                                @foreach($business as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name.' ('.$value->business_type_name.') brgy: '.$value->barangay}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="col-12">
                                        <label for="duratiion">Duration <span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="30" class="form-select" required value="1" wire:model="request.duration" wire:change="update_expiration_date()" >
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" required disabled wire:model.live="request.request_date" required min="" wire:change="update_duration()">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="end_date" required wire:model.live="request.expiration_date" required wire:change="update_duration()">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                @if(isset($request['id']))
                                    @if($request['id'] != -1)
                                        <button type="submit" class="btn btn-warning">Proceed</button>
                                    @else 
                                        <button type="submit" disabled class="btn btn-warning">Proceed</button>
                                    @endif
                                @else 
                                    <button type="submit" class="btn btn-primary">Request</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="requestPDFModal" tabindex="-1" aria-labelledby="requestPDFModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="requestPDFModalLabel">Request PDF Inspection </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="generate_request_pdf('requestPDFModaltoggler')">
                            <div class="modal-body">
                                <div class="row mb-3">
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
                                    <div class="col-12">
                                        <label for="select_business">Select Business <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" id="select_business" aria-label="Select Member" required wire:model="request.business_id">
                                                    <option value="">Select Business</option>
                                                @foreach($business as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name.' ('.$value->business_type_name.') brgy: '.$value->barangay}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="col-12">
                                        <label for="duratiion">Duration <span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="30" class="form-select" required value="1" wire:model="request.duration" wire:change="update_expiration_date()" >
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" required disabled wire:model.live="request.request_date" required min="" wire:change="update_duration()">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="end_date" required wire:model.live="request.expiration_date" required wire:change="update_duration()">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                @if(isset($request['id']))
                                    @if($request['id'] != -1)
                                        <button type="submit" class="btn btn-warning">Proceed</button>
                                    @else 
                                        <button type="submit" disabled class="btn btn-warning">Proceed</button>
                                    @endif
                                @else 
                                    <button type="submit" class="btn btn-primary">Request</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="requestCategoryListModal" tabindex="-1" aria-labelledby="requestCategoryListModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="requestCategoryListModalLabel">Request Category Lists </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-8">
                                <form wire:submit.prevent="add_category_to_request_list()">
                                        <div class="mb-3">
                                            <select class="form-select" aria-label="Select Member" required wire:model="business_category.id">
                                                <option value="">Select Business Category</option>
                                                @foreach($business_category_list as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3 mx-3" >
                                        <button class="btn btn-primary" type="submit">Add</button>
                                    </div>
                                </div>
                            </form>
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
                                        @foreach($request_lists as $key => $value)
                                            <tr>
                                                <td class="align-middle text-center">{{$key+1}}</td>
                                                <td class="mx-2 align-middle">
                                                    {{$value->name}}
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button class="btn btn-danger" type="button" wire:click="delete_request_category({{$value->id}})">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

        </div>    
    </div>
</div>
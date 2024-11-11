<div>
    <div class="content">
        <div class="container-fluid">
            <div class="row d-flex mt-4 mb-4">
                <div class="col-lg-4 col-md-4">
                    <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class=" d-flex ">
                        <span for="rows" class="align-middle mt-2">Status</span>
                            <select name="" id=""  class="form-select" wire:model.live.debounce="search.status_id">
                                <option value="">Select All</option>
                                @foreach($status as $key => $value)
                                    <option value="{{$value['id']}}">{{$value['name']}}</option>
                                @endforeach
                            </select>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class=" d-flex ">
                        <span for="rows" class="align-middle mt-2">category</span>
                            <select name="" id=""  class="form-select" wire:model.live.debounce="search.business_category_id">
                                <option value="">Select category</option>
                                @foreach($business_categories as $key => $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
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
                    <button class="btn btn-primary mr-2" wire:click="request_list_modal('requestCategoryListModaltoggler')">
                        Notification Category Lists
                    </button>
                    <button type="button" class="btn btn-primary"  wire:click="generate_request('requestModaltoggler')">
                        Notify
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
                                    @elseif($filter_value['name'] == 'Notification Range' && $filter_value['active'])
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Response Date' && $filter_value['active'])
                                        <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                    @elseif($filter_value['name'] == 'Schedule Date' && $filter_value['active'])
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
                                    @elseif($filter_value['name'] == 'Notification Range' && $filter_value['active'])
                                        <td class="align-middle text-center">
                                            {{date_format(date_create($value->request_date),"M d, Y")}} - {{date_format(date_create($value->expiration_date),"M d, Y") }}
                                        </td>
                                    @elseif($filter_value['name'] == 'Schedule Date' && $filter_value['active'])
                                        <td class="align-middle text-center">
                                            {{date_format(date_create($value->{$filter_value['column_name']}),"M d, Y")}} 
                                        </td>
                                    @elseif($filter_value['name'] == 'Response Date' && $filter_value['active'])
                                        <td class="align-middle text-center">
                                            @switch($value->status_name)
                                                @case("Pending")
                                                    @break
                                                @case("Accepted")
                                                    {{date_format(date_create($value->{$filter_value['column_name']}),"M d, Y")}} 
                                                    @break
                                                @case("Declined")
                                                    {{date_format(date_create($value->{$filter_value['column_name']}),"M d, Y")}} 
                                                    @break
                                                @case("Deleted")
                                                    @break
                                                @case("No response")
                                                    dsfasdfsa
                                                    @break
                                                @case("Completed")
                                                    {{date_format(date_create($value->{$filter_value['column_name']}),"M d, Y")}} 
                                                    @break
                                            @endswitch
                                        </td>
                                    @elseif($filter_value['name'] == 'Statement' && $filter_value['active'])
                                        <td class="align-middle">
                                            @switch($value->status_name)
                                                @case("Pending")
                                                    @break
                                                @case("Accepted")
                                                    {{ 'Accepted' }} 
                                                    @break
                                                @case("Declined")
                                                    {{ $value->{$filter_value['column_name']} }} 
                                                    @break
                                                @case("Deleted")
                                                    @break
                                                @case("No response")
                                                    dsfasdfsa
                                                    @break
                                                @case("Completed")
                                                    {{ 'Accepted' }} 
                                                    @break
                                            @endswitch
                                        </td>
                                    @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                        <td class="text-end align-middle">
                                            @switch($value->status_name)
                                                @case("Pending")
                                                    @if($value->request_type == 0)
                                                        <a class="btn btn-primary" target="_blank" wire:click="add({{$value->id}},'addModaltoggler')">
                                                            <i class="bi bi-calendar-plus"></i>
                                                        </a>
                                                        <button class="btn btn-outline-secondary" wire:click="edit({{$value->id}},'declineModaltoggler')">
                                                            <i class="bi bi-calendar2-x"></i>
                                                        </button>
                                                    @endif
                                                    <a class="btn btn-outline-primary" target="_blank" href="/inspector-team-leader/request/generate-request-pdf/{{$value->hash}}/{{$value->request_date}}/{{$value->expiration_date}}">
                                                        <i class="bi bi-file-earmark-font"></i>
                                                    </a>
                                                    <!-- <button class="btn btn-danger" wire:click="edit({{$value->id}},'deleteModaltoggler')">
                                                        <i class="bi bi-trash3"></i>
                                                    </button> -->
                                                    @break
                                                @case("Accepted")
                                                    <a class="btn btn-primary" target="_blank"  wire:click="add({{$value->id}},'addModaltoggler')">
                                                        <i class="bi bi-calendar-plus"></i>
                                                    </a>
                                                    <!-- <button class="btn btn-danger" wire:click="edit({{$value->id}},'deleteModaltoggler')">
                                                        <i class="bi bi-trash3"></i>
                                                    </button> -->
                                                    @break
                                                @case("Declined")
                                                    <a class="btn btn-primary" target="_blank" wire:click="reissue_request({{$value->id}},'reIssueRequestModaltoggler')">
                                                        <i class="bi bi-send-check"></i>
                                                    </a>
                                                    <!-- <button class="btn btn-danger" wire:click="edit({{$value->id}},'deleteModaltoggler')">
                                                        <i class="bi bi-trash3"></i>
                                                    </button> -->
                                                    @break
                                                @case("Deleted")
                                                    @break
                                                @case("No response")
                                                    <a class="btn btn-primary" target="_blank" wire:click="reissue_request({{$value->id}},'reIssueRequestModaltoggler')">
                                                        <i class="bi bi-send-check"></i>
                                                    </a>
                                                    <a class="btn btn-outline-primary" target="_blank" href="/inspector-team-leader/request/generate-request-pdf/{{$value->hash}}/{{$value->request_date}}/{{$value->expiration_date}}">
                                                        <i class="bi bi-file-earmark-font"></i>
                                                    </a>
                                                    <!-- <button class="btn btn-danger" wire:click="edit({{$value->id}},'deleteModaltoggler')">
                                                        <i class="bi bi-trash3"></i>
                                                    </button> -->
                                                    @break
                                                @case("Completed")
                                                    @break
                                            @endswitch
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
            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#reIssueRequestModal" id="reIssueRequestModaltoggler" style="display:none;"></button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#declineModal" id="declineModaltoggler" style="display:none;"></button>
           
            
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

            <div wire:ignore.self class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="declineModalLabel">Decline</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="save_decline({{$request['id']}},'declineModaltoggler')">
                            <div class="modal-body">
                                <div>Are you sure you want to decline this?</div>
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                <button type="submit" class="btn btn-outline-secondary">Decline</button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
            
            <div wire:ignore.self class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="requestModalLabel">Notification Inspection </h5>
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
                                    <div class="col-8">
                                        <label for="select_business">Select Business <span class="text-danger">*</span></label>
                                        <div class="mb-3">
                                            <select class="form-select" id="select_business" aria-label="Select Member" required wire:model.live="request.business_id">
                                                    <option value="">Select Business</option>
                                                @foreach($business as $key => $value)
                                                    <option value="{{$value->id}}">{{$value->name.' ('.$value->business_type_name.')'}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="select_business">Filter Business Category</label>
                                        <div class="mb-3">
                                            <select class="form-select" id="select_business" aria-label="Select Business Category" wire:change="update_business_id()" wire:model.live="modal.business_category_id">
                                                    <option value="">Select Business Category</option>
                                                    @foreach($business_categories as $key => $value)
                                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="mb-3">
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" value="1" id="email-radio" wire:model.live="request.request_type">
                                            <label class="form-check-label" for="email-radio">
                                                Email
                                            </label>
                                        </div>
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" value="0" id="email-pdf" wire:model.live="request.request_type">
                                            <label class="form-check-label" for="pdf-radio">
                                                PDF
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="request_date">Schedule date<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="request_date" wire:model.live="request.schedule_date" required min="{{$request['schedule_date']}}">
                                    </div>
                                    @if($request['request_type'] == 1)
                                    <label for="" class="form-label text-dark">Response Duration</label>
                                    <div class="col-lg-6">
                                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" required disabled wire:model.live="request.request_date" required min="">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="end_date" required wire:model.live="request.expiration_date" required >
                                    </div>
                                    @endif
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
                                    <button type="submit" class="btn btn-primary">Notify</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div wire:ignore.self class="modal fade" id="reIssueRequestModal" tabindex="-1" aria-labelledby="reIssueRequestModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reIssueRequestModalLabel">Notification Inspection </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="send_request('reIssueRequestModaltoggler')">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        @if(isset($request['business']))
                                            <div class="row">
                                                <div class="col-12 text-dark">
                                                    <p>Business name: {{$request['business']->name.' ('.$request['business']->business_type_name.' )' }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="mb-3">
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" value="1" id="email-radio" wire:model.live="request.request_type">
                                            <label class="form-check-label" for="email-radio">
                                                Email
                                            </label>
                                        </div>
                                        <div class="form-check mx-2">
                                            <input class="form-check-input" type="radio" value="0" id="email-pdf" wire:model.live="request.request_type">
                                            <label class="form-check-label" for="pdf-radio">
                                                PDF
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="request_date">Schedule date<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="request_date" wire:model.live="request.schedule_date" required min="{{$request['schedule_date']}}">
                                    </div>
                                    @if($request['request_type'] == 1)
                                    <label for="" class="form-label text-dark">Response Duration</label>
                                    <div class="col-lg-6">
                                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="start_date" required disabled wire:model.live="request.request_date" required min="">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="end_date" required wire:model.live="request.expiration_date" required >
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                @if(isset($request['id']))
                                    @if($request['id'] != -1)
                                        <button type="submit" class="btn btn-primary">Notify</button>
                                    @else 
                                        <button type="submit" disabled class="btn btn-warning">Proceed</button>
                                    @endif
                                @else 
                                    <button type="submit" class="btn btn-primary">Notify</button>
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
                            <h5 class="modal-title" id="requestCategoryListModalLabel">Notification Category Lists </h5>
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
                                                        <i class="bi bi-trash3"></i>
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
                                @if(isset($inspection['business']))
                                    <div class="row">
                                        <div class="col-12 text-dark">
                                            <p>Business name: {{$inspection['business']->name.' ('.$inspection['business']->business_type_name.' )' }}</p>
                                        </div>
                                    </div>
                                @endif
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
                                                <th class="align-middle">Name</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inspection['inspector_leaders']  as $key =>$value)
                                                <tr>
                                                    <td class="align-middle">{{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' ( '.$value->work_role_name.' ) '.(isset($value->inspector_team) ? '( '.$value->inspector_team.' )' : '( Not assigend )')}}</td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-danger " wire:click="delete_team_leader({{$key}})">
                                                            <i class="bi bi-trash3"></i>
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
                                                        <button class="btn btn-danger " wire:click="delete_team_member({{$key}})">
                                                            <i class="bi bi-trash3"></i>
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
            

        </div>    
    </div>
</div>
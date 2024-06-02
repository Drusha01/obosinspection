<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800" >{{$title}}</h1>
            </div>
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center w-50">
                    <label for="search" class="form-label mb-0 mr-2">Search business name:</label>
                    <input type="text" id="search" class="form-control" placeholder="Enter Name" wire:model.live.debounce.650ms="search.business_name">
                </div>
                <div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            @foreach($filter as $filter_key => $filter_value)
                                @if($filter_value['name'] == 'Action')
                                    <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                                @elseif($filter_value['name'] == 'History')
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

        <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" id="editModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#deactivateModal" id="deactivateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#activateModal" id="activateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#histModal" id="histModaltoggler" style="display:none;"></button>
        

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

          



    </div>
</div>

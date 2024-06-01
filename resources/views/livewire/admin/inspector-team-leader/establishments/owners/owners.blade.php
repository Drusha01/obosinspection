<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
            </div>
            <!-- Search bar and Add button -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center w-50">
                    <label for="search" class="form-label mb-0 mr-2">Search:</label>
                    <input type="text" id="search" class="form-control" placeholder="Enter Name" wire:model="searchTerm">
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


    </div>
</div>

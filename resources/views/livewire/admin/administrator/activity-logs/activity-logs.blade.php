<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <div class="p-0 m-0" wire:click="add('addModaltoggler')">
                    
                </div>
            </div>
            <!-- Table -->
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
                        @forelse($table_data as $key => $value)
                            <tr>
                                @foreach($filter as $filter_key => $filter_value)
                                    @if($filter_value['name'] == '#' && $filter_value['active'])
                                        <th class="align-middle">{{($table_data->currentPage()-1)*$table_data->perPage()+$key+1 }}</th>
                                    @elseif($filter_value['name'] == 'Activity log' && $filter_value['active'])
                                        <td class="align-middle">
                                            {{$value->first_name.' '.$value->middle_name.' '.$value->last_name.' '.$value->suffix.' '.$value->log_details}}
                                        </td>
                                    @elseif($filter_value['name'] == 'Date time' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            {{date_format(date_create($value->date_created),"M d, Y h:i a")}}
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
    </div>
</div>

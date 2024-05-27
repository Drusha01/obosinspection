<div>
    <div class="row px-3">
        <div class="container mt-2 p-0 mx-0">
            <div class="d-sm-flex align-items-center justify-content-between mt-2 mb-2">
                <h1 class="h3 mb-0 p-0 ml-3 text-black" >{{$title}}</h1>
                <div class="p-0 m-0">

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        Add Administrator
                    </button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <tr>
                        @foreach($users_filter as $filter_key => $filter_value)
                            @if($filter_value['name'] == 'Action')
                                <th scope="col" class="text-center">{{$filter_value['name']}}</th>
                            @else 
                                <th scope="col">{{$filter_value['name']}}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($users_data as $key =>$value)
                        <tr>
                            @foreach($users_filter as $filter_key => $filter_value)
                                @if($filter_value['name'] == '#' && $filter_value['active'])
                                    <th class="align-middle">{{($users_data->currentPage()-1)*$users_data->perPage()+$key+1 }}</th>
                                @elseif($filter_value['name'] == 'Action' && $filter_value['active'])
                                    <td class="text-center  align-middle">
                                        <button class="btn btn-danger">
                                            Deactivate
                                        </button>
                                        <button class="btn btn-primary">
                                            Edit
                                        </button>
                                    </td>
                                @elseif ($filter_value['name'] == 'Image'  && $filter_value['active'])
                                    <td class="text-center align-middle">
                                        <a href="{{asset('storage/profile/'.$value->{$filter_value['column_name']})}}" target="blank">
                                            <img class="img-fluid"src="{{asset('storage/profile/'.$value->{$filter_value['column_name']})}}" alt="" style="max-height:50px;max-width:50px; ">
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
        
        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add New Business</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="role" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
                
    </div>
</div>
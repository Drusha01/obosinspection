<div>
    <div class="content">
        <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                    <h1 class="h3 mb-0 p-0  text-black" >{{$title}}</h1>
                    <div class="p-0 m-0" >
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal" wire:click="add_person('addModaltoggler')">
                            Add Inspector
                        </button>
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
        
        <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Inspector</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add_person('addModaltoggler')">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" wire:model="person.img_url">
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
                                        <input type="text" class="form-control" required wire:model="person.suffix">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="brgy_id" class="form-label">Barangay</label>
                                        <select class="form-select" aria-label="Select Barangay" wire:model="person.brgy_id">
                                            <option value="">Select Barangay</option>
                                            @foreach($brgy as $key => $value)
                                                <option value="{{$value->id}}">{{$value->brgyDesc}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number</label>
                                        <input type="number" class="form-control" required wire:model="person.contact_number">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" required wire:model="person.email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" wire:model="person.password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="cpassword" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" wire:model="person.cpassword">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <div class="p-0 m-0" wire:click="add('addModaltoggler')">
                    <button type="button" class="btn btn-primary">
                        Add Category
                    </button>
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
                                            <button class="btn btn-primary" wire:click="edit({{$value->id}},'editModaltoggler')">
                                                Edit
                                            </button>
                                        </td>
                                    @elseif ($filter_value['name'] == 'Image' && $filter_value['active'])
                                        <td class="text-center align-middle">
                                            <a href="{{asset('storage/content/category/'.$value->{$filter_value['column_name']})}}" target="_blank">
                                                <img class="img-fluid" src="{{asset('storage/content/category/'.$value->{$filter_value['column_name']})}}" alt="" style="max-height:50px; max-width:50px;">
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
        <!-- Hidden buttons for modal toggling -->
        <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" id="addModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" id="editModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#deactivateModal" id="deactivateModaltoggler" style="display:none;"></button>
        <button type="button" data-bs-toggle="modal" data-bs-target="#activateModal" id="activateModaltoggler" style="display:none;"></button>


        <!-- Modals -->
        <!-- Add Modal -->
        <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_add('addModaltoggler')">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" wire:model="category.img_url">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" required wire:model="category.name">
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_edit({{$category['id']}},'editModaltoggler')">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" wire:model="category.img_url">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" required wire:model="category.name">
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
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
                        <h5 class="modal-title" id="deactivateModalLabel">Deactivate Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_deactivate({{$category['id']}},'deactivateModaltoggler')">
                            <div>Are you sure you want to deactivate this category?</div>
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
                        <h5 class="modal-title" id="activateModalLabel">Activate Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save_activate({{$category['id']}},'activateModaltoggler')">
                            <div>Are you sure you want to activate this category?</div>
                            <button type="submit" class="btn btn-warning">Activate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

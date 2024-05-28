<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    Add Item
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">category</th>
                            <th scope="col">action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>John Doe</td>
                            <td>john.doe@example.com</td>
                            <td>Admin</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jane Smith</td>
                            <td>jane.smith@example.com</td>
                            <td>User</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Sam Johnson</td>
                            <td>sam.johnson@example.com</td>
                            <td>Moderator</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="addItem">
                                <div class="mb-3">
                                    <label for="itemImage" class="form-label">Upload Picture</label>
                                    <input type="file" class="form-control" id="itemImage" wire:model="item.image">
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" wire:model="item.category">
                                        <option value="mechanical">Mechanical</option>
                                        <option value="electrical">Electrical</option>
                                        <option value="electronics">Electronics</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="itemName" class="form-label">Item Name</label>
                                    <input type="text" class="form-control" id="itemName" wire:model="item.name">
                                </div>
                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
                
    </div>
</div>
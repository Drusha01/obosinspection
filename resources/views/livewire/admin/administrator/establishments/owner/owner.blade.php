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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        Add Owner
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
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
        </div>
    </div>

    <!-- Add Modal -->
    <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Owner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save_add">
                        <div class="mb-3">
                            <label for="picture" class="form-label">Upload Picture</label>
                            <input type="file" class="form-control" wire:model="user.picture">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" required wire:model="user.first_name" placeholder="Enter Firstname">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" wire:model="user.middle_name" placeholder="Enter Middlename"> 
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" required wire:model="user.last_name" placeholder="Enter Lastname">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <input type="text" class="form-control" wire:model="user.suffix" placeholder="Jr,Sr, etc.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" required wire:model="user.contact_number" placeholder="Enter Number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" required wire:model="user.email" placeholder="Enter Email">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

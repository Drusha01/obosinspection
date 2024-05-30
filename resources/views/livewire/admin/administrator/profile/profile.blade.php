<div>
    <div class="content">
        <div class="container-fluid mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h3 mb-0 text-gray-800">User Profile</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    Edit Profile <i class="bi bi-pencil-square"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="{{asset('storage/content/profile/'.$user_info['img_url'])}}" class="img-thumbnail rounded-circle" alt="Profile Picture">
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>First Name</th>
                                <td>{{$user_info['first_name']}}</td>
                            </tr>
                            <tr>
                                <th>Middle Name</th>
                                <td>{{$user_info['middle_name']}}</td>
                            </tr>
                            <tr>
                                <th>Family Name</th>
                                <td>{{$user_info['last_name']}}</td>
                            </tr>
                            <tr>
                                <th>Suffix</th>
                                <td>{{$user_info['suffix']}}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{$user_info['email']}}</td>
                            </tr>
                            <tr>
                                <th>Contact Number</th>
                                <td>{{$user_info['contact_number']}}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{$user_info['role_name']}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div wire:ignore.self class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save_edit()">
                        <div class="mb-3">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" required wire:model="user_info.first_name">
                        </div>
                        <div class="mb-3">
                            <label for="editMiddleName" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" wire:model="user_info.middle_name">
                        </div>
                        <div class="mb-3">
                            <label for="editFamilyName" class="form-label">Family Name</label>
                            <input type="text" class="form-control" wire:model="user_info.last_name">
                        </div>
                        <div class="mb-3">
                            <label for="editFamilyName" class="form-label">Suffix</label>
                            <input type="text" class="form-control" wire:model="user_info.suffix">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" disabled wire:model="user_info.email">
                        </div>
                        <div class="mb-3">
                            <label for="editContactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" wire:model="user_info.contact_number">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
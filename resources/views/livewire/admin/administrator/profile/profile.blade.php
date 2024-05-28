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
                    <img src="https://via.placeholder.com/150" class="img-thumbnail rounded-circle" alt="Profile Picture">
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>First Name</th>
                                <td>kezoru</td>
                            </tr>
                            <tr>
                                <th>Middle Name</th>
                                <td>ikari</td>
                            </tr>
                            <tr>
                                <th>Family Name</th>
                                <td>sai</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>kezoru@gmail.com</td>
                            </tr>
                            <tr>
                                <th>Contact Number</th>
                                <td>+1234567890</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>Team-leader</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/update-profile" method="post">
                        <div class="mb-3">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" name="firstName" value="John">
                        </div>
                        <div class="mb-3">
                            <label for="editMiddleName" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="editMiddleName" name="middleName" value="Doe">
                        </div>
                        <div class="mb-3">
                            <label for="editFamilyName" class="form-label">Family Name</label>
                            <input type="text" class="form-control" id="editFamilyName" name="familyName" value="Smith">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" value="john.doe@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="editContactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="editContactNumber" name="contactNumber" value="+1234567890">
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <input type="text" class="form-control" id="editRole" name="role" value="Admin">
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
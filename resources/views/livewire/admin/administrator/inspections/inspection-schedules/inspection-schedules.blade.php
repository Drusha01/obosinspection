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
                        Add Schedule
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                    <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Business Name</th>
                            <th scope="col">Inspection Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>Diwata pares</td>
                            <td>08/25/2024</td>
                            <td>
                                <button class="btn btn-danger">
                                    deactivate
                                </button>
                                <button class="btn btn-warning">
                                    Activate
                                </button>
                                <button class="btn btn-secondary">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        <tr>
                        <th scope="row">1</th>
                            <td>Diwata pares</td>
                            <td>08/25/2024</td>
                            <td>
                                <button class="btn btn-danger">
                                    deactivate
                                </button>
                                <button class="btn btn-warning">
                                    Activate
                                </button>
                                <button class="btn btn-secondary">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        <tr>
                        <th scope="row">1</th>
                            <td>Diwata pares</td>
                            <td>08/25/2024</td>
                            <td>
                                <button class="btn btn-danger">
                                    deactivate
                                </button>
                                <button class="btn btn-warning">
                                    Activate
                                </button>
                                <button class="btn btn-secondary">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Inspection Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form fields for inspection schedule -->
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="image">
                                </div>
                                <div class="col">
                                    <label for="name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col">
                                    <label for="inspection_date" class="form-label">Inspection Date</label>
                                    <input type="date" class="form-control" id="inspection_date" required>
                                </div>
                            </div>
                        
                            <!-- Inspector Information Section -->
                            <div class="row mb-3">
                                <div class="col">
                                    <label>Total Inspector</label>
                                    <input type="number" class="form-control" id="total_inspector">
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teamLeaderModal">
                                        Add Team Leader
                                    </button>
                                    <select id="selectedTeamLeaders" multiple style="display:none;"></select>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#teamMemberModal">
                                        Add Team Member
                                    </button>
                                    <select id="selectedTeamMembers" multiple style="display:none;"></select>
                                </div>
                            </div>
                            <!-- Display selected team leader -->
                            <div class="mb-3">
                                <label>Selected Team Leaders:</label>
                                <ul id="selectedTeamLeaders">
                                    <!-- Selected team leader will be displayed here -->
                                </ul>
                            </div>

                            <!-- Display selected team members -->
                            <div class="mb-3">
                                <label>Selected Team Members:</label>
                                <ul id="selectedTeamMembers">
                                    <!-- Selected team members will be displayed here -->
                                </ul>
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Leader Modal -->
            <div class="modal fade" id="teamLeaderModal" tabindex="-1" aria-labelledby="teamLeaderModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="teamLeaderModalLabel">Select Team Leaders</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form or content to select team leaders -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="teamLeader1">
                                <label class="form-check-label" for="teamLeader1">
                                    Team Leader 1
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="teamLeader2">
                                <label class="form-check-label" for="teamLeader2">
                                    Team Leader 2
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="teamLeader3">
                                <label class="form-check-label" for="teamLeader3">
                                    Team Leader 3
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Member Modal -->
            <div class="modal fade" id="teamMemberModal" tabindex="-1" aria-labelledby="teamMemberModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="teamMemberModalLabel">Select Team Members</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form or content to select team members -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="teamMember1">
                                <label class="form-check-label" for="teamMember1">
                                    Team Member 1
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="teamMember2">
                                <label class="form-check-label" for="teamMember2">
                                    Team Member 2
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="teamMember3">
                                <label class="form-check-label" for="teamMember3">
                                    Team Member 3
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
                
    </div>
</div>
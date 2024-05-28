<div>
    <div class="content">
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add 
                </button>
            </div>
            
            <!-- Inspector-group Table -->
            <div class="mt-4">
                <div class="table-responsive">
                    <table class="table table-striped table-hover bg-secondary" style="border-radius: 10px; overflow: hidden;">
                        <thead class="table-dark" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Team Lead</th>
                                <th scope="col">Member</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>John Doe (Team Lead)</td>
                                <td>Jane Smith, Sam Johnson</td>
                            </tr>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Inspector Group Modal -->
            <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Inspector Group</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form to add inspector group -->
                            <div class="mb-3">
                                <label for="teamLead" class="form-label">Team Lead</label>
                                <select class="form-select" id="teamLead">
                                    <option selected>Select Team Lead</option>
                                    <option>John Doe</option>
                                    <option>Jane Smith</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="members" class="form-label">Members</label>
                                <select multiple class="form-select" id="members">
                                    <option>Jane Smith</option>
                                    <option>Sam Johnson</option>
                                </select>
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

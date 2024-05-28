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

            <!-- Add Inspection Schedule Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Add Inspection Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <!-- Modal Progress Bar -->
                            <div class="progress mb-4">
                                <div id="progressBar" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <!-- Step 1: Basic Information -->
                            <div id="step1">
                                <!-- Form  for inspection schedule -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Business Name</label>
                                    <select class="form-select" id="businessName" required>
                                        <option value="">Select Business Name</option>
                                        <option value="ABC Restaurant">ABC Restaurant</option>
                                        <option value="XYZ Store">XYZ Store</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="inspection_date" class="form-label">Inspection Date</label>
                                    <input type="date" class="form-control" id="inspection_date" required value="2024-06-01">
                                </div>
                            </div>
                            <!-- Step 2: Team Leaders -->
                            <div id="step2" style="display: none;">
                                <!-- Add Team Leader button with icon -->
                                <div class="input-group mb-3">
                                    <select class="form-select" id="teamLeaderSelect">
                                        <option value="">Select Team Leader</option>
                                        <option value="Team Leader 1">Team Leader 1</option>
                                        <option value="Team Leader 2">Team Leader 2</option>
                                        <option value="Team Leader 3">Team Leader 3</option>
                                    </select>
                                    <button class="btn btn-primary" type="button" id="addTeamLeaderBtn"><i class="bi bi-plus"></i></button>
                                </div>
                                <!-- Selected Team Leaders list -->
                                <ul id="selectedTeamLeaders" class="list-group ml-1">
                                    <li class="list-group-item">Team Leader 1</li>
                                    <li class="list-group-item">Team Leader 2</li>
                                </ul>
                            </div>
                            <!-- Step 3: Team Members -->
                            <div id="step3" style="display: none;">
                                <!-- Add Team Member button with icon -->
                                <div class="input-group mb-3">
                                    <select class="form-select" id="teamMemberSelect">
                                        <option value="">Select Team Member</option>
                                        <option value="Team Member 1">Team Member 1</option>
                                        <option value="Team Member 2">Team Member 2</option>
                                        <option value="Team Member 3">Team Member 3</option>
                                    </select>
                                    <button class="btn btn-primary" type="button" id="addTeamMemberBtn"><i class="bi bi-plus"></i></button>
                                </div>
                                <!-- Selected Team Members list -->
                                <ul id="selectedTeamMembers" class="list-group ml-1">
                                    <li class="list-group-item">Team Member 1</li>
                                    <li class="list-group-item">Team Member 2</li>
                                </ul>
                            </div>
                        </div>
                        <!-- Modal Footer -->
                        <div class="modal-footer">
                            <!-- Previous, Next, and Add buttons -->
                            <button type="button" id="prevButton" class="btn btn-secondary">Previous</button>
                            <button type="button" id="nextButton" class="btn btn-primary">Next</button>
                            <button type="button" id="addButton" class="btn btn-success" style="display: none;">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                //  modal steps
                $(document).ready(function () {
                    // variables for progress
                    var currentStep = 1;
                    var totalSteps = 3;

                    // Next button 
                    $("#nextButton").click(function () {
                        if (currentStep < totalSteps) {
                            currentStep++;
                            updateModal(currentStep);
                        }
                    });

                    // Previous button 
                    $("#prevButton").click(function () {
                        if (currentStep > 1) {
                            currentStep--;
                            updateModal(currentStep);
                        }
                    });

                    // Function to update the modal contents based on the step
                    function updateModal(step) {
                        // this hides all steps
                        $("#step1, #step2, #step3").hide();

                        // this Shows current step
                        $("#step" + step).show();

                        // Updates the progress bar
                        var progressBarValue = (step / totalSteps) * 100;
                        $("#progressBar").css("width", progressBarValue + "%").attr("aria-valuenow", progressBarValue);

                        // Enable/disable previous and next buttons based on step
                        if (step == 1) {
                            $("#prevButton").prop("disabled", true);
                        } else {
                            $("#prevButton").prop("disabled", false);
                        }

                        if (step == totalSteps) {
                            $("#nextButton").hide();
                            $("#addButton").show();
                        } else {
                            $("#nextButton").show();
                            $("#addButton").hide();
                        }
                    }

                    // Initialize modal with first step
                    updateModal(1);
                });

                //  for adding team leaders and members
                $(document).ready(function () {
                    // Add Team Leader button click event
                    $("#addTeamLeaderBtn").click(function () {
                        var selectedTeamLeader = $("#teamLeaderSelect").val();
                        if (selectedTeamLeader) {
                            $("#selectedTeamLeaders").append("<li class='list-group-item'>" + selectedTeamLeader + "</li>");
                        }
                    });

                    // Add Team Member button click event
                    $("#addTeamMemberBtn").click(function () {
                        var selectedTeamMember = $("#teamMemberSelect").val();
                        if (selectedTeamMember) {
                            $("#selectedTeamMembers").append("<li class='list-group-item'>" + selectedTeamMember + "</li>");
                        }
                    });
                });
            </script>

        </div>
                
    </div>
</div>
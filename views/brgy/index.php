<?php require_once 'controllers/BarangayController.php'; ?>
<!-- Barangay Management Content -->
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>Barangay <small>Management</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Settings 1</a>
                            <a class="dropdown-item" href="#">Settings 2</a>
                        </div>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br />
                <!-- Barangay Form -->
                <form class="form-label-left input_mask" id="BarangayForm">
                    <div class="form-group row">
                        <label class="col-form-label col-md-3 col-sm-3">Barangay Name</label>
                        <div class="col-md-9 col-sm-9">
                            <input id="barangayname" type="text" class="form-control">
                        </div>
                    </div>
                    <?php if ($_SESSION["user_data"]['city_id']==0): ?>
                       <div class="form-group row">
                        <label class="col-form-label col-md-3 col-sm-3">City</label>
                        <div class="col-md-9 col-sm-9">
                         <select id="cityId" class="form-control">
                            <!-- Options will be dynamically populated -->
                        </select>
                    </div>
                </div>
            <?php endif; ?>
            <div class="ln_solid"></div>
            <div class="form-group row">
                <div class="col-md-9 col-sm-9 offset-md-3">
                    <button class="btn btn-primary" type="reset">Reset</button>
                    <button type="submit" class="btn btn-success">Add Barangay</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
<div class="col-md-8">
    <div class="x_panel">
        <div class="x_title">
            <h2>Barangay <small>List</small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Settings 1</a>
                        <a class="dropdown-item" href="#">Settings 2</a>
                    </div>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br />
            <!-- Barangay List Table -->
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Barangay Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($barangays)): ?>
                        <?php foreach ($barangays as $barangay): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($barangay['name']); ?></td>
                                <td>
                                    <!-- Inside the table for each barangay -->
                                    <button type="button" class="btn btn-primary btn-sm" onclick="openBarangayModal('<?php echo htmlspecialchars($barangay['id']); ?>', '<?php echo htmlspecialchars($barangay['name']); ?>', '<?php echo htmlspecialchars($barangay['city_id']); ?>')">Update</button>
                                    <?php if (in_array('Delete Barangay', $_SESSION['user_permissions'])): ?>
                                       <button type="button" class="btn btn-danger btn-sm" onclick="deleteBarangay('<?php echo htmlspecialchars($barangay['id']); ?>')">Delete</button>
                                   <?php endif; ?>
                               </td>
                           </tr>
                       <?php endforeach; ?>
                   <?php endif; ?>
               </tbody>
           </table>
       </div>
   </div>
</div>
</div>
<!-- Update Barangay Modal -->
<div class="modal fade" id="updateBarangayModal" tabindex="-1" role="dialog" aria-labelledby="updateBarangayModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateBarangayModalLabel">Update Barangay</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateBarangayForm">
                    <input type="hidden" id="updateBarangayId" name="updateBarangayId"> <!-- Hidden field for Barangay ID -->
                    <div class="form-group">
                        <label for="updateBarangayName">Barangay Name</label>
                        <input type="text" class="form-control" id="updateBarangayName" placeholder="Enter barangay name">
                    </div>
                    <div class="form-group">
                        <label for="updateCityId">City</label>
                        <select id="updateCityId" class="form-control">
                            <!-- Populate with cities dynamically -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Barangay</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- JavaScript to Handle Form Submission and AJAX Requests -->
<script type="text/javascript">
    // Handle form submission for adding a barangay
    $(document).on("submit", "#BarangayForm", function(e) {
        e.preventDefault();
        var barangayname = $('#barangayname').val();
        var cityId = $('#cityId').val();
        var action = 'store';
        if (barangayname === '' || cityId === '') {
            alert('Please enter Barangay Name and select City');
            return;
        }
        $.ajax({
            url: 'controllers/BarangayController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ barangayname: barangayname, cityId: cityId, action: action }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = 'index.php?page=brgym';
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
               window.location.href = 'index.php?page=brgym';
            }
        });
    });
    // Function to open the update modal and fill the form with current data
    function openBarangayModal(barangayId, barangayName, cityId) {
        $('#updateBarangayId').val(barangayId);  // Set the barangay ID in the hidden field
        $('#updateBarangayName').val(barangayName);  // Set the barangay name in the input field
        $('#updateCityId').val(cityId);  // Set the city ID in the select field
        $('#updateBarangayModal').modal('show');  // Show the modal
    }
    // Handle form submission to update the barangay
    $(document).on("submit", "#updateBarangayForm", function(e) {
        e.preventDefault();
        var barangayId = $('#updateBarangayId').val();
        var barangayName = $('#updateBarangayName').val().trim();
        var cityId = $('#updateCityId').val();
        if (barangayName === '' || cityId === '') {
            alert('Please enter the barangay name and select city');
            return;
        }
        $.ajax({
            url: 'controllers/BarangayController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ barangayId: barangayId, barangayname: barangayName, cityId: cityId, action: 'update' }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#updateBarangayModal').modal('hide');  // Hide the modal on success
                    window.location.href = 'index.php?page=brgym';  // Reload the page
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while updating the barangay.');
            }
        });
    });
    // Function to delete a barangay
    function deleteBarangay(barangayId) {
        if (confirm('Are you sure you want to delete this barangay?')) {
            $.ajax({
                url: 'controllers/BarangayController.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id: barangayId, action: 'delete' }),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'index.php?page=brgym';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred');
                }
            });
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // Fetch cities when the page loads
        $.ajax({
            url: 'controllers/fetchSubmitted.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'fetchCities' }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var citySelect = $('#cityId');
                    citySelect.empty();  // Clear existing options
                    $.each(response.cities, function(index, city) {
                        citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                } else {
                    alert(response.message);
                }
            },
        });
    });
</script>
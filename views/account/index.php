<?php require_once 'controllers/AccountController.php'; ?>
<!-- Account Management Content -->
<div class="row">
    <div class="col-md-4">
        <div class="x_panel">
            <div class="x_title">
                <h2>Account <small>Management</small></h2>
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
                <!-- Account Form -->
                <form class="form-label-left input_mask" id="AccountForm">
                   <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3">Email</label>
                    <div class="col-md-9 col-sm-9">
                        <input id="email" type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-md-3 col-sm-3">Password</label>
                    <div class="col-md-9 col-sm-9">
                        <input id="password" type="password" class="form-control">
                    </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group row">
                    <div class="col-md-9 col-sm-9 offset-md-3">
                        <button class="btn btn-primary" type="reset">Reset</button>
                        <button type="submit" class="btn btn-success">Add Account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-8">
    <div class="x_panel">
        <div class="x_title">
            <h2>Account <small>List</small></h2>
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
            <!-- Account List Table -->
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($accounts)): ?>
                        <?php foreach ($accounts as $account): ?>
                            <tr>
                                <td><?php echo $account['email']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" 
                                        onclick="openAccountModal('<?php echo $account['id']; ?>', '<?php echo $account['email']; ?>', '<?php echo $account['city_id']; ?>', '<?php echo $account['brgy_id']; ?>')">Update</button>
                                    <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="deleteAccount('<?php echo $account['id']; ?>')">Delete</button>
                                    <button class="btn btn-warning change-role-btn" 
                                        data-id="<?php echo $account['id']; ?>" 
                                        data-role="<?php echo $account['role_id']; ?>">Change Role</button>
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

<!-- Update Account Modal -->
<div class="modal fade" id="updateAccountModal" tabindex="-1" role="dialog" aria-labelledby="updateAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAccountModalLabel">Update Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateAccountForm">
                    <input type="hidden" id="updateAccountId" name="updateAccountId"> <!-- Hidden field for Account ID -->
                    <div class="form-group">
                        <label for="updateEmail">Email</label>
                        <input type="text" class="form-control" id="updateEmail" placeholder="Enter email">
                    </div>
                    <!-- City Selection Dropdown -->
                    <div class="form-group">
                        <label for="updateCity">City</label>
                        <select class="form-control" id="updateCity">
                            <option value="">Select City</option>
                            <!-- Dynamic options will be added here -->
                        </select>
                    </div>
                    <!-- Barangay Selection Dropdown -->
                    <div class="form-group">
                        <label for="updateBrgy">Barangay</label>
                        <select class="form-control" id="updateBrgy">
                            <option value="">Select Barangay</option>
                            <!-- Dynamic options will be added here -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Account</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div id="changeRoleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="changeRoleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change User Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
  <div class="modal-body">
    <form id="changeRoleForm">
      <input type="hidden" id="changeUserId" name="userId">
      <div class="form-group">
        <label for="editRole">Role</label>
        <select class="form-control" id="changeRole" name="role_id">
          <option value="1">User</option>
          <option value="2">Admin</option>
          <!-- Add more roles as needed -->
      </select>
  </div>
  <button type="submit" class="btn btn-primary">Change Role</button>
</form>
</div>
</div>
</div>
</div>

<!-- JavaScript -->
<script type="text/javascript">
    // Open the update modal with the current user's data (pre-fill form)
    function openAccountModal(accountId, email, cityId, brgyId) {
        $('#updateAccountId').val(accountId);  // Set the account ID in the hidden field
        $('#updateEmail').val(email);  // Set the email in the input field
        fetchCities(cityId, brgyId);  // Fetch cities and barangays, passing the user's current city and barangay
        $('#updateAccountModal').modal('show');  // Show the modal
    }

    // Fetch cities and barangays and set user's selected city and barangay
    function fetchCities(userCityId = null, userBrgyId = null) {
        $.ajax({
            url: 'controllers/AccountController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'citis' }),
            dataType: 'json',
            success: function(response) {
                let cityOptions = '<option value="">Select City</option>';
                $.each(response.cities, function(index, city) {
                    let selected = (userCityId && city.id == userCityId) ? 'selected' : '';
                    cityOptions += `<option value="${city.id}" ${selected}>${city.name}</option>`;
                });
                $('#updateCity').html(cityOptions);

                // Fetch barangays after cities are loaded
                if (userCityId) {
                    fetchBarangays(userCityId, userBrgyId);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('An error occurred while fetching cities.');
            }
        });
    }

    // Fetch barangays for a selected city and set user's selected barangay
    function fetchBarangays(cityId, userBrgyId = null) {
        $.ajax({
            url: 'controllers/AccountController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'brang', city_id: cityId }),
            dataType: 'json',
            success: function(response) {
                let brgyOptions = '<option value="">Select Barangay</option>';
                $.each(response.barangays, function(index, brgy) {
                    let selected = (userBrgyId && brgy.id == userBrgyId) ? 'selected' : '';
                    brgyOptions += `<option value="${brgy.id}" ${selected}>${brgy.name}</option>`;
                });
                $('#updateBrgy').html(brgyOptions);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                alert('An error occurred while fetching barangays.');
            }
        });
    }

    // Submit update form with city and barangay data
    $('#updateAccountForm').on('submit', function(e) {
        e.preventDefault();
        var accountId = $('#updateAccountId').val();
        var email = $('#updateEmail').val();
        var cityId = $('#updateCity').val();
        var brgyId = $('#updateBrgy').val();

        $.ajax({
            url: 'controllers/AccountController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ 
                accountId: accountId, 
                email: email, 
                city_id: cityId, 
                brgy_id: brgyId, 
                action: 'update' 
            }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#updateAccountModal').modal('hide');  // Hide modal on success
                    window.location.reload();  // Reload the page or redirect
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while updating the account.');
            }
        });
    });
</script>

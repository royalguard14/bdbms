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
                                    <button type="button" class="btn btn-primary btn-sm" onclick="openAccountModal('<?php echo $account['id']; ?>','<?php echo $account['email']; ?>')">Update</button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteAccount('<?php echo $account['id']; ?>')">Delete</button>
                                    <button class="btn btn-warning change-role-btn" data-id="<?php echo $account['id']; ?>" data-role="<?php echo $account['role_id']; ?>">Change Role</button>
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
<script type="text/javascript">
    $(document).ready(function() {
    // When the "Change Role" button is clicked
    $('.change-role-btn').click(function() {
        let userId = $(this).data('id');
        let currentRole = $(this).data('role');
        // Fill the modal with the current user ID and role
        $('#changeUserId').val(userId);
        // Fetch roles from the server
        $.ajax({
            type: 'POST',
            url: 'controllers/AccountController.php', // Replace with your controller URL
            contentType: 'application/json',
            data: JSON.stringify({ action: 'fetch_roles' }),
            success: function(response) {
                let result = JSON.parse(response);
                if (result.success) {
                    let roles = result.roles;
                    let roleOptions = '';
                    // Populate the role dropdown with roles from the database
                    roles.forEach(function(role) {
                        let selected = (role.id == currentRole) ? 'selected' : '';
                        roleOptions += `<option value="${role.id}" ${selected}>${role.name}</option>`;
                    });
                    $('#changeRole').html(roleOptions);
                    $('#changeRoleModal').modal('show'); // Show the modal after roles are loaded
                } else {
                    alert(result.message);
                }
            },
            error: function() {
                alert('Error fetching roles from the server.');
            }
        });
    });
    // Handle the "Change Role" form submission
    $('#changeRoleForm').submit(function(e) {
        e.preventDefault();
        let formData = {
            action: 'update_role',
            accountId: $('#changeUserId').val(),
            role_id: $('#changeRole').val()
        };
        $.ajax({
            type: 'POST',
            url: 'controllers/AccountController.php', // Replace with your controller URL
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                let result = JSON.parse(response);
                if (result.success) {
                    alert(result.message);
                    location.reload(); // Reload the page or refresh the user list
                } else {
                    alert(result.message);
                }
            },
            error: function() {
                alert('Error updating the user role.');
            }
        });
    });
});
</script>
<!-- JavaScript to Handle Form Submission and AJAX Requests -->
<script type="text/javascript">
    // Handle form submission for adding an account
    $(document).on("submit", "#AccountForm", function(e) {
        e.preventDefault();
        var password = $('#password').val();
        var email = $('#email').val();
        var action = 'store';
        if (password === '' || email === '') {
            alert('Please enter Username and Email');
            return;
        }
        $.ajax({
            url: 'controllers/AccountController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ password: password, email: email, action: action }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = 'index.php?page=account';
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred');
            }
        });
    });
    // Function to open the update modal and fill the form with current data
    function openAccountModal(accountId,email) {
        $('#updateAccountId').val(accountId);  // Set the account ID in the hidden field
        $('#updateEmail').val(email);  // Set the email in the input field
        $('#updateAccountModal').modal('show');  // Show the modal
    }
    // Handle form submission to update the account
    $(document).on("submit", "#updateAccountForm", function(e) {
        e.preventDefault();
        var accountId = $('#updateAccountId').val();
        var email = $('#updateEmail').val().trim();
        if (email === '') {
            alert('Please enter Username and Email');
            return;
        }
        $.ajax({
            url: 'controllers/AccountController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ accountId: accountId, email: email, action: 'update' }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#updateAccountModal').modal('hide');  // Hide the modal on success
                    window.location.href = 'index.php?page=account';  // Reload the page
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while updating the account.');
            }
        });
    });
    // Function to delete an account
    function deleteAccount(accountId) {
        if (confirm('Are you sure you want to delete this account?')) {
            $.ajax({
                url: 'controllers/AccountController.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id: accountId, action: 'delete' }),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'index.php?page=account';  // Reload the page after deleting the account
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while deleting the account.');
                }
            });
        }
    }
</script>
<?php require_once 'controllers/RoleController.php'; ?>
<!-- content dito -->
<div class="row">
	<div class="col-md-4 ">
		<div class="x_panel">
			<div class="x_title">
				<h2>Role <small>Management</small></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="#">Settings 1</a>
							<a class="dropdown-item" href="#">Settings 2</a>
						</div>
					</li>
					<li><a class="close-link"><i class="fa fa-close"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<br />
				<form class="form-label-left input_mask" id="roleform">
					<div class="form-group row">
						<label class="col-form-label col-md-3 col-sm-3 ">Role</label>
						<div class="col-md-9 col-sm-9 ">
							<input id="rolename" type="text" class="form-control" placeholder="Default Input">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-9 col-sm-9  offset-md-3">
							<button class="btn btn-primary" type="reset">Reset</button>
							<button type="submit" class="btn btn-success submit" >Publish</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-8 ">
		<div class="x_panel">
			<div class="x_title">
				<h2>Role <small>List</small></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
						<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
							<a class="dropdown-item" href="#">Settings 1</a>
							<a class="dropdown-item" href="#">Settings 2</a>
						</div>
					</li>
					<li><a class="close-link"><i class="fa fa-close"></i></a>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<br />
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($roles)): ?>
							<?php foreach ($roles as $d): ?>
								<tr>
									<td style="align-content: center;"><?php echo $d['name']; ?>
								</td>
								<td>
									<button type="button" class="btn btn-primary btn-sm" onclick="openModal('<?php echo $d['id']; ?>','<?php echo $d['name']; ?>')">Update</button>
									<button type="button" class="btn btn-danger btn-sm" onclick="toDelete('<?php echo $d['id']; ?>')">Drop</button>
									<?php if (in_array('Grant Permission', $_SESSION['user_permissions'])): ?>
										<button type="button" class="btn btn-warning btn-sm" onclick="openPermissions('<?php echo $d['id']; ?>')">Permissions</button>
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
</div><!-- end ng row -->
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="editrolemodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel2">Edit Role</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<h4>Name</h4>
				<input type="hidden" id="roleid">
				<input id="erolename" type="text" class="form-control" value="">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveUpdate">Save changes</button>
			</div>
		</div>
	</div>
</div>
<!-- Permissions Modal -->
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="permissionsModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Assign Permissions</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="permissionsForm">
					<input type="hidden" id="perm_role_id">
					<div id="permissionsList"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="savePermissions">Save Permissions</button>
			</div>
		</div>
	</div>
</div>
<!-- content dito -->
<script type="text/javascript">
	function openModal(x,y){
		var id = document.getElementById('roleid');
		var name = document.getElementById('erolename');
		id.value = x;
		name.value = y;
		// var id = document.querySelector('input[name="timeino"]');
		$('#editrolemodal').modal('show');
	}
</script>
<script type="text/javascript">
	$(document).on("submit", "#roleform", function(e) {
		e.preventDefault();
		var rolenames = $('#rolename').val();
		var action = 'store';
		if (rolename === '') {
			alert('Please enter Role Name');
			return;
		}
		$.ajax({
			url: 'controllers/RoleController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ rolename: rolenames, action: action,}),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=role';
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				window.location.href = 'index.php?page=role';
			}
		});
	});
</script>
<script type="text/javascript">
	$(document).on("click", "#saveUpdate", function(e) {
		e.preventDefault();
		var id = $('#roleid').val();
		var name = $('#erolename').val();
		var action = 'update';
		$.ajax({
			url: 'controllers/RoleController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ rolename: name, action: action,id:id}),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=role';
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				window.location.href = 'index.php?page=role';
			}
		});
	});
</script>
<script type="text/javascript">
	function toDelete(x){
		var action = 'delete';
		var requestData = {id:x,action:action};
		$.ajax({
			url: 'controllers/RoleController.php',
			data: JSON.stringify(requestData),
			cache: false,
			contentType: 'application/json',
			method: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=role';
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				window.location.href = 'index.php?page=role';
			}
		});
	}
</script>
<script type="text/javascript">
// Open permissions modal and load permissions for the selected role
function openPermissions(roleId) {
	$('#perm_role_id').val(roleId);
	$.ajax({
		url: 'controllers/RoleController.php',
		method: 'POST',
		contentType: 'application/json',
		data: JSON.stringify({ action: 'fetchPermissions', role_id: roleId }),
		dataType: 'json',
		success: function(response) {
			console.log(response)
			if (response.success) {
				var permissionsList = $('#permissionsList');
				permissionsList.empty();
				response.permissions.forEach(function(permission) {
					var isChecked = response.assigned_permissions.includes(permission.id.toString()) ? 'checked' : '';
					permissionsList.append(`
						<div class="form-check">
						<input class="form-check-input" type="checkbox" value="${permission.id}" id="perm${permission.id}" ${isChecked}>
						<label class="form-check-label" for="perm${permission.id}">${permission.name}</label>
						</div>
						`);
				});
				$('#permissionsModal').modal('show');
			} else {
				alert('Failed to load permissions.');
			}
		},
		error: function(xhr, status, error) {
			alert('An error occurred while fetching permissions.');
		}
	});
}
// Save permissions for the role
$('#savePermissions').click(function() {
	var roleId = $('#perm_role_id').val();
	var selectedPermissions = [];
	$('#permissionsList input[type="checkbox"]:checked').each(function() {
		selectedPermissions.push($(this).val());
	});
	$.ajax({
		url: 'controllers/RoleController.php',
		method: 'POST',
		contentType: 'application/json',
		data: JSON.stringify({ action: 'savePermissions', role_id: roleId, permissions: selectedPermissions }),
		dataType: 'json',
		success: function(response) {
			if (response.success) {
				alert(response.message);
				$('#permissionsModal').modal('hide');
                window.location.href = 'index.php?page=role'; // Refresh page
            } else {
            	alert('Failed to save permissions.');
            }
        },
        error: function(xhr, status, error) {
        	alert('An error occurred while saving permissions.');
        }
    });
});
</script>
<?php require_once 'controllers/PermissionController.php'; ?>
<!-- content dito -->
<div class="row">
	<div class="col-md-4 ">
		<div class="x_panel">
			<div class="x_title">
				<h2>Permission <small>Management</small></h2>
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
				<form class="form-label-left input_mask" id="Permissionform">
					<div class="form-group row">
						<label class="col-form-label col-md-3 col-sm-3 ">Permission</label>
						<div class="col-md-9 col-sm-9 ">
							<input id="permissionsname" type="text" class="form-control" >
						</div>
					</div>
					<div class="ln_solid"></div>
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
				<h2>Permission <small>List</small></h2>
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
						<?php if(isset($permissions)): ?>
							<?php foreach ($permissions as $d): ?>
								<tr>
									<td style="align-content: center;"><?php echo $d['name']; ?>
								</td>
								<td>
									<button type="button" class="btn btn-primary btn-sm" onclick="openModal('<?php echo $d['id']; ?>','<?php echo $d['name']; ?>')">Update</button>
									<button type="button" class="btn btn-danger btn-sm" onclick="toDelete('<?php echo $d['id']; ?>')">Drop</button>
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
<script type="text/javascript">
	$(document).on("submit", "#Permissionform", function(e) {
		e.preventDefault();
		var permissionname = $('#permissionsname').val();
		var action = 'store';
		if (permissionname === '') {
			alert('Please enter Role Name');
			return;
		}
		$.ajax({
			url: 'controllers/PermissionController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ permissionname: permissionname, action: action,}),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=permission';
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				window.location.href = 'index.php?page=permission';
			}
		});
	});
</script>


<script type="text/javascript">
	function toDelete(x){
		var action = 'delete';
		var requestData = {id:x,action:action};
		$.ajax({
			url: 'controllers/PermissionController.php',
			data: JSON.stringify(requestData),
			cache: false,
			contentType: 'application/json',
			method: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=permission';
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				window.location.href = 'index.php?page=permission';
			}
		});
	}
</script>.




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

<script type="text/javascript">
	$(document).on("click", "#saveUpdate", function(e) {
		e.preventDefault();
		var id = $('#roleid').val();
		var name = $('#erolename').val();
		var action = 'update';

		$.ajax({
			url: 'controllers/PermissionController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ permissionname: name, action: action,id:id}),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=permission';
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				window.location.href = 'index.php?page=permission';
			}
		});
	});
</script>
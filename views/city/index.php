<?php require_once 'controllers/CityController.php'; ?>
<!-- City Management Content -->
<div class="row">
	<div class="col-md-4">
		<div class="x_panel">
			<div class="x_title">
				<h2>City <small>Management</small></h2>
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
				<!-- City Form -->
				<form class="form-label-left input_mask" id="CityForm">
					<div class="form-group row">
						<label class="col-form-label col-md-3 col-sm-3">City Name</label>
						<div class="col-md-9 col-sm-9">
							<input id="cityname" type="text" class="form-control">
						</div>
					</div>
					<div class="ln_solid"></div>
					<div class="form-group row">
						<div class="col-md-9 col-sm-9 offset-md-3">
							<button class="btn btn-primary" type="reset">Reset</button>
							<button type="submit" class="btn btn-success">Add City</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="x_panel">
			<div class="x_title">
				<h2>City <small>List</small></h2>
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
				<!-- City List Table -->
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>City Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if(isset($cities)): ?>
							<?php foreach ($cities as $city): ?>
								<tr>
									<td><?php echo $city['name']; ?></td>
									<td>
										<!-- Inside the table for each city -->
										<button type="button" class="btn btn-primary btn-sm" onclick="openCityModal('<?php echo $city['id']; ?>','<?php echo $city['name']; ?>')">Update</button>
										<button type="button" class="btn btn-danger btn-sm" onclick="deleteCity('<?php echo $city['id']; ?>')">Delete</button>
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
<!-- Update City Modal -->
<div class="modal fade" id="updateCityModal" tabindex="-1" role="dialog" aria-labelledby="updateCityModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="updateCityModalLabel">Update City</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="updateCityForm">
					<input type="hidden" id="updateCityId" name="updateCityId"> <!-- Hidden field for City ID -->
					<div class="form-group">
						<label for="updateCityName">City Name</label>
						<input type="text" class="form-control" id="updateCityName" placeholder="Enter city name">
					</div>
					<button type="submit" class="btn btn-primary">Update City</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- JavaScript to Handle Form Submission and AJAX Requests -->
<script type="text/javascript">
	// Handle form submission for adding a city
	$(document).on("submit", "#CityForm", function(e) {
		e.preventDefault();
		var cityname = $('#cityname').val();
		var action = 'store';
		if (cityname === '') {
			alert('Please enter City Name');
			return;
		}
		$.ajax({
			url: 'controllers/CityController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ cityname: cityname, action: action }),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=citym';
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
function openCityModal(cityId, cityName) {
    $('#updateCityId').val(cityId);  // Set the city ID in the hidden field
    $('#updateCityName').val(cityName);  // Set the city name in the input field
    $('#updateCityModal').modal('show');  // Show the modal
}
// Handle form submission to update the city
$(document).on("submit", "#updateCityForm", function(e) {
	e.preventDefault();
	var cityId = $('#updateCityId').val();
	var cityName = $('#updateCityName').val().trim();
	if (cityName === '') {
		alert('Please enter the city name');
		return;
	}
	$.ajax({
		url: 'controllers/CityController.php',
		method: 'POST',
		contentType: 'application/json',
		data: JSON.stringify({ cityId: cityId, cityname: cityName, action: 'update' }),
		dataType: 'json',
		success: function(response) {
			if (response.success) {
                $('#updateCityModal').modal('hide');  // Hide the modal on success
                window.location.href = 'index.php?page=citym';  // Reload the page
            } else {
            	alert(response.message);
            }
        },
        error: function(xhr, status, error) {
        	alert('An error occurred while updating the city.');
        }
    });
});
	// Function to delete a city
	function deleteCity(cityId) {
		if (confirm('Are you sure you want to delete this city?')) {
			$.ajax({
				url: 'controllers/CityController.php',
				method: 'POST',
				contentType: 'application/json',
				data: JSON.stringify({ id: cityId, action: 'delete' }),
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						window.location.href = 'index.php?page=citym';
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
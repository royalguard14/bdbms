<?php require_once 'controllers/BudgetController.php'; ?>
<!-- Barangay Budget Management Content -->
<div class="row">
	<div class="col-md-4">
		<div class="x_panel">
			<div class="x_title">
				<h2>Barangay Budget <small>Management</small></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					<li><a class="close-link"><i class="fa fa-close"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<br />
				<!-- Budget Form -->
				<form class="form-label-left input_mask" id="BudgetForm" onsubmit="return validateBudgetForm()">
					<div class="form-group row">
						<label class="col-form-label col-md-3 col-sm-3">Year</label>
						<div class="col-md-9 col-sm-9">
							<input id="year" type="number" class="form-control"  min="2023" max="2030" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-md-3 col-sm-3">Barangay</label>
						<div class="col-md-9 col-sm-9">
							<select id="barangayId" class="form-control" required>
								<!-- Barangay options will be populated dynamically -->
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-md-3 col-sm-3">Budget</label>
						<div class="col-md-9 col-sm-9">
							<input id="budget" type="number" step="0.01" class="form-control" placeholder="Enter allocated budget" required>
						</div>
					</div>
					<div class="ln_solid"></div>
					<div class="form-group row">
						<div class="col-md-9 col-sm-9 offset-md-3">
							<button class="btn btn-primary" type="reset">Reset</button>
							<button type="submit" class="btn btn-success">Save Budget</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="x_panel">
			<div class="x_title">
				<h2>Barangay Budget <small>List</small></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
					<li><a class="close-link"><i class="fa fa-close"></i></a></li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<!-- Budget List Table -->
				<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Barangay</th>
							<th>Year</th>
							<th>Allocated Budget</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($budgets as $budget): ?>
							<tr>
								<td><?php echo $budget['barangay_name']; ?></td>
								<td><?php echo $budget['year']; ?></td>
								<td><?php echo number_format($budget['allocated_budget'], 2); ?></td>
								<td>
									<button type="button" class="btn btn-primary btn-sm" onclick="openBudgetModal('<?php echo $budget['barangay_id']; ?>', '<?php echo $budget['year']; ?>', '<?php echo $budget['allocated_budget']; ?>')">Update</button>
									<button type="button" class="btn btn-danger btn-sm" onclick="toDelete('<?php echo $budget['id']; ?>')">Drop</button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Update Budget Modal -->
<div class="modal fade" id="updateBudgetModal" tabindex="-1" role="dialog" aria-labelledby="updateBudgetModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="updateBudgetModalLabel">Update Barangay Budget</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="updateBudgetForm" onsubmit="return validateUpdateBudgetForm()">
					<input type="hidden" id="updateBarangayId">
					<div class="form-group">
						<label for="updateYear">Year</label>
						<input type="number" class="form-control" id="updateYear" readonly required>
					</div>
					<div class="form-group">
						<label for="updateBudget">Allocated Budget</label>
						<input type="number" step="0.01" class="form-control" id="updateBudget" required>
					</div>
					<button type="submit" class="btn btn-primary">Update Budget</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- JavaScript -->
<script type="text/javascript">
    // Add Budget Form Validation
	function validateBudgetForm() {
		const year = $('#year').val();
		const budget = $('#budget').val();
		if (!year || !budget) {
			alert('Please fill in all the required fields.');
			return false;
		}
		return true;
	}
    // Update Budget Form Validation
	function validateUpdateBudgetForm() {
		const budget = $('#updateBudget').val();
		if (!budget) {
			alert('Please provide the updated budget amount.');
			return false;
		}
		return true;
	}
    // Add Budget via AJAX
	$(document).on("submit", "#BudgetForm", function(e) {
		e.preventDefault();
		var barangayId = $('#barangayId').val();
		var year = $('#year').val();
		var budget = $('#budget').val();
		$.ajax({
			url: 'controllers/BudgetController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ action: 'addOrUpdateBudget', barangayId: barangayId, year: year, budget: budget }),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					location.reload();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alert('An error occurred.');
			}
		});
	});
    // Open Modal to Update Budget
	function openBudgetModal(barangayId, year, budget) {
		$('#updateBarangayId').val(barangayId);
		$('#updateYear').val(year);
		$('#updateBudget').val(budget);
		$('#updateBudgetModal').modal('show');
	}
    // Update Budget via AJAX
	$(document).on("submit", "#updateBudgetForm", function(e) {
		e.preventDefault();
		var barangayId = $('#updateBarangayId').val();
		var year = $('#updateYear').val();
		var budget = $('#updateBudget').val();
		$.ajax({
			url: 'controllers/BudgetController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ action: 'addOrUpdateBudget', barangayId: barangayId, year: year, budget: budget }),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					$('#updateBudgetModal').modal('hide');
					location.reload();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alert('An error occurred.');
			}
		});
	});
</script>
<!-- JavaScript -->
<script type="text/javascript">
	$('#year').on('change', function() {
		var year = $(this).val();
		if (year) {
			$.ajax({
				url: 'controllers/BudgetController.php',
				method: 'POST',
				contentType: 'application/json',
				data: JSON.stringify({ year: year, action: "checkyear" }),
				dataType: 'json',
				success: function(response) {
                // Log the response to the console for debugging
					if (response.success === true) {
						if (response.data.length === 0) {
							$('#barangays-list').html('<p>No barangays found without a budget for this year.</p>');
						}else {
                    // If data is returned, process the list of barangays
							if (response.data.length > 0) {
								var html = '';
                        // Create an empty option for the select dropdown
								html += '<option value="">Select Barangay</option>';
								response.data.forEach(function(data) {
									html += '<option value="' + data.id + '">' + data.name + '</option>';
								});
                        $('#barangayId').html(html); // Populate the barangay select dropdown
                    } else {
                    	$('#barangayId').html('<option value="">No barangays found</option>');
                    }
                }
            }
        }
    });
		}
	});
    // Add Budget via AJAX
	$(document).on("submit", "#BudgetForm", function(e) {
		e.preventDefault();
		var barangayId = $('#barangayId').val();
		var year = $('#year').val();
		var budget = $('#budget').val();
		$.ajax({
			url: 'controllers/BudgetController.php',
			method: 'POST',
			contentType: 'application/json',
			data: JSON.stringify({ action: 'addOrUpdateBudget', barangayId: barangayId, year: year, budget: budget }),
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					location.reload();
				} else {
					alert(response.message);
				}
			},
			error: function() {
				alert('An error occurred.');
			}
		});
	});
</script>
<script type="text/javascript">
	function toDelete(x){
		var action = 'delete';
		var requestData = {id:x,action:action};
		$.ajax({
			url: 'controllers/BudgetController.php',
			data: JSON.stringify(requestData),
			cache: false,
			contentType: 'application/json',
			method: 'POST',
			dataType: 'json',
			success: function(response) {
				if (response.success) {
					window.location.href = 'index.php?page=budget&&section=brgyBudget';
				} else {
					alert(response.message);
				}
			},
			error: function(xhr, status, error) {
				window.location.href = 'index.php?page=budget&&section=brgyBudget';
			}
		});
	}
</script>
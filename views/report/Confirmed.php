<?php require_once 'controllers/StatusController.php'; ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>Confirm<small>Forms</small></h2>
			<ul class="nav navbar-right panel_toolbox">
			</ul>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<div class="row">
				<div class="col-sm-12">
					<div class="card-box table-responsive">
						<table id="uploaded" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th>Record No.</th>
									<th>Form Type</th>
									<th>Barangay</th>
									<th>Confirm On</th>
									<?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT"): ?>
										<th>Action</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($myconfirm)): ?>
									<?php foreach ($myconfirm as $confirm): ?>
										<tr>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($confirm['id']); ?></td>
											<td style="vertical-align: middle;"><?php echo ($confirm['form_type'] == 1) ? 'Report' : 'Plan'; ?></td>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($confirm['barangay_name']); ?></td>
											<td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($confirm['changed_at'])); ?></td>
											<?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT"): ?>
												<td> <button type="button" class="btn btn-round btn-sm btn-outline viewconfirm" 
													data-id ="<?php echo htmlspecialchars($confirm['id']); ?>"
													data-brgy ="<?php echo htmlspecialchars($confirm['barangay_name']); ?>"
													data-formtype ="<?php echo ($confirm['form_type'] == 1) ? 'Report' : 'Plan'; ?>"
													data-title="<?php echo htmlspecialchars($confirm['file_name']); ?>" 
													data-period_covered="<?php echo htmlspecialchars($confirm['period_covered']); ?>" 
													data-change_by="<?php echo htmlspecialchars($confirm['first_name']. " " .$confirm['last_name'] ); ?>" 
													data-created_at="<?php echo htmlspecialchars($confirm['date_uploaded']); ?>" 
													data-changed_at="<?php echo htmlspecialchars($confirm['changed_at']); ?>" >
													<i class="fa fa-search"></i>
												</button></td>
											<?php endif; ?>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="viewconfirm">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4 col-sm-4 form-group">
						<h4 class="modal-title" id="myModalLabel">Form Information</h4>
					</div>
					<div class="col-md-4 col-sm-4 form-group" style="text-align: right; margin-top: .5rem;">
						<label class="control-label">Submitted On</label>
					</div>
					<div class="col-md-4 col-sm-4 form-group">
						<input type="text" id="submitted_on" class="form-control" readonly="readonly" style="font-size: .8rem;">
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12 col-sm-12 form-group">
						<label>File Name</label>
						<input type="text" id="file_name" class="form-control" disabled>
					</div>
					<div class="col-md-12 col-sm-12 form-group">
						<label>Period Covered</label>
						<input type="text" id="period_covered" class="form-control" disabled>
					</div>
					<div class="col-md-6 col-sm-6 form-group">
						<label>Confirmed By</label>
						<input type="text" id="submitted_by" class="form-control" disabled>
					</div>
					<div class="col-md-6 col-sm-6 form-group">
						<div class="row">
							<input type="hidden" id="report_id">
		
							<div class="col-md-6 col-sm-6 form-group">
								<label style="color: transparent;">====================</label>
								<button type="button" class="btn btn btn-success col-md-12 col-sm-12 toaccepted" style="font-size: .8rem;">
									Accepted
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#uploaded').DataTable({
			"paging": true,
			"lengthChange": false,
			"searching": false,
			"ordering": true,
			"info": true,
			"autoWidth": true,
			"responsive": true,
		});
	});
</script>
<script type="text/javascript">
	$('.viewconfirm').on('click', function () {
		$('#viewconfirm').modal('show'); 
		let id = $(this).data('id');
		let title = $(this).data('title');
		let periodCovered = $(this).data('period_covered');
		let createdAt = $(this).data('created_at');
		let changeBy = $(this).data('change_by');
		let changedAt = $(this).data('changed_at');
		let formtype = $(this).data('formtype');
		let brgy = $(this).data('brgy');
		let fperiodCovered = formatDateTime(periodCovered);
		let fchangedAt = formatDateTime(changedAt);
		$('#report_id').val(id);
		$('#file_name').val(title);
		$('#submitted_on').val(getFormattedDateTime(createdAt));
		$('#file_name').val(title);
		$('#period_covered').val(fperiodCovered);
		$('#submitted_by').val(changeBy);
		$('#accepted_on').val(fchangedAt);
	})
</script>
<script type="text/javascript">
   // Handle the "To Verified" button click
   $('.toaccepted').on('click', function () {
      let report_id = $('#report_id').val(); // Get the hidden report ID
      if (confirm('Are you sure you want to Accept this form?')) {
      	$.ajax({
      		url: 'controllers/UploadController.php?action=toAccepted',
      		type: 'POST',
      		data: { id: report_id },
      		success: function (response) {
      			if (response.success) {
      				$('#viewconfirm').modal('hide'); 
      				location.reload(); 
      			}
      		},
      		error: function () {
      			alert('Error verifying the form.');
      		}
      	});
      }
   });
</script>
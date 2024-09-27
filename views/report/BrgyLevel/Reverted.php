<?php require_once 'controllers/StatusController.php'; ?>
<!-- City Management Content -->



<div class="col-md-12 col-sm-12 ">
	<div class="x_panel">
		<div class="x_title">
			<h2>Reverted<small>Form</small></h2>
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
			<div class="row">
				<div class="col-sm-12">
					<div class="card-box table-responsive">
						<table id="uploaded" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th>Form Type</th>
									<th>Version</th>
									<th>Period Covered</th>
									<th>Reverted On</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>


								<?php if (!empty($myreverted)): ?>
									<?php foreach ($myreverted as $reverted): ?>
										<tr>
											<td style="vertical-align: middle;"><?php echo ($reverted['form_type'] == 1) ? 'Report' : 'Plan'; ?></td>
											<td style="vertical-align: middle;">1</td>
											<td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($reverted['period_covered'])); ?></td>
											<td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($reverted['changed_at'])); ?></td>
											<td style="vertical-align: middle; text-align: center;">

												<button type="button" class="btn btn-round btn-sm btn-outline viewreverted_btn" 


			data-title="<?php echo htmlspecialchars($reverted['file_name']); ?>" 
												data-period_covered="<?php echo htmlspecialchars($reverted['period_covered']); ?>" 
												data-change_by="<?php echo htmlspecialchars($reverted['first_name']. " " .$reverted['last_name'] ); ?>" 
												data-created_at="<?php echo htmlspecialchars($reverted['date_uploaded']); ?>" 
												data-changed_at="<?php echo htmlspecialchars($reverted['changed_at']); ?>" 
												data-remark="<?php echo htmlspecialchars($reverted['remark']); ?>" 
								





												>
													<i class="fa fa-eye"></i>
												</button>


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
	</div>
</div>
<!-- modal -->




<!-- modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="viewreverted_modal">
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
						<label>Reverted By</label>
						<input type="text" id="reverted_by" class="form-control" disabled>
					</div>
					<div class="col-md-6 col-sm-6 form-group">
						<label>Reverted On</label>
						<input type="text" id="reverted_on" class="form-control" disabled>
					</div>
									<div class="col-md-12 col-sm-12 form-group">
						<label class="">Remarks</label>
						<textarea class="form-control" id="remark" disabled>The Period Covered must be the date in the "Actual Date of Annual Meeting".
						</textarea>
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
		$('.viewreverted_btn').on('click', function () {
		$('#viewreverted_modal').modal('show'); 
		let title = $(this).data('title');
		let periodCovered = $(this).data('period_covered');
		let createdAt = $(this).data('created_at');
		let changeBy = $(this).data('change_by');
		let changedAt = $(this).data('changed_at');
		let remark = $(this).data('remark');


		let fchangedAt = formatDateTime(changedAt);
		let fperiodCovered = formatDateTime(periodCovered);


    // Assign values to the modal fields
    $('#submitted_on').val(getFormattedDateTime(createdAt));
    $('#file_name').val(title);
    $('#period_covered').val(fperiodCovered);
    $('#reverted_by').val(changeBy);
    $('#reverted_on').val(fchangedAt);
    $('#remark').val(remark);
});
</script>
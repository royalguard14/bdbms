<?php require_once 'controllers/StatusController.php'; ?>
<?php 
$formTypeLabels = [
    1 => 'Report',
    2 => 'Budget Plan',
     3 => 'Other Plan'
];

 ?>



<?php echo '<pre>'.var_export($mysubmitted, true).'</pre>' ?>

 
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>Routes<small>Form</small></h2>

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
									<th>Title</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($myfilesroute)): ?>
									<?php foreach ($myfilesroute as $data): ?>
										<tr>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($data['id']); ?></td>
											<td style="vertical-align: middle;"><?php echo $formTypeLabels[$data['form_type']] ?? 'Unknown'; ?></td>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($data['title']); ?></td>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($data['status']); ?></td>

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

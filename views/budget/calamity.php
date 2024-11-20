<?php require_once 'controllers/CalamityController.php'; ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>BARANGAY <?php echo $_SESSION['user_data']['barangay_name'] ?><small>Calamity Reports</small> </h2>
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
									<th>Buget Plan No.</th>
									<th>Title</th>
									<th>Date Cover</th>
									<th>Liquidate Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($calamityReport)): ?>
									<?php foreach ($calamityReport as $data): ?>
										<tr>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($data['id']); ?></td>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($data['title']); ?></td>
											<td style="vertical-align: middle;">
												<?php
												$date = DateTime::createFromFormat('Y-m-d', $data['period_covered']);
												echo htmlspecialchars($date ? $date->format('F Y') : 'Invalid Date');
												?>
											</td>
											<td style="vertical-align: middle;">
												<?php
												$remark = json_decode($data['remark'], true);
												$formattedRequestAmount = isset($remark['amount_request']) 
												? '₱ ' . number_format($remark['amount_request'], 2) 
												: 'N/A';
												echo htmlspecialchars($formattedRequestAmount);
												?>
											</td>
											<td>
												<button type="button" class="btn btn-danger btn-sm" onclick="deletePlan('<?php echo $data['id']; ?>')">Drop</button>
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
	    function deletePlan(planID) {
        if (confirm('Are you sure you want to delete this Report?')) {
            $.ajax({
                url: 'controllers/CalamityController.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id: planID, action: 'delete' }),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'index.php?page=budget&&section=calamity'; 
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
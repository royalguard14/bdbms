<?php require_once 'controllers/StatusController.php'; ?>
<?php 
$formTypeLabels = [
    1 => 'Report',
    2 => 'Budget Plan',
    3 => 'Other Plan'
];

function getFormTypeLabel($formType, $formTypeLabels)
{
    return $formTypeLabels[$formType] ?? 'Unknown';
}

function isAdminAssistant()
{
    return isset($_SESSION['role']['name']) && $_SESSION['role']['name'] === 'ADMIN ASSISTANT';
}
 ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>Accepted<small>Form</small></h2>

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
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($myaccepted)): ?>
									<?php foreach ($myaccepted as $accepted): ?>
										<tr>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($accepted['id']); ?></td>
											<td style="vertical-align: middle;"><?php echo $formTypeLabels[$accepted['form_type']] ?? 'Unknown'; ?></td>
											<td style="vertical-align: middle;"><?php echo htmlspecialchars($accepted['title']); ?></td>
											<td style="vertical-align: middle; text-align: center;">
												<button type="button" class="btn btn-round btn-sm btn-outline viewaccepted_btn"
												data-title="<?php echo htmlspecialchars($accepted['file_name']); ?>" 
												data-period_covered="<?php echo htmlspecialchars($accepted['period_covered']); ?>" 
												data-change_by="<?php echo htmlspecialchars($accepted['first_name']. " " .$accepted['last_name'] ); ?>" 
												data-created_at="<?php echo htmlspecialchars($accepted['date_uploaded']); ?>" 
												data-changed_at="<?php echo htmlspecialchars($accepted['changed_at']); ?>" 
												>
												<i class="fa fa-search"></i>
											</button>
						<button type="button" class="btn btn-round btn-sm btn-outline printBtn" 
    data-id ="<?php echo htmlspecialchars($accepted['id']); ?>"
    data-brgy ="<?php echo htmlspecialchars($accepted['barangay_name']); ?>"
    data-formtype ="<?php echo ($accepted['form_type'] == 1) ? 'Report' : 'Plan'; ?>"
    data-title="<?php echo htmlspecialchars($accepted['file_name']); ?>"
    data-period_covered="<?php echo htmlspecialchars($accepted['period_covered']); ?>"
    data-change_by="<?php echo htmlspecialchars($accepted['first_name']. " " .$accepted['last_name'] ); ?>"
    data-created_at="<?php echo htmlspecialchars($accepted['date_uploaded']); ?>"
    data-changed_at="<?php echo htmlspecialchars($accepted['changed_at']); ?>">
    <i class="fa fa-download"></i>
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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="viewaccepted">
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
						<label>Accepted By</label>
						<input type="text" id="accepted_by" class="form-control" disabled>
					</div>
					<div class="col-md-6 col-sm-6 form-group">
						<label>Accepted On</label>
						<input type="text" id="accepted_on" class="form-control" disabled>
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
	$('.viewaccepted_btn').on('click', function () {
		$('#viewaccepted').modal('show'); 
		let title = $(this).data('title');
		let periodCovered = $(this).data('period_covered');
		let createdAt = $(this).data('created_at');
		let changeBy = $(this).data('change_by');
		let changedAt = $(this).data('changed_at');
		let fperiodCovered = formatDateTimeMY(periodCovered);
		let fchangedAt = formatDateTime(changedAt);
    // Assign values to the modal fields
    //$('#submitted_on').val(formatDateTime(createdAt));
     $('#submitted_on').val(formatDateTime(createdAt));
    $('#file_name').val(title);
    $('#period_covered').val(fperiodCovered);
    $('#accepted_by').val(changeBy);
    $('#accepted_on').val(fchangedAt);
});
</script>
<script type="text/javascript">
	// Attach event listener to all buttons with the 'printBtn' class
$('.printBtn').on('click', function () {
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
    
    let printContent = `
    <style>
        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }
            body {
                font-family: Arial, sans-serif;
                height: auto;
                display: flex;
                flex-direction: column;
            }
            .footer {
                margin-top: 83%;
                text-align: center;
                font-size: 0.8rem;
                color: gray;
            }
            @page {
                margin: 0;
            }
            body {
                margin: 0;
            }
        }
    </style>
    <div style="padding: 20px; flex: 1;">
        <img src="../assets/images/favicon.ico" alt="Company Logo" style="width: 150px; margin-bottom: 20px;">
        <h3>The following document has been received:</h3>
        <br>
        <p><strong>Receiving:</strong> ${changeBy}</p>
        <p><strong>Receipt Date and Time:</strong> ${fchangedAt}</p>
        <br><br>
        <h2>Document Details:</h2>
        <hr>
        <div style="text-align: left; font-size: 0.9rem;">
            <p><strong>Barangay:</strong> ${brgy}</p>
            <p><strong>Record No.:</strong> ${id}</p>
            <p><strong>Document Type:</strong> ${formtype}</p>
            <p><strong>Period Covered:</strong> ${fperiodCovered}</p>
        </div>
        <div class="footer">
            <p>Acceptance of this document is subject to review of forms and contents</p>
        </div>
    </div>
    `;
    
    // Open a new window and print the content
    let printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><title>Print</title></head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
});

</script>
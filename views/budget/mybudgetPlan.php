<?php require_once 'controllers/BBPController.php'; ?>
<?php 
$formTypeLabels = [
    1 => 'Report',
    2 => 'Budget Plan',
    3 => 'Other Plan'
];
?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>BARANGAY <?php echo $_SESSION['user_data']['barangay_name'] ?><small>Budget Plan</small> </h2>
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
                            <th>Amount Proposal</th>
                            <th>Liquidate Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($bybudgetPlan)): ?>
                            <?php foreach ($bybudgetPlan as $data): ?>
                                <tr>
                                    <td style="vertical-align: middle;"><?php echo htmlspecialchars($data['id']); ?></td>
                                    <td style="vertical-align: middle;"><?php echo htmlspecialchars($data['title']); ?></td>
                                    <td style="vertical-align: middle;">
                                      <?php
                                      $date = DateTime::createFromFormat('Y-m-d H:i:s', $data['period_covered']);
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
                                <td style="vertical-align: middle;" id="mainTableTotal">
                                    <?php
                                    $formattedAmount = '₱ ' . number_format($data['total_liquidation'] ?? 0, 2);
                                    echo htmlspecialchars($formattedAmount);
                                    ?>
                                </td>
                                <td>
                                    <?php if ($data['status'] == "Accepted" ): ?>
                                        <button type="button" class="btn btn-info btn-sm" 
                                        onclick="liquidate('<?php echo $data['id']; ?>')">Liquidation</button>
                                    <?php elseif ($data['status'] == "Uploaded" ): ?>
                                       <button type="button" class="btn btn-round btn-sm btn-outline" 
                                       id="viewPdfButton"
                                       data-title="<?php echo $data['file_name']; ?>" >
                                       <i class="fa fa-eye"></i>
                                   </button>
                                   <button type="button" class="btn btn-round btn-sm btn-outline editUploaddetail" 
                                   data-id="<?php echo $data['id']; ?>" 
                                   data-formtype="<?php echo $data['form_type']; ?>" 
                                   data-title="<?php echo htmlspecialchars($data['title']); ?>" 
                                   data-period="<?php echo htmlspecialchars($data['period_covered']); ?>">
                                   <i class="fa fa-pencil"></i>
                               </button>
                               <button type="button" class="btn btn-round btn-sm btn-outline delete-btn" 
                               data-id="<?php echo $data['id']; ?>" >
                               <i class="fa fa-trash"></i>
                           </button>
                           <button type="button" class="btn btn-round btn-sm btn-outline submited-btn" data-id="<?php echo $data['id']; ?>">
                              <i class="fa fa-send-o"></i>
                          </button>
                          <?php elseif ($data['status'] == "Submitted" ): echo htmlspecialchars("Pending"); ?>
                          <?php elseif ($data['status'] == "Reverted" ): ?>
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
                        <?php else: echo htmlspecialchars($data['status']); ?>
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
</div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="liquidationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Liquidations</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="id_budget"> <!-- Hidden input for budget ID -->
            <div class="modal-body" style="overflow-y: auto; max-height: 500px;">
                <!-- Search Input -->
                <div class="form-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by description, amount, or date">
                </div>
                <table id="modaltable" class="table table-striped table-bordered" style="width:100%; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Amount Spent</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="liquidationData">
                        <!-- Data will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <h5 class="text-right">Total Amount Spent: <span id="totalAmountSpent">₱ 0.00</span></h5>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Non-File Upload Actions -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="nonUploadForm">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        <div class="row">
          <div class="col-md-4 col-sm-4 form-group">
            <h4 class="modal-title">Form Information</h4>
        </div>
        <div class="col-md-4 col-sm-4 form-group" style="text-align: right; margin-top: .5rem;">
            <label class="control-label">Date Today</label>
        </div>
        <div class="col-md-4 col-sm-4 form-group">
            <input type="text" class="form-control" id="currentDateTimeNonUpload" readonly="readonly" value="">
        </div>
    </div>
    <hr>
    <form id="nonUploadForm" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-12 col-sm-12 form-group">
          <input type="hidden" name="id">
          <label for="form_type">Form Type</label>
          <select name="form_type" id="form_type_non_upload" class="form-control" required>
            <option value="" disabled selected>Select Form Type</option>
            <option value="2">Budget Plan</option>
            <option value="1">Other Report</option>
            <option value="3">Other Plan</option>
        </select>
    </div>
    <div class="col-md-12 col-sm-12 form-group">
      <label for="title">Form Title</label>
      <input type="text" name="title" id="title_non_upload" class="form-control" required placeholder="Enter form title">
  </div>
  <div class="col-md-12 col-sm-12 form-group">
      <label for="period_covered">Period Covered</label>
      <input type="date" name="period_covered" id="period_covered_non_upload" class="form-control" required placeholder="Enter the period covered">
  </div>
  <div class="col-md-6 col-sm-6 form-group"></div>
  <div class="col-md-6 col-sm-6 form-group">
      <button type="submit" class="btn btn-primary col-md-12 col-sm-12" id="submitNonUpload">Update</button>
  </div>
</div>
</form>
</div>
</div>
</div>
</div>
<script type="text/javascript">
  $('.editUploaddetail').on('click', function () {
    let id = $(this).data('id');
    let formtype = $(this).data('formtype');
    let title = $(this).data('title');
    let period = $(this).data('period');
    $('input[name="id"]').val(id); 
    $('#form_type_non_upload').val(formtype); 
    $('#title_non_upload').val(title); 
    $('#period_covered_non_upload').val(period); 
    $('#currentDateTimeNonUpload').val(getFormattedDateTime());
    $('#nonUploadForm').modal('show'); 
});
  $(document).on('submit', '#nonUploadForm', function(e) {
    e.preventDefault();
    let formData = new FormData(this); 
    $.ajax({
      url: 'controllers/UploadController.php?action=updateUpload', 
      type: 'POST',
      data: formData,
      contentType: false, 
      processData: false, 
      success: function(response) {
        //let result = JSON.parse(response);
        if (response.success) {
          location.reload(); 
      } else {
          alert(result.message);
      }
  },
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
        $('#submitted_on').val(formatDateTime(createdAt));
        $('#file_name').val(title);
        $('#period_covered').val(fperiodCovered);
        $('#reverted_by').val(changeBy);
        $('#reverted_on').val(fchangedAt);
        $('#remark').val(remark);
    });
</script>
<script type="text/javascript">
        // Handle delete action
    $(document).on('click', '.delete-btn', function () {
      let id = $(this).data('id');
      if (confirm('Are you sure you want to delete this form?')) {
        $.ajax({
          url: 'controllers/UploadController.php?action=delete',
          type: 'POST',
          data: { id: id },
          success: function (response) {
            let result = JSON.parse(response);
            alert(result.message);
            if (result.success) {
              location.reload();
          }
      },
      error: function () {
        alert('Error deleting the form.');
    }
});
    }
});
</script>
<script type="text/javascript">
        // Handle delete action
    $(document).on('click', '.submited-btn', function () {
      let id = $(this).data('id');
      if (confirm('Are you sure you want to Submit this form?')) {
        $.ajax({
          url: 'controllers/UploadController.php?action=submited',
          type: 'POST',
          data: { id: id },
          success: function (response) {
              window.location.href = 'index.php?page=budget&&section=mybudgetPlan';
          }
      });
    }
});
</script>
<script type="text/javascript">
  $('#viewPdfButton').on('click', function() {
    let title = $(this).data('title'); // Get the title set above
    let filePath = `assets/uploaded_files/${title}`;
    // Check if the file exists
    $.ajax({
      url: filePath,
      type: 'HEAD',
      success: function() {
            // Set the PDF source to the iframe
        $('#pdfViewer').attr('src', filePath);
            // Show the modal with the embedded PDF
        $('#pdfViewerModal').modal('show');
    },
    error: function() {
        alert('File not found or unavailable for viewing.');
    }
});
});
</script>
<!-- modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="pdfViewerModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
    </div>
    <div class="modal-body">
        <iframe id="pdfViewer" src="" width="100%" height="500px"></iframe>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    // Search functionality
    $('#searchInput').on('keyup', function() {
        var searchValue = $(this).val().toLowerCase();
    // Filter table rows based on search query
        $('#modaltable tbody tr').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(searchValue) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
</script>
<script type="text/javascript">
    // Initialize DataTable
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
    function deletePlan(planID) {
        if (confirm('Are you sure you want to delete this Plan?')) {
            $.ajax({
                url: 'controllers/BBPController.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id: planID, action: 'delete' }),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'index.php?page=budget&&section=mybudgetPlan'; 
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
    function liquidate(planID) {
        $('#id_budget').val(planID);
        $.ajax({
            url: 'controllers/BBPController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ id: planID, action: 'get_liquidations' }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let liquidations = response.liquidations;
                    let totalAmount = response.total_amount;
                // Clear existing data
                    $('#liquidationData').empty();
                // Populate the table with liquidation data
                    liquidations.forEach((liquidation, index) => {
                        $('#liquidationData').append(`
                            <tr data-id="${liquidation.id}">
                            <td>${index + 1}</td>
                            <td>${liquidation.description}</td>
                            <td>₱ ${parseFloat(liquidation.amount_spent).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                            <td>${liquidation.liquidation_date}</td>
                            <td>
                            <button class="btn btn-danger btn-sm delete-liquidation">Delete</button>
                            </td>
                            </tr>
                            `);
                    });
                // Update the total amount spent
                    $('#totalAmountSpent').text(`₱ ${totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2 })}`);
                // Show the modal
                    $('#liquidationModal').modal('show');
                } else {
                    alert(response.message || 'Failed to fetch liquidations.');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching liquidations.');
            }
        });
    }
// Delete liquidation
    $(document).on('click', '.delete-liquidation', function() {
        const row = $(this).closest('tr');
        const liquidationID = row.data('id');
        if (confirm('Are you sure you want to delete this liquidation?')) {
            $.ajax({
                url: 'controllers/BBPController.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ action: 'delete_liquidation', liquidation_id: liquidationID }),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Liquidation deleted successfully!');
                        row.remove();
                        let planId = document.getElementById('id_budget').value;
                        updateTotalAmount()
                        updateMainTableTotal(planId); 
                    } else {
                        alert(response.message || 'Failed to delete liquidation.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while deleting liquidation.');
                }
            });
        }
    });
    function updateMainTableTotal(budget_plan_id) {
        $.ajax({
            url: 'controllers/BBPController.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ action: 'get_updated_total', budget_plan_id: budget_plan_id }),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let updatedTotal = response.updated_total;
                // Ensure the value is treated as a number
                    updatedTotal = parseFloat(updatedTotal); 
                    if (isNaN(updatedTotal)) {
                        alert('Invalid amount returned from the server.');
                        return;
                    }
                // Format the total correctly with commas and 2 decimal places
                    const formattedTotal = `₱ ${updatedTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                // Find the <td> in the main table and update its text
                    $('#mainTableTotal').text(formattedTotal);
                } else {
                    alert(response.message || 'Failed to fetch updated total.');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred while fetching updated total.');
            }
        });
    }
// Recalculate the total amount spent
    function updateTotalAmount() {
        let total = 0;
        $('#liquidationData tr').each(function() {
        // Extract the text from the third cell (Amount Spent) and remove any non-numeric characters
            const amountText = $(this).find('td:nth-child(3)').text().replace(/[^0-9.-]+/g, '');
            const amount = parseFloat(amountText);
            total += isNaN(amount) ? 0 : amount;
        });
    // Format the total as a currency string
        $('#totalAmountSpent').text(`₱ ${total.toLocaleString('en-US', { minimumFractionDigits: 2 })}`);
    }
</script>
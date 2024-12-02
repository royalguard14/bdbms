<?php require_once 'controllers/StatusController.php'; 
$formTypeLabels = [
  1 => 'Report',
  2 => 'Budget Plan',
  5 => 'Calamity Report',
];


?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Report<small>Status</small></h2>
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
                  <th>Form Type</th>
                  <th>Title</th>
                  <th>Date Uploaded</th>
                  <th>Status</th>
                  <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT"): ?>
                   <th>Barangay</th>
                   <th>Action</th>
                 <?php endif; ?>
               </tr>
             </thead>
             <tbody>
              <?php if (!empty($mysubmitted)): ?>
                <?php foreach ($mysubmitted as $submitted): ?>
                  <tr>
                    <td style="vertical-align: middle;"><?php echo $formTypeLabels[$submitted['form_type']] ?? 'Unknown'; ?></td>
                    <td style="vertical-align: middle;"><?php echo htmlspecialchars($submitted['title']); ?></td>
                    <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($submitted['date_uploaded'])); ?></td>
                    <td style="vertical-align: middle;"><?php echo htmlspecialchars($submitted['status']); ?></td>
                    <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT"): ?>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($submitted['barangay_name']); ?></td>
                    <?php endif; ?>

                    <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT"): ?>




                      <?php if ($submitted['status'] == "Submitted"): ?>
                        <td>
                          <button type="button" class="btn btn-round btn-sm btn-outline viewsubmitted" 
                          data-id ="<?php echo htmlspecialchars($submitted['id']); ?>"
                          data-brgy ="<?php echo htmlspecialchars($submitted['barangay_name']); ?>"
                          data-formtype ="<?php echo ($submitted['form_type'] == 1) ? 'Report' : 'Plan'; ?>"
                          data-title="<?php echo htmlspecialchars($submitted['file_name']); ?>" 
                          data-period_covered="<?php echo htmlspecialchars($submitted['period_covered']); ?>" 
                          data-change_by="<?php echo htmlspecialchars($submitted['first_name']. " " .$submitted['last_name'] ); ?>" 
                          data-created_at="<?php echo htmlspecialchars($submitted['date_uploaded']); ?>" 
                          data-changed_at="<?php echo htmlspecialchars($submitted['changed_at']); ?>" >
                          <i class="fa fa-search"></i>
                        </button>
                      </td>

                    <?php elseif ($submitted['status'] == "Verified"): ?>
                      <td>Waiting to Confirm</td>





                    <?php elseif ($submitted['status'] == "Confirm"): ?>
                      <td> 
                        <button type="button" class="btn btn-round btn-sm btn-outline viewconfirm" 
                        data-id="<?php echo htmlspecialchars($submitted['id']); ?>"
                        data-brgy="<?php echo htmlspecialchars($submitted['barangay_name']); ?>"
                        data-formtype="<?php echo $formTypeLabels[$submitted['form_type']]; ?>"
                        data-title="<?php echo htmlspecialchars($submitted['file_name']); ?>" 
                        data-period_covered="<?php echo htmlspecialchars($submitted['period_covered']); ?>" 
                        data-change_by="<?php echo htmlspecialchars($submitted['first_name'] . " " . $submitted['last_name']); ?>" 
                        data-created_at="<?php echo htmlspecialchars($submitted['date_uploaded']); ?>" 
                        data-changed_at="<?php echo htmlspecialchars($submitted['changed_at']); ?>" 
                        aria-label="View details for <?php echo htmlspecialchars($submitted['file_name']); ?>">
                        <i class="fa fa-search"></i>
                      </button>
                    </td>







                  <?php elseif ($submitted['status'] == "Accepted"): ?>



                    <td style="vertical-align: middle; text-align: center;">
                      <button type="button" class="btn btn-round btn-sm btn-outline viewaccepted_btn"
                      data-title="<?php echo htmlspecialchars($submitted['file_name']); ?>" 
                      data-period_covered="<?php echo htmlspecialchars($submitted['period_covered']); ?>" 
                      data-change_by="<?php echo htmlspecialchars($submitted['first_name']. " " .$submitted['last_name'] ); ?>" 
                      data-created_at="<?php echo htmlspecialchars($submitted['date_uploaded']); ?>" 
                      data-changed_at="<?php echo htmlspecialchars($submitted['changed_at']); ?>" 
                      >
                      <i class="fa fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-round btn-sm btn-outline printBtn" 
                    data-id ="<?php echo htmlspecialchars($submitted['id']); ?>"
                    data-brgy ="<?php echo htmlspecialchars($submitted['barangay_name']); ?>"
                    data-formtype="<?php echo $formTypeLabels[$submitted['form_type']]; ?>"
                    data-title="<?php echo htmlspecialchars($submitted['file_name']); ?>"
                    data-period_covered="<?php echo htmlspecialchars($submitted['period_covered']); ?>"
                    data-change_by="<?php echo htmlspecialchars($submitted['first_name']. " " .$submitted['last_name'] ); ?>"
                    data-created_at="<?php echo htmlspecialchars($submitted['date_uploaded']); ?>"
                    data-changed_at="<?php echo htmlspecialchars($submitted['changed_at']); ?>">
                    <i class="fa fa-print"></i>
                  </button>

                </td>



              <?php else: ?>
                <td>No Action</td>
              <?php endif; ?>








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
<!-- MERGE -->
<!-- Submitted.php -->
<!-- modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="viewsubmitted">
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
            <label>Submitted By</label>
            <input type="text" id="submitted_by" class="form-control" disabled>
          </div>
          <div class="col-md-6 col-sm-6 form-group">
          </div>
          <div class="col-md-12 col-sm-12 form-group">
            <div class="row">
              <input type="hidden" id="report_id">
              <div class="col-md-3 col-sm-3 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" id="downloadButton" class="btn btn-info col-md-12 col-sm-12" style="font-size: .8rem;">
                  Download PDF
                </button>
              </div>
              <div class="col-md-3 col-sm-3 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" id="viewPdfButton" class="btn btn-dark col-md-12 col-sm-12" style="font-size: .8rem;">
                  View PDF
                </button>
              </div>
              <div class="col-md-3 col-sm-3 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" class="btn btn btn-success col-md-12 col-sm-12 toverify" style="font-size: .8rem;">
                  Verified
                </button>
              </div>
              <div class="col-md-3 col-sm-3 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" class="btn btn btn-warning col-md-12 col-sm-12 torevert" style="font-size: .8rem;">
                  Revert
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
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
<!-- Modal for Revert Action with Remark -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="revertModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="remark">Remark:</label>
          <textarea id="remark" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="submitRevert" class="btn btn-warning">Revert</button></div>
      </div>
    </div>
  </div>
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
<script type="text/javascript">
  $('.viewsubmitted').on('click', function () {
    $('#viewsubmitted').modal('show'); 
    let id = $(this).data('id');
    let title = $(this).data('title');
    let periodCovered = $(this).data('period_covered');
    let createdAt = $(this).data('created_at');
    let changeBy = $(this).data('change_by');
    let changedAt = $(this).data('changed_at');
    let fperiodCovered = formatDateTime(periodCovered);
    let fchangedAt = formatDateTime(changedAt);
    // Populate modal fields
    $('#report_id').val(id);
    $('#file_name').val(title);
    $('#submitted_on').val(formatDateTime(createdAt));
    $('#period_covered').val(fperiodCovered);
    $('#submitted_by').val(changeBy);
    $('#accepted_on').val(fchangedAt);
    // Set the data-title attribute for the download button
    $('#downloadButton').data('title', title);
    $('#viewPdfButton').data('title', title);
  });
// Handle download button click
  $('#downloadButton').on('click', function() {
    let title = $(this).data('title'); // Get the title set above
    let filePath = `assets/uploaded_files/${title}`;
    // Create a temporary anchor element to trigger the download
    let tempLink = document.createElement('a');
    tempLink.href = filePath;
    tempLink.download = `${title}`; // Sets the filename for download
    tempLink.style.display = 'none';
    document.body.appendChild(tempLink);
    tempLink.click();
    document.body.removeChild(tempLink);
  });
</script>
<script type="text/javascript">
   // Handle the "To Verified" button click
 $('.toverify').on('click', function () {
      let report_id = $('#report_id').val(); // Get the hidden report ID
      if (confirm('Are you sure you want to Verify this form?')) {
        $.ajax({
          url: 'controllers/UploadController.php?action=toVerify',
          type: 'POST',
          data: { id: report_id },
          success: function (response) {
            if (response.success) {
              $('#viewsubmitted').modal('hide'); 
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
  <script type="text/javascript">
    $('.torevert').on('click', function () {
  let report_id = $('#report_id').val(); // Get the hidden report ID
  // Show the modal to ask for remark
  $('#revertModal').modal('show');
  // When the 'Revert' button inside the modal is clicked
  $('#submitRevert').on('click', function () {
    let remark = $('#remark').val(); // Get the remark entered by the user
    if (!remark) {
      alert('Please provide a remark.');
      return; // Don't proceed if remark is empty
    }
    if (confirm('Are you sure you want to revert this form?')) {
      // AJAX request to revert the form with the report ID and remark
      $.ajax({
        url: 'controllers/UploadController.php?action=toRevert',
        type: 'POST',
        data: {
          id: report_id,
          remark: remark // Send the remark along with the report ID
        },
        success: function (response) {
          if (response.success) {
            $('#revertModal').modal('hide'); // Close the modal
            location.reload(); // Reload the page
          } else {
            alert('Error reverting the form.');
          }
        },
        error: function () {
          alert('Error verifying the form.');
        }
      });
    }
  });
});
</script>

<!-- COnfirmed.php -->
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
            <input type="text" id="confirm_submitted_on" class="form-control" readonly="readonly" style="font-size: .8rem;">
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12 col-sm-12 form-group">
            <label>File Name</label>
            <input type="text" id="confirm_file_name" class="form-control" disabled>
          </div>
          <div class="col-md-12 col-sm-12 form-group">
            <label>Period Covered</label>
            <input type="text" id="confirm_period_covered" class="form-control" disabled>
          </div>
          <div class="col-md-6 col-sm-6 form-group">
            <label>Confirmed By</label>
            <input type="text" id="confirm_submitted_by" class="form-control" disabled>
          </div>
          <div class="col-md-6 col-sm-6 form-group">
            <div class="row">
              <input type="hidden" id="confirm_report_id">
              <div class="col-md-6 col-sm-6 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" class="btn btn-success col-md-12 col-sm-12 toaccepted" style="font-size: .8rem;">
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
    
    // Optionally format the dates if needed
    let fperiodCovered = formatDateTime(periodCovered);  // Assuming formatDateTime is defined
    let fchangedAt = formatDateTime(changedAt);  // Assuming formatDateTime is defined

    // Set values in modal inputs with unique, descriptive IDs
    $('#confirm_report_id').val(id);
    $('#confirm_file_name').val(title);
    $('#confirm_submitted_on').val(formatDateTime(createdAt));  // Ensure this function is defined
    $('#confirm_period_covered').val(fperiodCovered);
    $('#confirm_submitted_by').val(changeBy);
    $('#confirm_accepted_on').val(fchangedAt);  // Ensure #confirm_accepted_on exists in the modal

  });
</script>

<script type="text/javascript">
   // Handle the "To Verified" button click
 $('.toaccepted').on('click', function () {
      let report_id = $('#confirm_report_id').val(); // Get the hidden report ID
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


  <!-- accepted.php -->

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
            <input type="text" id="accepted_submitted_on" class="form-control" readonly="readonly" style="font-size: .8rem;">
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12 col-sm-12 form-group">
            <label>File Name</label>
            <input type="text" id="accepted_file_name" class="form-control" disabled>
          </div>
          <div class="col-md-12 col-sm-12 form-group">
            <label>Period Covered</label>
            <input type="text" id="accepted_period_covered" class="form-control" disabled>
          </div>
          <div class="col-md-6 col-sm-6 form-group">
            <label>Accepted By</label>
            <input type="text" id="accepted_accepted_by" class="form-control" disabled>
          </div>
          <div class="col-md-6 col-sm-6 form-group">
            <label>Accepted On</label>
            <input type="text" id="accepted_accepted_on" class="form-control" disabled>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('.viewaccepted_btn').on('click', function () {
    $('#viewaccepted').modal('show'); 

    let title = $(this).data('title');
    let periodCovered = $(this).data('period_covered');
    let createdAt = $(this).data('created_at');
    let changeBy = $(this).data('change_by');
    let changedAt = $(this).data('changed_at');
    
    // Format the date fields if needed
    let fperiodCovered = formatDateTimeMY(periodCovered);  // Assuming formatDateTimeMY is defined
    let fchangedAt = formatDateTime(changedAt);  // Assuming formatDateTime is defined

    // Set values in modal inputs with accepted_ prefix
    $('#accepted_submitted_on').val(formatDateTime(createdAt));  // Assuming formatDateTime is defined
    $('#accepted_file_name').val(title);
    $('#accepted_period_covered').val(fperiodCovered);
    $('#accepted_accepted_by').val(changeBy);
    $('#accepted_accepted_on').val(fchangedAt);
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

<?php require_once 'controllers/StatusController.php'; ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Submitted<small>Form</small></h2>
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
                  <th>File Name</th>
                  <th>Date Uploaded</th>
                  <th>Period Covered</th>
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
                    <td style="vertical-align: middle;"><?php echo ($submitted['form_type'] == 1) ? 'Report' : 'Plan'; ?></td>
                    <td style="vertical-align: middle;"><?php echo htmlspecialchars($submitted['title']); ?></td>
                    <td style="vertical-align: middle;"><?php echo htmlspecialchars($submitted['file_name']); ?></td>
                    <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($submitted['date_uploaded'])); ?></td>
                    <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($submitted['period_covered'])); ?></td>
                    <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT"): ?>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($submitted['barangay_name']); ?></td>
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
            <div class="row">
              <input type="hidden" id="report_id">
    <div class="col-md-4 col-sm-4 form-group">
  <label style="color: transparent;">====================</label>
  <button type="button" id="downloadButton" class="btn btn-dark col-md-12 col-sm-12" style="font-size: .8rem;">
    Download PDF
  </button>
</div>

              <div class="col-md-4 col-sm-4 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" class="btn btn btn-success col-md-12 col-sm-12 toverify" style="font-size: .8rem;">
                  Verified
                </button>
              </div>
              <div class="col-md-4 col-sm-4 form-group">
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
   // Handle the "To Verified" button click
   $('.torevert').on('click', function () {
      let report_id = $('#report_id').val(); // Get the hidden report ID
      if (confirm('Are you sure you want to Revert this form?')) {
        $.ajax({
          url: 'controllers/UploadController.php?action=toRevert',
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
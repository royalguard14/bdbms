<?php require_once 'controllers/StatusController.php'; ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Verified<small>Forms</small></h2>
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
                  <th>Period Covered</th>
                  <th>Verify By</th>
                  <th>Verify On</th>
                  <?php if ($_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
                    <th>Action</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($myverify)): ?>
                  <?php foreach ($myverify as $verify): ?>
                    <tr>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($verify['id']); ?></td>
                      <td style="vertical-align: middle;"><?php echo ($verify['form_type'] == 1) ? 'Report' : 'Plan'; ?></td>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($verify['barangay_name']); ?></td>
                      <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($verify['period_covered'])); ?></td>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($verify['first_name']. " " .$verify['last_name'] ); ?></td>
                      <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($verify['changed_at'])); ?></td>
                      <?php if ($_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
                        <td style="vertical-align: middle; text-align: center;">
                          <button type="button" class="btn btn-round btn-sm btn-outline viewverify_btn"
                          data-id ="<?php echo htmlspecialchars($verify['id']); ?>"
                          data-brgy ="<?php echo htmlspecialchars($verify['barangay_name']); ?>"
                          data-formtype ="<?php echo ($verify['form_type'] == 1) ? 'Report' : 'Plan'; ?>"
                          data-title="<?php echo htmlspecialchars($verify['file_name']); ?>" 
                          data-period_covered="<?php echo htmlspecialchars($verify['period_covered']); ?>" 
                          data-change_by="<?php echo htmlspecialchars($verify['first_name']. " " .$verify['last_name'] ); ?>" 
                          data-created_at="<?php echo htmlspecialchars($verify['date_uploaded']); ?>" 
                          data-changed_at="<?php echo htmlspecialchars($verify['changed_at']); ?>" >
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
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="viewverify">
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
            
          </div>
          <div class="col-md-4 col-sm-4 form-group">
            
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
            <label>Verified By</label>
            <input type="text" id="verify_by" class="form-control" disabled>
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
                <button type="button" class="btn btn btn-success col-md-12 col-sm-12 toconfirm" style="font-size: .8rem;">
                  Confirmed
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
  $('.viewverify_btn').on('click', function () {
    $('#viewverify').modal('show'); 
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
    $('#verify_by').val(changeBy);
    $('#verify_on').val(fchangedAt);

        $('#downloadButton').data('title', title);
  })

  $('#downloadButton').on('click', function() {
    let title = $(this).data('title'); // Get title for file name
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
   $('.torevert').on('click', function () {
      let report_id = $('#report_id').val(); // Get the hidden report ID
      if (confirm('Are you sure you want to Revert this form?')) {
        $.ajax({
          url: 'controllers/UploadController.php?action=toRevert',
          type: 'POST',
          data: { id: report_id },
          success: function (response) {
            if (response.success) {
              $('#viewverify').modal('hide'); 
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
   $('.toconfirm').on('click', function () {
      let report_id = $('#report_id').val(); // Get the hidden report ID
      if (confirm('Are you sure you want to Verify this form?')) {
        $.ajax({
          url: 'controllers/UploadController.php?action=toConfirm',
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
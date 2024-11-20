<?php require_once 'controllers/StatusController.php'; ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Barangay Budget Plans</small></h2>
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
                  
                  <th>Barangay</th>
                  <th>Title</th>
                  <th>Period Covered</th>
                  <th>Requested Amount</th>
          
                  <?php if ($_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
                    <th>Action</th>
                  <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($bugetPlans)): ?>
                  <?php foreach ($bugetPlans as $data): ?>
                    <tr>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($data['id']); ?></td>
                     
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($data['barangay_name']); ?></td>
                       <td style="vertical-align: middle;"><?php echo htmlspecialchars($data['title']); ?></td>
                      <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($data['period_covered'])); ?></td>
                     
                      <td style="vertical-align: middle;">                
                        <?php
                            $remark = json_decode($data['remark'], true);
                            $formattedRequestAmount = isset($remark['amount_request']) 
                            ? '₱ ' . number_format($remark['amount_request'], 2) 
                            : 'N/A';
                            echo htmlspecialchars($formattedRequestAmount);
                            ?></td>
                      <?php if ($_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
                        <td style="vertical-align: middle; text-align: center;">
                          <button type="button" class="btn btn-round btn-sm btn-outline viewverify_btn"
                          data-id ="<?php echo htmlspecialchars($data['id']); ?>"
                          data-brgy ="<?php echo htmlspecialchars($data['barangay_name']); ?>"
                          data-formtype ="<?php echo ($data['form_type'] == 1) ? 'Report' : 'Plan'; ?>"
                          data-title="<?php echo htmlspecialchars($data['title']); ?>" 
                          data-file_name="<?php echo htmlspecialchars($data['file_name']); ?>" 
                          data-period_covered="<?php echo htmlspecialchars($data['period_covered']); ?>" 
                          data-change_by="<?php echo htmlspecialchars($data['first_name']. " " .$data['last_name'] ); ?>" 
                          data-created_at="<?php echo htmlspecialchars($data['date_uploaded']); ?>" 
                          data-changed_at="<?php echo htmlspecialchars($data['changed_at']); ?>" >
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
            <label class="control-label">Submitted On</label>
          </div>
          <div class="col-md-4 col-sm-4 form-group">
            <input type="text" id="submitted_on" class="form-control" readonly="readonly" style="font-size: .8rem;">
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12 col-sm-12 form-group">
       
            <label>Title</label>
            
            <input type="text" id="title" class="form-control" disabled>
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
              <div class="col-md-6 col-sm-6 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" id="downloadButton" class="btn btn-info col-md-12 col-sm-12" style="font-size: .8rem;">
                  Download PDF
                </button>
              </div>

                <div class="col-md-6 col-sm-6 form-group">
                <label style="color: transparent;">====================</label>
                <button type="button" id="viewPdfButton" class="btn btn-dark col-md-12 col-sm-12" style="font-size: .8rem;">
                  View PDF
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
    let file_name = $(this).data('title'); // Get the title set above
    let filePath = `assets/uploaded_files/${file_name}`;

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
    let file_name = $(this).data('file_name');
    let periodCovered = $(this).data('period_covered');
    let createdAt = $(this).data('created_at');
    let changeBy = $(this).data('change_by');
    let changedAt = $(this).data('changed_at');
    let formtype = $(this).data('formtype');
    let brgy = $(this).data('brgy');
    let fperiodCovered = formatDateTime(periodCovered);
    let fchangedAt = formatDateTime(changedAt);
    $('#report_id').val(id);
    $('#title').val(title);
    $('#submitted_on').val(formatDateTime(createdAt));
 
    $('#period_covered').val(fperiodCovered);
    $('#verify_by').val(changeBy);
    $('#verify_on').val(fchangedAt);

    $('#downloadButton').data('title', file_name);
    $('#viewPdfButton').data('title', file_name);
  })

  $('#downloadButton').on('click', function() {
    let file_name = $(this).data('title'); // Get title for file name
    let filePath = `assets/uploaded_files/${file_name}`;

    // Create a temporary anchor element to trigger the download
    let tempLink = document.createElement('a');
    tempLink.href = filePath;
    tempLink.download = `${file_name}`; // Sets the filename for download
    tempLink.style.display = 'none';

    document.body.appendChild(tempLink);
    tempLink.click();
    document.body.removeChild(tempLink);

  });



</script>

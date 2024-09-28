<?php require_once 'controllers/StatusController.php'; ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Uploaded<small>Form</small></h2>
      <ul class="nav navbar-right panel_toolbox">
        <li><button type="button" class="btn btn-round btn-outline" id="uploadmodal">Upload New Form</button></li>
        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
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
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($myuploads)): ?>
                  <?php foreach ($myuploads as $upload): ?>
                    <tr>
                      <td style="vertical-align: middle;"><?php echo ($upload['form_type'] == 1) ? 'Report' : 'Plan'; ?></td>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($upload['title']); ?></td>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($upload['file_name']); ?></td>
                      <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($upload['date_uploaded'])); ?></td>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($upload['period_covered']); ?></td>
                      <td style="vertical-align: middle; text-align: center;">
                        <button type="button" class="btn btn-round btn-sm btn-outline editUploaddetail" 
                        data-id="<?php echo $upload['id']; ?>" 
                        data-formtype="<?php echo $upload['form_type']; ?>" 
                        data-title="<?php echo htmlspecialchars($upload['title']); ?>" 
                        data-period="<?php echo htmlspecialchars($upload['period_covered']); ?>">
                        <i class="fa fa-pencil"></i>
                      </button>
                      <button type="button" class="btn btn-round btn-sm btn-outline delete-btn" 
                      data-id="<?php echo $upload['id']; ?>" >
                      <i class="fa fa-trash"></i>
                    </button>
                    <button type="button" class="btn btn-round btn-sm btn-outline submited-btn" data-id="<?php echo $upload['id']; ?>">
                      <i class="fa fa-send-o"></i>
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
<!-- Modal for Uploading New Form -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="uploadform">
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
            <label class="control-label">Date today</label>
          </div>
          <div class="col-md-4 col-sm-4 form-group">
            <input type="text" class="form-control" id="currentDateTime" readonly="readonly" value="">
          </div>
        </div>
        <hr>
        <form id="uploadForm" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12 col-sm-12 form-group">
              <input type="hidden" name="id">
              <label for="form_type">Form Type</label>
              <select name="form_type" id="form_type" class="form-control" required>
                <option value="" disabled selected>Select Form Type</option>
                <option value="1">Report</option>
                <option value="2">Plan</option>
              </select>
            </div>
            <div class="col-md-12 col-sm-12 form-group">
              <label for="title">Form Title</label>
              <input type="text" name="title" id="title" class="form-control" required placeholder="Enter form title">
            </div>
            <div class="col-md-12 col-sm-12 form-group">
              <label for="period_covered">Period Covered</label>
              <input type="date" name="period_covered" id="period_covered" class="form-control" required placeholder="Enter the period covered">
            </div>
            <!-- File Upload Field -->
            <div class="col-md-12 col-sm-12 form-group" id="fileUploadField">
              <label for="uploaded_file">Upload File Here</label>
              <input type="file" name="uploaded_file" id="uploaded_file" class="form-control" required accept=".pdf">
            </div>
            <div class="col-md-6 col-sm-6 form-group"></div>
            <div class="col-md-6 col-sm-6 form-group">
              <button type="submit" class="btn btn-primary col-md-12 col-sm-12" id="submitUpload" name="submitUpload">Upload</button>
            </div>
          </div>
        </form>
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
                <option value="1">Report</option>
                <option value="2">Plan</option>
                <!-- Add other form types as needed -->
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
  $('#uploadmodal').on('click', function () {
    document.getElementById('currentDateTime').value = getFormattedDateTime();
    $('#uploadform').modal('show');
    $('#uploadForm')[0].reset(); 
  });
  $(document).on('submit', '#uploadForm', function(e) {
    e.preventDefault();
    let formData = new FormData(this); 
    $.ajax({
      url: 'controllers/UploadController.php?action=upload', 
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
      error: function() {
        alert('Error uploading the form.');
      }
    });
  });
</script>
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
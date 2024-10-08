<?php require_once 'controllers/StatusController.php'; ?>
<!-- City Management Content -->
<div class="col-md-12 col-sm-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Archived<small>Form</small></h2>
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
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($myarchived)): ?>
                  <?php foreach ($myarchived as $archived): ?>
                    <tr>
                      <td style="vertical-align: middle;"><?php echo ($archived['form_type'] == 1) ? 'Report' : 'Plan'; ?></td>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($archived['title']); ?></td>
                      <td style="vertical-align: middle;"><?php echo htmlspecialchars($archived['file_name']); ?></td>
                      <td style="vertical-align: middle;"><?php echo date('F d, Y | h:i A', strtotime($archived['date_uploaded'])); ?></td>
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
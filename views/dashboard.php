<?php require_once 'controllers/DashboardController.php'; ?>
<!-- top tiles -->
<?php



if ($_SESSION["role"]['name'] == "BRGY USER"):
$chartLabels = [];
$chartData = [];
foreach ($getTotalSpentByUserBarangay as $row) {
  $chartLabels[] = $row['formatted_date'];
  $chartData[] = $row['total_spent'];
}
$calamityLabels = [];
$calamityData = [];
$calamityTitles = [];
foreach ($calamityFundsUsage as $row) {
  $calamityLabels[] = $row['formatted_date'];
  $calamityData[] = (float) trim($row['amount_request'], '"');// Cast amount_request to float
  $calamityTitles[] = $row['title']; // Include the title for the report
}
endif




?>

<div class="row top_tiles" >
  <div class="tile_count col-md-12 col-sm-12">
    <?php if ($_SESSION["role"]['name'] == "BRGY USER"): ?>
      <div class="col-md-4 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-money"></i> Budget Allocated (This year)</span>
        <div class="count red">
          <?php
          $formattedAmount = '₱ ' . number_format($totalAlocatedBudget?? 0, 2);
          echo htmlspecialchars($formattedAmount);
          ?>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-bolt"></i> Quick Response Funds (This year)</span>
        <div class="count green">
          <?php
          $totalAllocatedBudget = $totalAlocatedBudget ?? 0;
          $thirtyPercent = $totalAllocatedBudget * 0.3; 
          $formattedThirtyPercent = '₱ ' . number_format($thirtyPercent, 2); 
          echo htmlspecialchars($formattedThirtyPercent);
          ?>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-calendar"></i> Allocated For Barangay Plans (This year)</span>
        <div class="count green">
          <?php
          $totalAllocatedBudget = $totalAlocatedBudget ?? 0;
          $thirtyPercent = $totalAllocatedBudget * 0.7; 
          $formattedThirtyPercent = '₱ ' . number_format($thirtyPercent, 2); 
          echo htmlspecialchars($formattedThirtyPercent);
          ?>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-file"></i> Total Reports / Plans (This Month)</span>
        <div class="count">
          <?php echo $totalReportsForMonth; ?>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-area-chart"></i> Quick Response Funds (Remaining)</span>
        <div class="count">
          <?php
          $totalAllocatedQRFBudget = $totalgetfromQRFBudget ?? 0;
          $thirtyPercent = $totalAllocatedBudget * 0.3;
          $natirasaQRF = $thirtyPercent - $totalAllocatedQRFBudget;
          $formattedThirtyPercent = '₱ ' . number_format($natirasaQRF, 2); 
          echo htmlspecialchars($formattedThirtyPercent);
          ?>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-pie-chart"></i> Remaining funds</span>
        <div class="count">
          <?php
          $totalAllocatedBudget = $totalAlocatedBudget ?? 0;
          $seventypercent = $totalAllocatedBudget * 0.7;
          $tirangPera = $seventypercent - $totalgetfromAlocatedBudget;
          $pwdpa = $tirangPera ?? 0;
          $formatedPwdpa = '₱ ' . number_format($pwdpa, 2); 
          echo htmlspecialchars($formatedPwdpa);
          ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" || $_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> Total Users</span>
        <div class="count"><?php echo $totalUsers; ?></div>
        <span class="count_bottom">System-Wide</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-map-marker"></i> Total Barangays</span>
        <div class="count"><?php echo $totalBarangays; ?></div>
        <span class="count_bottom">In City</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-file"></i> Reports (This Month)</span>
        <div class="count green"><?php echo $totalReportsForMonth; ?></div>
        <span class="count_bottom">Submitted</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-exclamation-circle"></i> Pending Reports</span>
        <div class="count green"><?php echo $pendingReports; ?></div>
        <span class="count_bottom">For Approval</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-file-o"></i> Not Submitted (This Month)</span>
        <div class="count green"><?php echo $notSubmittedBarangays; ?></div>
        <span class="count_bottom">Barangays</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-calendar"></i> Total Reports (This Year)</span>
        <div class="count green"><?php echo $totalReportsForYear; ?></div>
        <span class="count_bottom">System-Wide</span>
      </div>
    <?php endif; ?>
  </div>
</div>
<!-- /top tiles -->

<?php if ($_SESSION["role"]['name'] == "BRGY USER"): ?>
<div class="row">
  <div class="col-md-4 col-sm-6 ">
    <div class="x_panel fixed_height_320">
      <div class="x_title">
        <h2>Yearly Total Funds</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <canvas id="fundsPieChart" height="140" width="140" ></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-6">
    <div class="x_panel fixed_height_320">
      <div class="x_title">
        <h2>Monthly Quick Response Funds Expenses</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <canvas id="fundsUsageChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-6 ">
    <div class="x_panel fixed_height_320">
      <div class="x_title">
        <h2>Monthly Barangay Plans Expenses</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <canvas id="budgetChart" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function getRandomColor() {
    var r = Math.floor(Math.random() * 256);
    var g = Math.floor(Math.random() * 256);
    var b = Math.floor(Math.random() * 256);
    var a = (Math.random() * 0.5 + 0.5).toFixed(2); // Random alpha between 0.5 and 1
    return 'rgba(' + r + ',' + g + ',' + b + ',' + a + ')';
}
</script>
<script>
  var calamityLabels = <?php echo json_encode($calamityLabels); ?>;
  var calamityData = <?php echo json_encode($calamityData); ?>;
  var calamityTitles = <?php echo json_encode($calamityTitles); ?>;
  var chartLabels = <?php echo json_encode($chartLabels); ?>;
  var chartData = <?php echo json_encode($chartData); ?>;
</script>
<script>
  var ctx = document.getElementById('budgetChart').getContext('2d');
  var budgetChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: chartLabels, // Dynamically populated labels (e.g., "November 2024")
      datasets: [{
        label: 'Funds Spent (₱)',
        data: chartData, // Dynamically populated data
        backgroundColor: getRandomColor(),
        borderColor: getRandomColor(),
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Monthly Funds Usage'
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Amount (₱)'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Months'
          }
        }
      }
    }
  });
</script>
<script>
        // Pass PHP data to JavaScript
  var calamityLabels = <?php echo json_encode($calamityLabels); ?>;
  var calamityData = <?php echo json_encode($calamityData); ?>;
  var calamityTitles = <?php echo json_encode($calamityTitles); ?>;
        // Render the chart
  var ctx = document.getElementById('fundsUsageChart').getContext('2d');
  var fundsUsageChart = new Chart(ctx, {
    type: 'line',
    data: {
            labels: calamityLabels, // Use dynamic dates as labels
            datasets: [{
              label: 'Funds Spent (₱)',
              data: calamityData, // Use dynamic funds spent data
              borderColor: getRandomColor(),
              backgroundColor: getRandomColor(),
              fill: true
            }]
          },
          options: {
            plugins: {
              tooltip: {
                callbacks: {
                  // Display titles in tooltips
                  afterLabel: function(context) {
                    return 'Title: ' + calamityTitles[context.dataIndex];
                  }
                }
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                title: {
                  display: true,
                  text: 'Amount Spent (₱)'
                }
              },
              x: {
                title: {
                  display: true,
                  text: 'Months'
                }
              }
            }
          }
        });
      </script>
      <script>
        var ctx = document.getElementById('fundsPieChart').getContext('2d');
        var fundsPieChart = new Chart(ctx, {
          type: 'pie',
          data: {
            labels: ['Spend Funds', 'Allocated Funds'],
            datasets: [{
              data: [<?php echo $totalAmounNaNagastosThisYear ?>, <?php echo $totalAlocatedBudget ?>],
              backgroundColor: [
                   getRandomColor(),
        getRandomColor()
                ]
            }]
          },
          options: {
    responsive: true, // Ensures the chart is responsive
    maintainAspectRatio: false // Allows you to set your own height and width
  }
});
</script>

 <?php endif; ?>


 <?php if ($_SESSION["role"]['name'] == "ADMIN ASSISTANT" || $_SESSION["role"]['name'] == "HDRRMO ADMIN"): ?>
<div class="row">
  <?php foreach ($getBarangayBudgetDetails as $barangay): ?>
    <div class="col-md-4 col-sm-6">
      <div class="x_panel fixed_height_320">
        <div class="x_title">
          <h2><?php echo htmlspecialchars($barangay['barangay_name']); ?></h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <?php if (empty($barangay['total_budget']) || $barangay['total_budget'] == 0): ?>
            <!-- No Budget Assigned -->
            <div class="alert alert-warning text-center" role="alert">
              <strong>No budget assigned for this barangay.</strong>
            </div>
          <?php else: ?>
            <!-- Display Budget Details -->
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Category</th>
                  <th>Amount (₱)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Total Budget</td>
                  <td><?php echo number_format($barangay['total_budget'], 2); ?></td>
                </tr>
                <tr>
                  <td>Budget Plan Spent</td>
                  <td><?php echo number_format($barangay['total_budget_plan_spent'], 2); ?></td>
                </tr>
                <tr>
                  <td>Calamity Report Spent</td>
                  <td><?php echo number_format($barangay['total_calamity_report_spent'], 2); ?></td>
                </tr>
              </tbody>
            </table>
            <div>
              <strong>Budget Utilization:</strong>
              <?php 
                $totalSpent = $barangay['total_budget_plan_spent'] + $barangay['total_calamity_report_spent'];
                $utilization = $barangay['total_budget'] > 0 
                  ? ($totalSpent / $barangay['total_budget']) * 100 
                  : 0;
              ?>
              <div class="progress">
                <div 
                  class="progress-bar <?php echo $utilization > 80 ? 'bg-danger' : 'bg-success'; ?>" 
                  role="progressbar" 
                  style="width: <?php echo $utilization; ?>%" 
                  aria-valuenow="<?php echo $utilization; ?>" 
                  aria-valuemin="0" 
                  aria-valuemax="100"
                >
                  <?php echo round($utilization, 2); ?>%
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>


 <?php endif; ?>

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
                        <td style="vertical-align: middle;" id="mainTableTotal">
                            <?php
                            $formattedAmount = '₱ ' . number_format($data['total_liquidation'] ?? 0, 2);
                            echo htmlspecialchars($formattedAmount);
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deletePlan('<?php echo $data['id']; ?>')">Drop</button>
                            <button type="button" class="btn btn-info btn-sm" 
                            onclick="liquidate('<?php echo $data['id']; ?>')">Liquidation</button>
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
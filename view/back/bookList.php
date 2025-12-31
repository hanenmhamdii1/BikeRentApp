<?php
session_start();
include_once '../../Controller/AdminController.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../front/login.php');
    exit();
}

$ac = new AdminController();

// Initial data load
$rentals = $ac->listAllRentals();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeRent Admin | Dynamic Booking Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f7fe; font-family: 'Inter', sans-serif; }
        .sidebar { width: 250px; height: 100vh; background: #2e2e2e; color: white; position: fixed; padding: 20px; }
        .main-content { margin-left: 250px; padding: 40px; }
        .nav-link { color: #aaa; margin: 10px 0; display: block; text-decoration: none; transition: 0.3s; }
        .nav-link.active { color: #4a6cf7; font-weight: bold; }
        .nav-link:hover { color: white; }
        .data-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .badge-active { background: #e1fcef; color: #147d4e; }
        /* Animation for live search results */
        #rentalTableBody tr { transition: all 0.2s ease-in-out; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3 class="mb-5 text-primary">AdminPanel</h3>
    <a href="../back/admin_dashboard.php" class="nav-link"><i class="fa fa-users me-2"></i> Users Management</a>
    <a href="#" class="nav-link active"><i class="fa fa-list me-2"></i> Booking List</a>
    <a href="../front/list_product.php" class="nav-link"><i class="fa fa-bicycle me-2"></i> View Gallery</a>
    <a href="../front/profile.php" class="nav-link"><i class="fa fa-user-cog me-2"></i> My Profile</a>
    <a href="../front/logout.php" class="nav-link text-danger mt-5"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Rental Transactions</h2>
        <span class="badge bg-primary px-3 py-2" id="totalCount"><?php echo count($rentals); ?> Total Bookings</span>
    </div>

    <div class="data-card mb-4">
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label small fw-bold text-muted">LIVE RESEARCH</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fa fa-search"></i></span>
                    <input type="text" id="liveSearch" class="form-control bg-light border-0" placeholder="Type client name or vehicle name to search instantly...">
                </div>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-bold text-muted">STATUS FILTER</label>
                <select id="statusFilter" class="form-select bg-light border-0">
                    <option value="">All Statuses</option>
                    <option value="active">Active Only</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <div class="data-card">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Vehicle</th>
                    <th>Rental Period</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="rentalTableBody">
                <?php foreach($rentals as $r): ?>
                <tr>
                    <td><small class="text-muted">#BR-<?php echo $r['id']; ?></small></td>
                    <td><strong><?php echo htmlspecialchars($r['client_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($r['bike_name']); ?></td>
                    <td>
                        <div style="font-size: 0.85rem;">
                            <i class="fa fa-calendar-alt me-1 text-muted"></i>
                            <?php echo $r['start_date']; ?> <i class="fa fa-arrow-right mx-1 small"></i> <?php echo $r['end_date']; ?>
                        </div>
                    </td>
                    <td><span class="fw-bold text-primary"><?php echo number_format($r['total_price'], 2); ?> DT</span></td>
                    <td>
                        <span class="badge <?php echo ($r['status'] == 'active') ? 'badge-active' : 'bg-light text-dark'; ?> px-3 py-2">
                            <?php echo ucfirst($r['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearch');
    const statusSelect = document.getElementById('statusFilter');
    const tableBody = document.getElementById('rentalTableBody');

    function fetchResults() {
        const query = searchInput.value;
        const status = statusSelect.value;

        // Use Fetch API to call the search helper
        fetch(`search_rentals.php?search_rent=${encodeURIComponent(query)}&rent_status=${status}`)
            .then(response => response.text())
            .then(data => {
                tableBody.innerHTML = data;
                // Update the counter based on visible rows
                const rowCount = tableBody.querySelectorAll('tr:not(.no-results)').length;
                document.getElementById('totalCount').innerText = rowCount + " Bookings Found";
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    // Event listener for typing
    searchInput.addEventListener('input', fetchResults);
    
    // Event listener for dropdown change
    statusSelect.addEventListener('change', fetchResults);
});
</script>

</body>
</html>
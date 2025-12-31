<?php
session_start();
include_once '../../Controller/AdminController.php';

// Security: Only allow Admin role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../front/login.php');
    exit();
}

$ac = new AdminController();

// Handle Status Updates
if (isset($_GET['action']) && isset($_GET['uid'])) {
    $ac->updateUserStatus($_GET['uid'], $_GET['action']);
    header('Location: admin_dashboard.php');
}

$users = $ac->listAllUsers();
$stats = $ac->getStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikeRent | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f7fe; font-family: 'Inter', sans-serif; }
        .sidebar { width: 250px; height: 100vh; background: #2e2e2e; color: white; position: fixed; padding: 20px; }
        .main-content { margin-left: 250px; padding: 40px; }
        .stat-card { background: white; border-radius: 15px; padding: 25px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .user-table { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .bg-pending { background: #fff3cd; color: #856404; }
        .nav-link { color: #aaa; margin: 10px 0; display: block; text-decoration: none; }
        .nav-link.active { color: #4a6cf7; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3 class="mb-5 text-primary">AdminPanel</h3>
    <a href="#" class="nav-link "><i class="fa fa-users me-2"></i> Users Management</a>
    <a href="../back/bookList.php" class="nav-link"><i class="fa fa-list me-2"></i> Booking List</a>
    <a href="../front/list_product.php" class="nav-link"><i class="fa fa-bicycle me-2"></i> View Gallery</a>
    <a href="../front/profile.php" class="nav-link"><i class="fa fa-user-cog me-2"></i> My Profile</a>
    <a href="../front/logout.php" class="nav-link text-danger mt-5"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Platform Overview</h2>
    
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted text-uppercase fw-bold">Total Members</small>
                <h2 class="mb-0"><?php echo $stats['total_users']; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted text-uppercase fw-bold">Listed Vehicles</small>
                <h2 class="mb-0"><?php echo $stats['total_bikes']; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <small class="text-muted text-uppercase fw-bold">Ongoing Rents</small>
                <h2 class="mb-0 text-primary"><?php echo $stats['active_rents']; ?></h2>
            </div>
        </div>
    </div>

    <div class="user-table p-4">
        <h4 class="fw-bold mb-4">User Approval Management</h4>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Registered On</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?php echo htmlspecialchars($u['name']); ?></div>
                        <small class="text-muted"><?php echo htmlspecialchars($u['email']); ?></small>
                    </td>
                    <td><span class="badge bg-light text-dark"><?php echo ucfirst($u['role']); ?></span></td>
                    <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                    <td>
                        <span class="status-badge bg-<?php echo $u['status']; ?>">
                            <?php echo ucfirst($u['status']); ?>
                        </span>
                    </td>
                    <td class="text-end">
                        <?php if($u['status'] !== 'approved'): ?>
                            <a href="?action=approved&uid=<?php echo $u['id']; ?>" class="btn btn-sm btn-success rounded-pill px-3">Approve</a>
                        <?php endif; ?>
                        
                        <?php if($u['status'] !== 'blocked'): ?>
                            <a href="?action=blocked&uid=<?php echo $u['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">Block</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
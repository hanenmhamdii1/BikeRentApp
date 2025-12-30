<?php
session_start();
include_once '../../Controller/AdminController.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    exit('Unauthorized');
}

$ac = new AdminController();
$status = $_GET['rent_status'] ?? '';
$search = $_GET['search_rent'] ?? '';

$rentals = $ac->listAllRentals($status, $search);

if (!empty($rentals)) {
    foreach ($rentals as $r) {
        $badgeClass = ($r['status'] == 'active') ? 'badge-active' : 'bg-light text-dark';
        echo "<tr>
                <td><small class='text-muted'>#BR-{$r['id']}</small></td>
                <td><strong>" . htmlspecialchars($r['client_name']) . "</strong></td>
                <td>" . htmlspecialchars($r['bike_name']) . "</td>
                <td>
                    <div style='font-size: 0.85rem;'>
                        <i class='fa fa-calendar-alt me-1 text-muted'></i>
                        {$r['start_date']} <i class='fa fa-arrow-right mx-1 small'></i> {$r['end_date']}
                    </div>
                </td>
                <td><span class='fw-bold text-primary'>" . number_format($r['total_price'], 2) . " DT</span></td>
                <td>
                    <span class='badge {$badgeClass} px-3 py-2'>" . ucfirst($r['status']) . "</span>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No results found.</td></tr>";
}
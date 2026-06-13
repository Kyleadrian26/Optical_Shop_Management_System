<?php
session_start();
include "config.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

$success_msg = '';
$error_msg   = '';

// ============ HANDLE ADMIN REPLY ============
if(isset($_POST['submit_reply'])){
    $type    = mysqli_real_escape_string($conn, $_POST['type']);
    $id      = (int)$_POST['id'];
    $reply   = mysqli_real_escape_string($conn, trim($_POST['admin_reply']));
    $status  = mysqli_real_escape_string($conn, trim($_POST['status']));

    if($type === 'appointment'){
        mysqli_query($conn,
            "UPDATE appointments SET admin_reply='$reply', status='$status' WHERE appointment_id=$id");
    } elseif($type === 'order'){
        mysqli_query($conn,
            "UPDATE order_requests SET admin_reply='$reply', status='$status' WHERE order_request_id=$id");
    } elseif($type === 'repair'){
        mysqli_query($conn,
            "UPDATE repair_requests SET admin_reply='$reply', status='$status' WHERE repair_id=$id");
    }

    $success_msg = 'Reply saved successfully!';
}

// ============ FETCH ALL DATA ============
$appointments = mysqli_query($conn, "SELECT * FROM appointments ORDER BY created_at DESC");
$orders       = mysqli_query($conn, "SELECT * FROM order_requests ORDER BY created_at DESC");
$repairs      = mysqli_query($conn, "SELECT * FROM repair_requests ORDER BY created_at DESC");

// Counts
$appt_count   = mysqli_num_rows($appointments);
$order_count  = mysqli_num_rows($orders);
$repair_count = mysqli_num_rows($repairs);

// Pending counts for badges
$pending_appt   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM appointments WHERE status='Pending'"))['c'];
$pending_order  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM order_requests WHERE status='Pending'"))['c'];
$pending_repair = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM repair_requests WHERE status='Pending'"))['c'];

// Reset pointers
mysqli_data_seek($appointments, 0);
mysqli_data_seek($orders, 0);
mysqli_data_seek($repairs, 0);

// Active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'appointments';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Process Orders — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="process_orders.css">
</head>
<body>

<!-- ============ PAGE HEADER ============ -->
<div class="page-header">
    <h1>Process <span>Orders</span></h1>
    <p>Manage appointments, order requests, and repair submissions</p>
</div>

<!-- ============ MAIN CONTAINER ============ -->
<div class="admin-container">

    <!-- Top Bar -->
    <div class="top-bar">
        <a href="admin_dashboard.php" class="btn-back">← Back to Dashboard</a>
        <div class="top-stats">
            <span class="stat-pill">📅 <?php echo $appt_count; ?> Appointments</span>
            <span class="stat-pill">👓 <?php echo $order_count; ?> Orders</span>
            <span class="stat-pill">🔧 <?php echo $repair_count; ?> Repairs</span>
        </div>
    </div>

    <?php if($success_msg): ?>
    <div class="alert-success">✅ <?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tab-bar">
        <button class="tab-btn <?php echo $active_tab === 'appointments' ? 'active' : ''; ?>"
                onclick="switchTab('appointments', this)">
            📅 Appointments
            <?php if($pending_appt > 0): ?>
                <span class="pending-badge"><?php echo $pending_appt; ?></span>
            <?php endif; ?>
        </button>
        <button class="tab-btn <?php echo $active_tab === 'orders' ? 'active' : ''; ?>"
                onclick="switchTab('orders', this)">
            👓 Order Requests
            <?php if($pending_order > 0): ?>
                <span class="pending-badge"><?php echo $pending_order; ?></span>
            <?php endif; ?>
        </button>
        <button class="tab-btn <?php echo $active_tab === 'repairs' ? 'active' : ''; ?>"
                onclick="switchTab('repairs', this)">
            🔧 Repair Requests
            <?php if($pending_repair > 0): ?>
                <span class="pending-badge"><?php echo $pending_repair; ?></span>
            <?php endif; ?>
        </button>
    </div>

    <!-- ======== APPOINTMENTS TAB ======== -->
    <div id="tab-appointments" class="tab-content <?php echo $active_tab === 'appointments' ? 'active' : ''; ?>">
        <?php if($appt_count === 0): ?>
            <div class="empty-state">📭 No appointments submitted yet.</div>
        <?php else: ?>
        <?php while($row = mysqli_fetch_assoc($appointments)): ?>
        <div class="request-card status-<?php echo strtolower(str_replace(' ','-',$row['status'])); ?>">

            <div class="card-header">
                <div class="card-header-left">
                    <span class="request-id">#A<?php echo $row['appointment_id']; ?></span>
                    <span class="customer-name">
                        <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                    </span>
                    <span class="status-badge status-<?php echo strtolower(str_replace(' ','-',$row['status'])); ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </div>
                <span class="card-date"><?php echo date('M d, Y g:i A', strtotime($row['created_at'])); ?></span>
            </div>

            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">📧 Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📱 Phone</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['phone']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📅 Preferred Date</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['preferred_date']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🕐 Preferred Time</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['preferred_time']); ?></span>
                    </div>
                    <div class="info-item full">
                        <span class="info-label">🎯 Purpose</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['purpose']); ?></span>
                    </div>
                    <?php if(!empty($row['notes'])): ?>
                    <div class="info-item full">
                        <span class="info-label">📝 Notes</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['notes']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if(!empty($row['admin_reply'])): ?>
                <div class="previous-reply">
                    <span class="reply-label">💬 Your Previous Reply:</span>
                    <p><?php echo htmlspecialchars($row['admin_reply']); ?></p>
                </div>
                <?php endif; ?>

                <!-- Reply Form -->
                <form method="POST" action="process_orders.php?tab=appointments" class="reply-form">
                    <input type="hidden" name="type" value="appointment">
                    <input type="hidden" name="id" value="<?php echo $row['appointment_id']; ?>">
                    <div class="reply-row">
                        <div class="reply-status-wrap">
                            <label>Update Status</label>
                            <select name="status">
                                <option <?php echo $row['status']==='Pending'    ? 'selected':'' ?>>Pending</option>
                                <option <?php echo $row['status']==='Confirmed'  ? 'selected':'' ?>>Confirmed</option>
                                <option <?php echo $row['status']==='Done'       ? 'selected':'' ?>>Done</option>
                                <option <?php echo $row['status']==='Cancelled'  ? 'selected':'' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="reply-text-wrap">
                            <label>Reply to Customer</label>
                            <textarea name="admin_reply" placeholder="Type your reply here... (will be shown to customer via their contact details)"><?php echo htmlspecialchars($row['admin_reply'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="contact-hint">
                        📞 Contact via: <strong><?php echo htmlspecialchars($row['phone']); ?></strong>
                        &nbsp;|&nbsp; 📧 <strong><?php echo htmlspecialchars($row['email']); ?></strong>
                    </div>
                    <button type="submit" name="submit_reply" class="btn-reply">
                        💾 Save Reply & Status
                    </button>
                </form>

            </div>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- ======== ORDERS TAB ======== -->
    <div id="tab-orders" class="tab-content <?php echo $active_tab === 'orders' ? 'active' : ''; ?>">
        <?php if($order_count === 0): ?>
            <div class="empty-state">📭 No order requests submitted yet.</div>
        <?php else: ?>
        <?php while($row = mysqli_fetch_assoc($orders)): ?>
        <div class="request-card status-<?php echo strtolower(str_replace(' ','-',$row['status'])); ?>">

            <div class="card-header">
                <div class="card-header-left">
                    <span class="request-id">#O<?php echo $row['order_request_id']; ?></span>
                    <span class="customer-name">
                        <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                    </span>
                    <span class="status-badge status-<?php echo strtolower(str_replace(' ','-',$row['status'])); ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </div>
                <span class="card-date"><?php echo date('M d, Y g:i A', strtotime($row['created_at'])); ?></span>
            </div>

            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">📧 Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📱 Phone</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['phone']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">👓 Eyewear Type</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['eyewear_type']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🔭 Lens Type</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['lens_type'] ?: '—'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🖼️ Frame Preference</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['frame_pref'] ?: '—'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">💰 Budget</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['budget'] ?: '—'); ?></span>
                    </div>
                    <div class="info-item full">
                        <span class="info-label">📋 Prescription</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['has_prescription'] ?: '—'); ?></span>
                    </div>
                    <?php if(!empty($row['notes'])): ?>
                    <div class="info-item full">
                        <span class="info-label">📝 Notes</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['notes']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if(!empty($row['admin_reply'])): ?>
                <div class="previous-reply">
                    <span class="reply-label">💬 Your Previous Reply:</span>
                    <p><?php echo htmlspecialchars($row['admin_reply']); ?></p>
                </div>
                <?php endif; ?>

                <form method="POST" action="process_orders.php?tab=orders" class="reply-form">
                    <input type="hidden" name="type" value="order">
                    <input type="hidden" name="id" value="<?php echo $row['order_request_id']; ?>">
                    <div class="reply-row">
                        <div class="reply-status-wrap">
                            <label>Update Status</label>
                            <select name="status">
                                <option <?php echo $row['status']==='Pending'    ? 'selected':'' ?>>Pending</option>
                                <option <?php echo $row['status']==='Processing' ? 'selected':'' ?>>Processing</option>
                                <option <?php echo $row['status']==='Ready'      ? 'selected':'' ?>>Ready</option>
                                <option <?php echo $row['status']==='Completed'  ? 'selected':'' ?>>Completed</option>
                                <option <?php echo $row['status']==='Cancelled'  ? 'selected':'' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="reply-text-wrap">
                            <label>Reply to Customer</label>
                            <textarea name="admin_reply" placeholder="Type your reply here..."><?php echo htmlspecialchars($row['admin_reply'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="contact-hint">
                        📞 Contact via: <strong><?php echo htmlspecialchars($row['phone']); ?></strong>
                        &nbsp;|&nbsp; 📧 <strong><?php echo htmlspecialchars($row['email']); ?></strong>
                    </div>
                    <button type="submit" name="submit_reply" class="btn-reply">
                        💾 Save Reply & Status
                    </button>
                </form>

            </div>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <!-- ======== REPAIRS TAB ======== -->
    <div id="tab-repairs" class="tab-content <?php echo $active_tab === 'repairs' ? 'active' : ''; ?>">
        <?php if($repair_count === 0): ?>
            <div class="empty-state">📭 No repair requests submitted yet.</div>
        <?php else: ?>
        <?php while($row = mysqli_fetch_assoc($repairs)): ?>
        <div class="request-card status-<?php echo strtolower(str_replace(' ','-',$row['status'])); ?>">

            <div class="card-header">
                <div class="card-header-left">
                    <span class="request-id">#R<?php echo $row['repair_id']; ?></span>
                    <span class="customer-name">
                        <?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>
                    </span>
                    <span class="status-badge status-<?php echo strtolower(str_replace(' ','-',$row['status'])); ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                    </span>
                </div>
                <span class="card-date"><?php echo date('M d, Y g:i A', strtotime($row['created_at'])); ?></span>
            </div>

            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">📧 Email</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📱 Phone</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['phone']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">👓 Glasses Type</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['glasses_type']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">📅 Drop-off Date</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['dropoff_date'] ?: '—'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">🕐 Drop-off Time</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['dropoff_time'] ?: '—'); ?></span>
                    </div>
                    <div class="info-item full">
                        <span class="info-label">🔧 Repair Types</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['repair_types']); ?></span>
                    </div>
                    <?php if(!empty($row['damage_description'])): ?>
                    <div class="info-item full">
                        <span class="info-label">💥 Damage Description</span>
                        <span class="info-value"><?php echo htmlspecialchars($row['damage_description']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if(!empty($row['admin_reply'])): ?>
                <div class="previous-reply">
                    <span class="reply-label">💬 Your Previous Reply:</span>
                    <p><?php echo htmlspecialchars($row['admin_reply']); ?></p>
                </div>
                <?php endif; ?>

                <form method="POST" action="process_orders.php?tab=repairs" class="reply-form">
                    <input type="hidden" name="type" value="repair">
                    <input type="hidden" name="id" value="<?php echo $row['repair_id']; ?>">
                    <div class="reply-row">
                        <div class="reply-status-wrap">
                            <label>Update Status</label>
                            <select name="status">
                                <option <?php echo $row['status']==='Pending'           ? 'selected':'' ?>>Pending</option>
                                <option <?php echo $row['status']==='In Progress'       ? 'selected':'' ?>>In Progress</option>
                                <option <?php echo $row['status']==='Ready for Pickup'  ? 'selected':'' ?>>Ready for Pickup</option>
                                <option <?php echo $row['status']==='Completed'         ? 'selected':'' ?>>Completed</option>
                                <option <?php echo $row['status']==='Cancelled'         ? 'selected':'' ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="reply-text-wrap">
                            <label>Reply to Customer</label>
                            <textarea name="admin_reply" placeholder="Type your reply here..."><?php echo htmlspecialchars($row['admin_reply'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="contact-hint">
                        📞 Contact via: <strong><?php echo htmlspecialchars($row['phone']); ?></strong>
                        &nbsp;|&nbsp; 📧 <strong><?php echo htmlspecialchars($row['email']); ?></strong>
                    </div>
                    <button type="submit" name="submit_reply" class="btn-reply">
                        💾 Save Reply & Status
                    </button>
                </form>

            </div>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
    </div>

</div>

<!-- ============ FOOTER ============ -->
<footer>
    <p>© 2026 Optical Management System — Admin Panel</p>
</footer>

<script>
function switchTab(tab, btn) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
}
</script>

</body>
</html>
<?php
include 'db.php';
session_start();

// Check if the user is logged in and has access to view the report
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied. Only administrators can access this page.";
    exit;
}

// Fetch access logs from the database
$stmt = $pdo->prepare("SELECT access_log.id, users.username, access_log.access_time, access_log.action
                       FROM access_log
                       JOIN users ON access_log.user_id = users.id
                       ORDER BY access_log.access_time DESC");
$stmt->execute();
$access_logs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Access Report</h2>

        <?php if (count($access_logs) > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Access Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($access_logs as $log) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['id']); ?></td>
                            <td><?php echo htmlspecialchars($log['username']); ?></td>
                            <td><?php echo htmlspecialchars($log['access_time']); ?></td>
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No access logs found.</p>
        <?php } ?>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>

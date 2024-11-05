<?php
// Connect to the database
include '../config.php';

// Sanitize sort input to avoid SQL injection
$allowed_sort_columns = ['domain', 'email', 'status'];
$sort = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort_columns) ? $_GET['sort'] : 'domain';

// Fetch users from the database
$query = "SELECT user_id, domain, email, license, status, status FROM users ORDER BY $sort";
$stmt = sqlsrv_query($conn, $query);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }

        h2{
            display: flex;
            justify-content: center; 
            align-items: center; 
            text-align: center; 
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            border: 3px solid darkgray;
            padding: 8px;
            text-align: left;
        }
        th {
            border: 3px solid darkgray;
            border-bottom: 3px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #ffcd43;
        }
        button {
            background-color: #ffcd43;
            border: none;
            border-radius: 10px;
            color: black;
            cursor: pointer;
            transition: background-color 0.3s ease;
            padding: 5px 10px;
            margin: 5px;
        }
        button:hover {
            background-color: #f2b93a;
        }
    </style>
</head>
<body>
    <h2>User Management Dashboard</h2>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=domain">Domain</a></th>
                <th>Email</th>
                <th>Status</th>
                <th>License</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display each row
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['domain']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['status']}</td>";
                echo "<td>";
                if ($row['domain'] === 'hawker') {
                    echo "<a href='../Register/{$row['license']}'>View License</a>";
                } else {
                    echo "N/A";
                }
                echo "</td>";
                echo "<td>";
                if ($row['status'] === 'active') {
                    echo "<button onclick='suspendUser({$row['user_id']})'>Suspend</button>";
                }
                if ($row['status'] === 'suspended') {
                    echo "<button onclick='activateUser({$row['user_id']})'>Activate</button>";
                }
                if ($row['domain'] === 'hawker' && $row['status'] === 'pending') {
                    echo "<button onclick='approveHawker({$row['user_id']})'>Approve</button>";
                    echo "<button onclick='rejectHawker({$row['user_id']})'>Reject</button>";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function suspendUser(userId) {
            if (confirm("Are you sure you want to suspend this user?")) {
                window.location.href = "suspend_user.php?user_id=" + userId;
            }
        }

        function activateUser(userId) {
            if (confirm("Are you sure you want to activate this user?")) {
                window.location.href = "activate_user.php?user_id=" + userId;
            }
        }

        function approveHawker(userId) {
            if (confirm("Are you sure you want to approve this hawker?")) {
                window.location.href = "approve_hawker.php?user_id=" + userId;
            }
        }

        function rejectHawker(userId) {
            if (confirm("Are you sure you want to reject this hawker?")) {
                window.location.href = "reject_hawker.php?user_id=" + userId;
            }
        }
    </script>
</body>
</html>

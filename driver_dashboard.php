<?php
// driver_dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] != 'driver') {
    echo "<script>navigate('login.php');</script>";
}
require 'db.php';

$rides = $pdo->query("SELECT * FROM rides WHERE status = 'pending'")->fetchAll();
$deliveries = $pdo->query("SELECT * FROM deliveries WHERE status = 'pending'")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $table = $type == 'ride' ? 'rides' : 'deliveries';
    $stmt = $pdo->prepare("UPDATE $table SET driver_id = ?, status = 'accepted' WHERE id = ?");
    $stmt->execute([$_SESSION['user_id'], $id]);

    // Insert initial tracking data
    $latitude = rand(30000000, 40000000) / 1000000; // Simulated coordinates
    $longitude = rand(70000000, 80000000) / 1000000;
    $tracking_stmt = $pdo->prepare("INSERT INTO tracking (ride_id, delivery_id, latitude, longitude) VALUES (?, ?, ?, ?)");
    if ($type == 'ride') {
        $tracking_stmt->execute([$id, null, $latitude, $longitude]);
    } else {
        $tracking_stmt->execute([null, $id, $latitude, $longitude]);
    }

    echo "<script>alert('$type accepted!'); navigate('track.php');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - Careem Clone</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            animation: slideIn 1s ease-in-out;
        }
        .request {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .btn {
            background: #f1c40f;
            color: #000;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #e67e22;
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @media (max-width: 768px) {
            .request { font-size: 0.9em; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Driver Dashboard</h1>
        <h2>Pending Rides</h2>
        <?php foreach ($rides as $ride): ?>
            <div class="request">
                <p>Pickup: <?php echo htmlspecialchars($ride['pickup_location']); ?> | Dropoff: <?php echo htmlspecialchars($ride['dropoff_location']); ?> | Fare: $<?php echo $ride['fare']; ?></p>
                <form method="POST">
                    <input type="hidden" name="type" value="ride">
                    <input type="hidden" name="id" value="<?php echo $ride['id']; ?>">
                    <button type="submit" class="btn">Accept Ride</button>
                </form>
            </div>
        <?php endforeach; ?>
        <h2>Pending Deliveries</h2>
        <?php foreach ($deliveries as $delivery): ?>
            <div class="request">
                <p>Pickup: <?php echo htmlspecialchars($delivery['pickup_location']); ?> | Dropoff: <?php echo htmlspecialchars($delivery['dropoff_location']); ?> | Details: <?php echo htmlspecialchars($delivery['package_details']); ?> | Fare: $<?php echo $delivery['fare']; ?></p>
                <form method="POST">
                    <input type="hidden" name="type" value="delivery">
                    <input type="hidden" name="id" value="<?php echo $delivery['id']; ?>">
                    <button type="submit" class="btn">Accept Delivery</button>
                </form>
            </div>
        <?php endforeach; ?>
        <button class="btn" onclick="navigate('profile.php')">Manage Profile</button>
        <button class="btn" onclick="navigate('logout.php')">Logout</button>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

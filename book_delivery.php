<?php
// book_delivery.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] != 'user') {
    echo "<script>navigate('login.php');</script>";
}
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $package_details = $_POST['package_details'];
    $fare = rand(15, 60); // Simulated fare calculation

    $stmt = $pdo->prepare("INSERT INTO deliveries (user_id, pickup_location, dropoff_location, package_details, fare) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $pickup, $dropoff, $package_details, $fare]);

    // Simulate email notification
    $user = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $user->execute([$_SESSION['user_id']]);
    $email = $user->fetchColumn();
    echo "<script>alert('Delivery booked! Confirmation sent to $email'); navigate('track.php');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Delivery - Careem Clone</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            width: 400px;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        input::placeholder, textarea::placeholder {
            color: #ccc;
        }
        .btn {
            background: #e67e22;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #f1c40f;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @media (max-width: 768px) {
            .form-container { width: 90%; }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Book a Delivery</h2>
        <form method="POST">
            <input type="text" name="pickup" placeholder="Pickup Location" required>
            <input type="text" name="dropoff" placeholder="Dropoff Location" required>
            <textarea name="package_details" placeholder="Package Details" required></textarea>
            <button type="submit" class="btn">Book Delivery</button>
        </form>
        <button class="btn" onclick="navigate('dashboard.php')">Back to Dashboard</button>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] != 'user') {
    echo "<script>navigate('login.php');</script>";
}
require 'db.php';

$stmt = $pdo->prepare("SELECT name, wallet_balance FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Careem Clone</title>
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
            text-align: center;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            animation: slideIn 1s ease-in-out;
        }
        .options {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .option-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            width: 300px;
            transition: transform 0.3s;
        }
        .option-card:hover {
            transform: translateY(-10px);
        }
        .btn {
            background: #f1c40f;
            color: #000;
            padding: 10px 20px;
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
            .option-card { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
        <p>Wallet Balance: $<?php echo number_format($user['wallet_balance'], 2); ?></p>
        <div class="options">
            <div class="option-card">
                <h3>Book a Ride</h3>
                <button class="btn" onclick="navigate('book_ride.php')">Book Now</button>
            </div>
            <div class="option-card">
                <h3>Send a Parcel</h3>
                <button class="btn" onclick="navigate('book_delivery.php')">Send Now</button>
            </div>
            <div class="option-card">
                <h3>Manage Wallet</h3>
                <button class="btn" onclick="navigate('wallet.php')">Go to Wallet</button>
            </div>
        </div>
        <button class="btn" onclick="navigate('index.php')">Logout</button>
    </div>
    <script>
        function navigate(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>

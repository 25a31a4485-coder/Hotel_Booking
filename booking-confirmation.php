<?php
// booking-confirmation.php
require_once 'config.php';

$reference = $_GET['ref'] ?? '';

if (empty($reference)) {
    header('Location: booking.html');
    exit;
}

$conn = getDBConnection();
$reference = $conn->real_escape_string($reference);
$result = $conn->query("SELECT * FROM bookings WHERE booking_reference = '$reference'");

if ($result->num_rows === 0) {
    $error = "Booking not found";
} else {
    $booking = $result->fetch_assoc();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - GRAND HOTEL</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <style>
        body {
            background: #f0f6fc;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 16px 32px rgba(0,30,60,0.12);
        }
        h1 {
            color: #0a1c2e;
            border-left: 8px solid #0f2f4f;
            padding-left: 20px;
            font-family: 'Playfair Display', serif;
        }
        .success-icon {
            color: #28a745;
            font-size: 64px;
            text-align: center;
            margin: 20px 0;
        }
        .reference-box {
            background: #e1edf7;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .reference-box h2 {
            color: #0a1c2e;
            font-size: 28px;
            margin: 10px 0;
            letter-spacing: 2px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 30px 0;
        }
        .detail-item {
            padding: 15px;
            background: #f9f9f9;
            border-radius: 15px;
        }
        .detail-label {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #0a1c2e;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .total {
            background: #0a1c2e;
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: right;
            font-size: 1.5rem;
            margin: 30px 0;
        }
        .btn {
            background: #0a1c2e;
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 60px;
            font-size: 1.2rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }
        .btn:hover {
            background: #1f4b75;
        }
        @media (max-width: 600px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Booking Confirmed!</h1>
        
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php else: ?>
            <div class="reference-box">
                <p>Your booking reference number:</p>
                <h2><?php echo $booking['booking_reference']; ?></h2>
                <p>Please save this number for future reference</p>
            </div>
            
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Guest Name</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['guest_name']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['guest_email']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Check-in</div>
                    <div class="detail-value"><?php echo date('F j, Y', strtotime($booking['check_in_date'])); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Check-out</div>
                    <div class="detail-value"><?php echo date('F j, Y', strtotime($booking['check_out_date'])); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Room Type</div>
                    <div class="detail-value"><?php echo ucfirst(str_replace('_', ' ', $booking['room_type'])); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Guests</div>
                    <div class="detail-value"><?php echo $booking['adults']; ?> Adults, <?php echo $booking['children']; ?> Children</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Duration</div>
                    <div class="detail-value"><?php echo $booking['nights']; ?> nights</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value"><?php echo ucfirst($booking['payment_method']); ?></div>
                </div>
            </div>
            
            <div class="total">
                Total Amount: $<?php echo number_format($booking['total_amount'], 2); ?>
            </div>
            
            <p style="text-align: center;">
                A confirmation email has been sent to <strong><?php echo htmlspecialchars($booking['guest_email']); ?></strong>
            </p>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="booking.html" class="btn">Make Another Booking</a>
            <a href="home.html" class="btn" style="background: #6c757d; margin-left: 10px;">Return to Home</a>
        </div>
    </div>
</body>
</html>
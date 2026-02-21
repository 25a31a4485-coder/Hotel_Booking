<?php
// save_booking.php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

// Validate required fields
$required = ['guest_name', 'guest_email', 'room_type', 'check_in', 'check_out', 'adults', 'total_amount'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing field: $field"]);
        exit;
    }
}

$conn = getDBConnection();

// Sanitize inputs
$booking_reference = generateBookingReference();
$guest_name = $conn->real_escape_string($input['guest_name']);
$guest_email = $conn->real_escape_string($input['guest_email']);
$guest_phone = $conn->real_escape_string($input['guest_phone'] ?? '');
$room_type = $conn->real_escape_string($input['room_type']);
$check_in = $conn->real_escape_string($input['check_in']);
$check_out = $conn->real_escape_string($input['check_out']);
$adults = intval($input['adults']);
$children = intval($input['children'] ?? 0);
$nights = intval($input['nights']);
$room_price = floatval($input['room_price']);
$breakfast = isset($input['addons']['breakfast']) ? 1 : 0;
$spa = isset($input['addons']['spa']) ? 1 : 0;
$airport = isset($input['addons']['airport']) ? 1 : 0;
$dinner = isset($input['addons']['dinner']) ? 1 : 0;
$extras_total = floatval($input['extras_total']);
$taxes = floatval($input['taxes']);
$total_amount = floatval($input['total_amount']);
$payment_method = $conn->real_escape_string($input['payment_method'] ?? 'credit');
$special_requests = $conn->real_escape_string($input['special_requests'] ?? '');

$sql = "INSERT INTO bookings (
    booking_reference, guest_name, guest_email, guest_phone, room_type,
    check_in_date, check_out_date, adults, children, nights, room_price,
    breakfast_addon, spa_addon, airport_addon, dinner_addon, extras_total,
    taxes, total_amount, payment_method, special_requests
) VALUES (
    '$booking_reference', '$guest_name', '$guest_email', '$guest_phone', '$room_type',
    '$check_in', '$check_out', $adults, $children, $nights, $room_price,
    $breakfast, $spa, $airport, $dinner, $extras_total,
    $taxes, $total_amount, '$payment_method', '$special_requests'
)";

if ($conn->query($sql)) {
    echo json_encode([
        'success' => true,
        'message' => 'Booking saved successfully',
        'booking_reference' => $booking_reference,
        'booking_id' => $conn->insert_id
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $conn->error
    ]);
}

$conn->close();
?>
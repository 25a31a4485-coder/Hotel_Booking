<?php
// register.php
require_once 'config.php';

$error = '';
$success = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        // Check if email already exists
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Email already exists";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert user
            $insert_query = "INSERT INTO users (full_name, email, phone, password) VALUES ('$full_name', '$email', '$phone', '$hashed_password')";
            
            if (mysqli_query($conn, $insert_query)) {
                $success = "Registration successful! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRAND HOTEL | Register</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      font-family: 'Segoe UI', Roboto, system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .auth-card {
      background: white;
      border-radius: 28px;
      padding: 2.5rem;
      box-shadow: 0 16px 32px rgba(0, 30, 60, 0.12);
      width: 100%;
      max-width: 500px;
    }

    .auth-title {
      font-size: 2.2rem;
      color: #0a1c2e;
      margin-bottom: 2rem;
      text-align: center;
      font-family: 'Playfair Display', serif;
    }
    
    .auth-title i {
      color: #1f4b75;
      margin-right: 0.5rem;
    }

    .auth-form .form-group {
      margin-bottom: 1.5rem;
    }
    
    .auth-form label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #0f2f4f;
    }
    
    .auth-form input {
      width: 100%;
      padding: 0.9rem 1.2rem;
      border: 1px solid #96b8d4;
      border-radius: 60px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }
    
    .auth-form input:focus {
      outline: none;
      border-color: #1f4b75;
    }

    .auth-btn-submit {
      width: 100%;
      background: #0a1c2e;
      color: white;
      border: none;
      padding: 1rem;
      border-radius: 60px;
      font-size: 1.2rem;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
      border: 1px solid #608ab0;
      margin-top: 1rem;
    }
    
    .auth-btn-submit:hover {
      background: #1f4b75;
    }

    .auth-links {
      margin-top: 1.5rem;
      text-align: center;
    }
    
    .auth-links a {
      color: #1f4b75;
      text-decoration: none;
      font-weight: 600;
    }
    
    .auth-links a:hover {
      text-decoration: underline;
    }

    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
    }
    
    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #cbdbe9;
    }
    
    .divider span {
      padding: 0 1rem;
      color: #6c757d;
    }

    .back-home {
      text-align: center;
      margin-top: 1.5rem;
    }
    
    .back-home a {
      color: #0a1c2e;
      text-decoration: none;
    }
    
    .back-home a:hover {
      text-decoration: underline;
    }
    
    .error {
      background: #f8d7da;
      color: #721c24;
      padding: 1rem;
      border-radius: 50px;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .success {
      background: #d4edda;
      color: #155724;
      padding: 1rem;
      border-radius: 50px;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .password-hint {
      font-size: 0.85rem;
      color: #6c757d;
      margin-top: 0.3rem;
      margin-left: 1rem;
    }
  </style>
</head>
<body>
  <div class="auth-card">
    <h2 class="auth-title"><i class="fas fa-user-plus"></i> Create Account</h2>
    
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form class="auth-form" method="POST" action="">
      <div class="form-group">
        <label><i class="fas fa-user"></i> Full Name</label>
        <input type="text" name="full_name" placeholder="John Doe" required
               value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
      </div>
      
      <div class="form-group">
        <label><i class="fas fa-envelope"></i> Email Address</label>
        <input type="email" name="email" placeholder="your@email.com" required
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>
      
      <div class="form-group">
        <label><i class="fas fa-phone"></i> Phone Number (Optional)</label>
        <input type="tel" name="phone" placeholder="+1 234 567 8900"
               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
      </div>
      
      <div class="form-group">
        <label><i class="fas fa-lock"></i> Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
        <div class="password-hint">Minimum 6 characters</div>
      </div>
      
      <div class="form-group">
        <label><i class="fas fa-lock"></i> Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="••••••••" required>
      </div>
      
      <button type="submit" class="auth-btn-submit">Register</button>
    </form>
    
    <div class="divider">
      <span>OR</span>
    </div>
    
    <div class="auth-links">
      Already have an account? <a href="login.php">Login here</a>
    </div>
    
    <div class="back-home">
      <a href="homepage.php"><i class="fas fa-home"></i> Back to Home</a>
    </div>
  </div>
</body>
</html>
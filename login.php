<?php
// login.php
require_once 'config.php';

$error = '';
$success = '';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = "Please enter email and password";
    } else {
        // Query to check user
        $query = "SELECT id, full_name, email, password FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                
                // Redirect to homepage
                header("Location: homepage.php");
                exit();
            } else {
                $error = "Invalid email or password";
            }
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GRAND HOTEL | Login</title>
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
    }

    .auth-card {
      background: white;
      border-radius: 28px;
      padding: 2.5rem;
      box-shadow: 0 16px 32px rgba(0, 30, 60, 0.12);
      width: 100%;
      max-width: 450px;
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
  </style>
</head>
<body>
  <div class="auth-card">
    <h2 class="auth-title"><i class="fas fa-sign-in-alt"></i> Welcome Back</h2>
    
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form class="auth-form" method="POST" action="">
      <div class="form-group">
        <label><i class="fas fa-envelope"></i> Email Address</label>
        <input type="email" name="email" placeholder="your@email.com" required 
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>
      <div class="form-group">
        <label><i class="fas fa-lock"></i> Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
      </div>
      
      <button type="submit" class="auth-btn-submit">Login</button>
    </form>
    
    <div class="divider">
      <span>OR</span>
    </div>
    
    <div class="auth-links">
      Don't have an account? <a href="register.php">Register here</a>
    </div>
    
    <div class="back-home">
      <a href="homepage.php"><i class="fas fa-home"></i> Back to Home</a>
    </div>
  </div>
</body>
</html>
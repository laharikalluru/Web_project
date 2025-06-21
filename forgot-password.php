<?php
session_start();
include("includes/db.php");
include("includes/header.php");

if(isset($_POST['reset'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database
        $update_query = "UPDATE users SET reset_token = '$token', reset_expiry = '$expiry' WHERE email = '$email'";
        mysqli_query($conn, $update_query);
        
        // In a real application, you would send an email with the reset link
        // For now, we'll just show a success message
        $success = "If an account exists with this email, you will receive password reset instructions.";
    } else {
        // Don't reveal if email exists or not for security
        $success = "If an account exists with this email, you will receive password reset instructions.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Little Learners Emporium</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .forgot-container {
            max-width: 400px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .forgot-header h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .forgot-header p {
            color: #666;
            font-size: 0.9em;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #5db075;
            outline: none;
        }

        .reset-btn {
            width: 100%;
            padding: 12px;
            background: #5db075;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .reset-btn:hover {
            background: #4a8f5e;
        }

        .forgot-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .forgot-footer a {
            color: #5db075;
            text-decoration: none;
        }

        .forgot-footer a:hover {
            text-decoration: underline;
        }

        .success-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9em;
            text-align: center;
        }

        .info-box {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }

        .info-box h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1em;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
        }

        .info-box li {
            margin: 5px 0;
            padding-left: 20px;
            position: relative;
        }

        .info-box li:before {
            content: "•";
            color: #5db075;
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <h1>Forgot Password?</h1>
            <p>Enter your email address and we'll send you instructions to reset your password.</p>
        </div>

        <?php if(isset($success)): ?>
            <div class="success-message">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="forgot-password.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="Enter your registered email">
            </div>

            <button type="submit" name="reset" class="reset-btn">Send Reset Instructions</button>
        </form>

        <div class="info-box">
            <h3>What happens next?</h3>
            <ul>
                <li>We'll send you an email with reset instructions</li>
                <li>Click the link in the email to reset your password</li>
                <li>Create a new password and you're done!</li>
            </ul>
        </div>

        <div class="forgot-footer">
            <p>Remember your password? <a href="login.php">Back to Login</a></p>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>
</body>
</html> 
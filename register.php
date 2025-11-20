<?php
session_start();
include_once 'includes/footer.php'; // Only footer, no navbar for a focused register page

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (!$username || !$email || !$password) {
        $error = 'All fields are required.';
    } else {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = 'Email already registered.';
        } else {
            // For demo: store password as plain text (hash in production)
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $username, $email, $password);
            if ($stmt->execute()) {
                $success = 'Registration successful! You can now <a href=\"login.php\">login</a>.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Sambodhan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #b2dfdb 0%, #e0f7fa 100%);
            font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0,0,0,0.13);
            max-width: 370px;
            width: 100%;
            padding: 38px 32px 32px 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .register-logo {
            width: 60px;
            height: 60px;
            background: #e0f7fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            font-size: 2.2rem;
            color: #009688;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .register-title {
            font-size: 1.7rem;
            font-weight: 700;
            color: #00796b;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }
        .register-desc {
            color: #555;
            font-size: 1.05rem;
            margin-bottom: 22px;
            text-align: center;
        }
        form {
            width: 100%;
        }
        label {
            display: block;
            margin-bottom: 7px;
            color: #234;
            font-weight: 500;
            font-size: 1rem;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 11px;
            border-radius: 8px;
            border: 1px solid #b2dfdb;
            margin-bottom: 18px;
            font-size: 1rem;
            background: #f7fafc;
            transition: border 0.2s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border: 1.5px solid #009688;
            outline: none;
        }
        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }
        .register-btn, .login-btn {
            flex: 1;
            background: #009688;
            color: #fff;
            border: none;
            padding: 11px 0;
            border-radius: 8px;
            font-size: 1.08rem;
            font-family: 'Montserrat', Arial, sans-serif;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background 0.18s, box-shadow 0.18s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .register-btn:hover, .login-btn:hover {
            background: #00796b;
        }
        .error {
            color: #c62828;
            background: #ffebee;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 18px;
            text-align: center;
            width: 100%;
        }
        .success {
            color: #388e3c;
            background: #e8f5e9;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 18px;
            text-align: center;
            width: 100%;
        }
        @media (max-width: 500px) {
            .register-card { padding: 22px 6vw 22px 6vw; }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="register-logo">🧠</div>
        <div class="register-title">Create Account</div>
        <div class="register-desc">Join Sambodhan and start your journey<br>toward better mental health.</div>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <div class="button-row">
                <button type="submit" class="register-btn">Register</button>
                <a href="login.php" class="login-btn">Login</a>
            </div>
        </form>
    </div>
    <?php include_once 'includes/footer.php'; ?>
</body>
</html> 
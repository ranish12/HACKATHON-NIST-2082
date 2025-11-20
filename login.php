<?php
session_start();
include_once 'includes/footer.php'; // Only footer, no navbar for a focused login page

// Database connection
$conn = new mysqli('localhost', 'root', '', 'sambodhan');
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // First, check if is_banned column exists
    $check_column = $conn->query("SHOW COLUMNS FROM users LIKE 'is_banned'");
    $has_ban_column = $check_column && $check_column->num_rows > 0;

    if ($has_ban_column) {
        // Query user by email with ban check
        $stmt = $conn->prepare("SELECT id, username, email, password, is_banned FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $db_email, $db_password, $is_banned);
            $stmt->fetch();
            
            // Check if user is banned
            if ($is_banned) {
                $error = 'Your account has been banned. Please contact the administrator for more information.';
            } else {
                // For demo: password is plain text, but should be hashed in production
                if ($password === $db_password) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $db_email;
                    // Set is_admin session variable
                    if ($db_email === 'admin@sambodhan.com') {
                        $_SESSION['is_admin'] = true;
                        header('Location: admin/dashboard.php');
                        exit();
                    } else {
                        $_SESSION['is_admin'] = false;
                        header('Location: index.php');
                        exit();
                    }
                } else {
                    $error = 'Incorrect password.';
                }
            }
        } else {
            $error = 'No user found with that email.';
        }
        $stmt->close();
    } else {
        // Fallback: Query without ban check (for older databases)
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $db_email, $db_password);
        $stmt->fetch();
            
        // For demo: password is plain text, but should be hashed in production
        if ($password === $db_password) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $db_email;
            // Set is_admin session variable
            if ($db_email === 'admin@sambodhan.com') {
                $_SESSION['is_admin'] = true;
                header('Location: admin/dashboard.php');
                exit();
            } else {
                $_SESSION['is_admin'] = false;
                header('Location: index.php');
                exit();
            }
        } else {
            $error = 'Incorrect password.';
        }
    } else {
        $error = 'No user found with that email.';
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
    <title>Login | Sambodhan</title>
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
        .login-card {
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
        .login-logo {
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
        .login-title {
            font-size: 1.7rem;
            font-weight: 700;
            color: #00796b;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }
        .login-desc {
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
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 11px;
            border-radius: 8px;
            border: 1px solid #b2dfdb;
            margin-bottom: 18px;
            font-size: 1rem;
            background: #f7fafc;
            transition: border 0.2s;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            border: 1.5px solid #009688;
            outline: none;
        }
        .button-row {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }
        .submit-btn, .register-btn {
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
        .submit-btn:hover, .register-btn:hover {
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
        @media (max-width: 500px) {
            .login-card { padding: 22px 6vw 22px 6vw; }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">🧠</div>
        <div class="login-title">Welcome Back</div>
        <div class="login-desc">Log in to Sambodhan<br>and take a step toward self-care.</div>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <div class="button-row">
                <button type="submit" class="submit-btn">Login</button>
                <a href="register.php" class="register-btn">Register</a>
            </div>
        </form>
    </div>
    <?php include_once 'includes/footer.php'; ?>
</body>
</html> 
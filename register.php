<?php
ob_start(); // Start output buffering

require 'db.php'; // Include the database connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password hashing and validation
    if ($password === $confirm_password) {
        // Check if the email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email = :email";
        $checkStmt = $conn->prepare($checkEmailQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            // Email already exists
            echo "This email is already registered. Please use a different email or try to login.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                // Redirect to index.html upon successful registration
                header("Location: /9.code/pages"); // Update with correct relative path
                exit();
            } else {
                echo "Error: Could not execute the query.";
            }
        }
    } else {
        echo "Passwords do not match!";
    }
}

ob_end_flush(); // End output buffering
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts for custom font styles -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Base reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #ff6ec4, #7873f5);
            color: #333;
            overflow: hidden;
        }

        .register-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            animation: fadeIn 1.2s ease-in-out;
        }

        .register-container::before, .register-container::after {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            border-radius: 50%;
            background: rgba(255, 110, 196, 0.15);
            z-index: -1;
        }

        .register-container::before {
            top: -20%;
            left: 20%;
            animation: rotate-bg 8s linear infinite;
        }

        .register-container::after {
            bottom: -20%;
            right: 20%;
            animation: rotate-bg 6s linear infinite reverse;
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes rotate-bg {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h2 {
            font-weight: 600;
            color: #ff6ec4;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .form-header p {
            font-size: 1rem;
            color: #666;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .form-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1.5rem;
            padding-left: 2.5rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #ff6ec4;
            box-shadow: 0 0 5px rgba(255, 110, 196, 0.5);
        }

        .form-group i {
            position: absolute;
            left: 1rem;
            color: #ff6ec4;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            background: #ff6ec4;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background: #7873f5;
            transform: scale(1.05);
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
            color: #666;
        }

        .login-link a {
            color: #ff6ec4;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #7873f5;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .register-container {
                padding: 1.5rem;
            }
        }

    </style>
</head>
<body>

<div class="register-container">
    <div class="form-header">
        <h2>Register</h2>
        <p>Create an account to get started</p>
    </div>
    <form action="register.php" method="POST">
        <div class="form-group">
            <i class="fas fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn">Sign Up</button>
    </form>
    <div class="login-link">
        Already have an account? <a href="login.php">Log in</a>
    </div>
</div>

</body>
</html>

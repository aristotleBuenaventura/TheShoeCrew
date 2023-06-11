<?php
include 'config.php';

if (isset($_POST["verify_email"])) {
    $email = $_POST["email"];
    $verification_code = $_POST["verification_code"];

    // connect with database

    // mark email as verified
    $sql = "UPDATE registration SET email_verified_at = NOW() WHERE email = '" . $email . "' AND verification_code = '" . $verification_code . "'";
    $result  = mysqli_query($conn, $sql);

    if (mysqli_affected_rows($conn) == 0) {
        die("Verification code failed.");
    }

    echo "<p>Your email has been successfully verified. You can now <a href='login.php'>proceed to login</a>.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        p.success-message {
            color: #4CAF50;
            text-align: center;
            margin-top: 20px;
        }
        p.login-link {
            text-align: center;
            margin-top: 10px;
        }
        p.login-link a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Email Verification</h1>
        <form method="post" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="verification_code">Verification Code:</label>
            <input type="text" id="verification_code" name="verification_code" required>
            <button type="submit" name="verify_email">Verify</button>
        </form>
        <?php if (isset($_POST["verify_email"])): ?>
            <p class="success-message">Your email has been successfully verified. You can now <a href="login.php">proceed to login</a>.</p>
        <?php endif; ?>
    
    </div>
</body>
</html>

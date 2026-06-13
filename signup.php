<?php
include "config.php";

if(isset($_POST['signup'])){

    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(fullname, username, password)
            VALUES('$fullname','$username','$password')";

    if(mysqli_query($conn, $sql)){
        echo "Signup Successful!";
    } else {
        echo "Error!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>

<div class="login-page">
    <div class="back-home">
        <a href="index.php"><button type="button">← Back to Home</button></a>
    </div>

    <div class="login-left">
        <img src="logo1.png" alt="Logo" class="login-logo">
        <h2 class="login-subtitle">Join our community and discover frames that suit your style. Sign up now and make every look unforgettable.</h2>
    </div>

    <div class="login-right">
        <div class="form-box">
            <h2>Sign Up</h2>

            <form method="POST">
                <input type="text" name="fullname" placeholder="Full Name" required>

                <input type="text" name="username" placeholder="Username" required>

                <input type="password" name="password" placeholder="Password" required>

                <button type="submit" name="signup">Sign Up</button>
            </form>

            <a href="login.php">Already have an account?</a>
        </div>
    </div>
</div>

</body>
</html>

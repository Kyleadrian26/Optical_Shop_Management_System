<?php
session_start();

include "config.php";

$errorMsg = '';
if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] == 'admin'){

                header("Location: admin_dashboard.php");
                exit();

            } elseif($row['role'] == 'cashier'){

                header("Location: cashier_dashboard.php");
                exit();

            } elseif($row['role'] == 'customer'){

                header("Location: customer_dashboard.php");
                exit();

            }

        } else {

            $errorMsg = "Wrong password!";

        }

    } else {

        $errorMsg = "User not found!";

    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Login</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">

</head>
<body>

<div class="login-page">
    <div class="back-home">
        <a href="index.php"><button type="button">← Back to Home</button></a>
    </div>

    <div class="login-left">
        <img src="logo1.png" alt="Logo" class="login-logo">
        <h2 class="login-subtitle">Love at first sight. Wait until you see our new arrivals.
             Drop by and let's find your perfect fit.</h2>
    </div>

    <div class="login-right">
        <div class="form-box">

            <h2>Login</h2>

            <form method="POST">

                <input
                type="text"
                name="username"
                placeholder="Username"
                required>

                <div class="password-field">
                    <input
                    id="password-input"
                    type="password"
                    name="password"
                    placeholder="Password"
                    required>
                    <button type="button" class="password-toggle" aria-label="Show password">👁</button>
                </div>
                <?php if(!empty($errorMsg)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errorMsg); ?></div>
                <?php endif; ?>

                <button
                type="submit"
                name="login">
                Login
                </button>

            </form>

            <a href="signup.php">
            Create Account
            </a>

        </div>
    </div>

</div>

<script>
const passwordInput = document.getElementById('password-input');
const passwordToggle = document.querySelector('.password-toggle');
if (passwordToggle && passwordInput) {
    passwordToggle.addEventListener('click', function() {
        const showPassword = passwordInput.type === 'password';
        passwordInput.type = showPassword ? 'text' : 'password';
        this.classList.toggle('active', showPassword);
        this.textContent = '👁';
        this.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
    });
}
</script>
</body>
</html>
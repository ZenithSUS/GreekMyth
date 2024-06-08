<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="../styles/forgot.css">
    <!-- Title -->
    <title>Account Recovery</title>
</head>
<body>
    <!-- Forgot Password Container -->
    <div class="container">
        <!-- Forgot Password Form -->
        <form action="../actions/forgot_act.php" method="post" class="forgotForm">
            <!-- Heading or Title -->
            <h1>Account Recovery</h1>
            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter email">
            </div>
            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" name="submit">Recover</button>
            </div>
            <!-- Error Message -->
            <?php if(isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <!-- Auth Options -->
            <div class="auth-options">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>
</body>
</html>
<?php
// DATABASE CONNECTION
include("config.php");
session_start();

// USERS LOGIN
if(isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // CHECK IF THE INPUT IS EMPTY
    if(empty($email) || empty($password)) {
        $loginError[] = "Please fill in all fields.";
    } 
    else {
        // QUERY FOR USERS EMAIL
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // EMAIL AND PASSWORD VERIFICATION BEFORE LOGINN
        if($row && password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: note.php");
            exit();
        } 
        else {
            // ERROR IF THE EMAIL OR THE PASSWORD IS INCORRECT
            $loginError[] = "Incorrect email or password";
        }
    }
}

// USERS REGISTRATION
if(isset($_POST["register_submit"])) {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // CHECK IF THE INPUT IS EMPTY
    if(empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $registerError[] = "Please fill in all fields.";
    } 
    else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // DOUBLE CHECK OF THE EMAIL ALREADY EXIST OR NOT
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0) {
            $registerError[] = "You are trying to register with an already-existing email";
        } 
        else {
            // INSERTING THE NEW PASSWORD AND EMAIL IN DATABASE
            $stmt = mysqli_prepare($conn, "INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $hashedPassword);
            mysqli_stmt_execute($stmt);
        }
    }
}

// FORGOT PASSWORD
if(isset($_POST['forgot_password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // CHECK IF BOTH PASSWORDS ARE PROVIDED
    if(isset($password) && isset($confirm_password)) {
        // CHECK IF THE PASSWORD MATCH
        if($password == $confirm_password) {
            // HASH THE PASSWORD
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // UPDATE USERS PASSWORD
            $stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
            if(mysqli_stmt_execute($stmt)) {
                // IF SUCCES TO CHANGE THE PASSWORD THE MESSAGE APPEAR
                $message[] = "Password has been reset successfully.";
            } 
            else {
                $message[] = "Error updating password.";
            }
        } 
        else {
            // THIS MESSAGE IF THE CONFIRMATION IS NOT RIGHT
            $message[] = "Passwords do not match.";
        }
    } 
    else {
        $message[] = "Please provide both password and confirm password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x=icon" href="./assets/ibon.png">
    <!-- ICONS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- STYLE -->
    <link rel="stylesheet" href="./styles/style.css">
    <title>Techtilanotes | Login</title>
    </title>
</head>
<body>
    <div class="wrapper">
        <!-- NAV BAR -->
        <nav class="nav">
            <div class="nav-logo">
                <img src="./assets/ibon.png" alt="">
                <div>
                    <p>Techtilanotes</p>
                </div>
            </div>
            <div class="nav-menu" id="navMenu">
                <ul>
                    <li>
                        <a href="#" class="link active">Home</a>
                    </li>
                    <li>
                        <a href="#" class="link">About</a>
                    </li>
                    <li>
                        <a href="#" class="link">Contact</a></a>
                    </li>
                </ul>
            </div>
            <!-- BUTTON FOR REGISTRATION AND LOGIN -->
            <div class="nav-button">
                <button class="btn white-btn" id="loginBtn" onclick="login()">Sign In</button>
                <button class="btn" id="registerBtn" onclick="register()">Sign Up</button>
            </div>
            <div class="nav-menu-btn">
                <i class="bx bx-menu" onclick="myMenuFunction()"></i>
            </div>
        </nav>
        <div class="form-box">

            <!-- FORGOT PASSWORD FORM -->
            <form action="" class="forgot-container" id="forgot" method="POST">
                <div class="top">
                    <header>Forgot password</header>
                </div>
                <div class="input-box">
                    <input type="email" class="input-field" placeholder="Email" name="email" id="email">
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" placeholder="New Password" name="password" id="password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" placeholder="Confirm Password" name="confirm_password" id="confirm_password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <!-- FORGOT PASSWORD BUTTON -->
                <div class="input-box">
                    <input type="submit" class="submit" value="Update" name="forgot_password">
                </div>
                <div class="two-col">
                    <div class="one">
                        <span>Have an account? <a href="#" onclick="login()">Login</a></span>
                    </div>
                </div>
            </form>

            <!-- LOGIN FORM -->
            <form action="" class="login-container" id="login" method="POST">
                <div class="top">
                    <header>Login</header>
                </div>

                <!-- SHOW MESSAGE FOR ALL ERROR OR CHECK -->
                <?php
                if (isset($loginError)) {
                    foreach ($loginError as $loginError) {
                        echo '<span class="error-msg">' . $loginError . '</span>';
                    };
                }

                if (isset($registerError)) {
                    foreach ($registerError as $registerError) {
                        echo '<span class="error-msg">' . $registerError . '</span>';
                    };
                }

                if (isset($message)) {
                    foreach ($message as $message) {
                        echo '<span class="error-msg">' . $message . '</span>';
                    };
                }
                ?>
                <div class="input-box">
                    <input type="email" class="input-field" placeholder="Email" name="email" id="email">
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" placeholder="Password" name="password" id="password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <!-- BUTTON LOGIN -->
                <div class="input-box">
                    <input type="submit" class="submit" value="Sign In" name="login_submit">
                </div>
                <div class="two-col">
                    <div class="one">
                        <span>Don't have an account? <a href="#" onclick="register()">Sign Up</a></span>
                    </div>
                    <div class="two">
                        <label><a href="#" onclick="forgot()">Forgot password?</a></label>
                    </div>
                </div>
            </form>

            <!-- REGISTRATION FORM -->
            <form action="" class="register-container" id="register" method="POST">
                <div class="top">
                    <header>Sign Up</header>
                </div>
                <div class="two-forms">
                    <div class="input-box">
                        <input type="text" class="input-field" placeholder="Firstname" name="firstName" id="firstName">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-box">
                        <input type="text" class="input-field" placeholder="Lastname" name="lastName" id="lastName">
                        <i class="bx bx-user"></i>
                    </div>
                </div>
                <div class="input-box">
                    <input type="email" class="input-field" placeholder="Email" name="email" id="email">
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="input-box">
                    <input type="password" class="input-field" placeholder="Password" name="password" id="password">
                    <i class="bx bx-lock-alt"></i>
                </div>
                <!-- BUTTON REGISTER -->
                <div class="input-box">
                    <input type="submit" class="submit" value="Register" name="register_submit">
                </div>
                <div class="two-col">
                    <div class="one">
                        <span>Have an account? <a href="#" onclick="login()">Login</a></span>
                    </div>
            </form>
        </div>
    </div>
    
    <!-- SCRIPT FOR CHANGING POSITION OF FORMS -->
    <script src="./script/script.js"></script>

</body>
</html>
<?php 
    require_once("db_creds.php");
    require_once("InputCleaner.php");
    require_once("Users.php");
    require_once("ErrorMessages.php");

$user = new Users($sqlcon);

if(isset($_POST["reg_submit"])){

    $username = InputCleaner::cleanUsername($_POST["username"]);
    $password = InputCleaner::cleanPassword($_POST["password"]);

    $wasSuccessful = $user->login($username, $password);


    if($wasSuccessful) {
        $_SESSION["userLoggedIn"] = $username;
        header("Location: index.php");
    }

}

function getInputValue($name) {
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>HoardBoard.com - Register</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="login.css">
       
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>
        <!-- container for entire page -->
        <div class="login__container">
            <!-- container for registration box in center -->
            <div class="login__box">
                <!-- container for header above input form -->
                <div class="login__header">
                    <!-- Website Logo -->
                    <a class="logo__container" href="index.php">
                        <img src="imgs/logo.png" title="logo" alt="Site Logo">
                    </a>

                    <!-- Message -->
                    <h3 class="reg__tag">Log In</h3>
                    <span>Your Hoard awaits!</span>
                </div>

                <!-- container for registration form -->
                <div class="login__form">
                    <!-- Submit form for input fields, all fields must be filled before submission -->
                    <form class="login__fields" method="POST" action="login.php">
                        <!-- Username. 'required' keyword prevents form from submitting if empty -->
                        <?php echo $user->getErr(ErrorMessages::$loginFailed); ?>
                        <input type="text" name="username" placeholder="Your username" value="<?php getInputValue('username'); ?>" required>
                        
                        <!-- Password -->
                        <input type="password" name="password" placeholder="Your password" required>
                    
                        <!-- Submit button -->
                        <input type="submit" name="reg_submit" value="Submit">
                    </form>
                </div>
                
                <!-- Link to login if you already have an acct -->
                <a class="register_link" href="register.php">Not in the Hoard yet? Register</a>
            </div>
        </div>
    </body>
</html>
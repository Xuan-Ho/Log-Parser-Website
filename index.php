<?php

    require_once('mysql_connection.php');
    
    /*********************************************************************
    *
    *PHP FUNCTIONS
    *
    *********************************************************************/
    
    //Sanitizing function
    function test_input($input_data)
    {
        $input_data = trim($input_data);
        $input_data = stripcslashes($input_data);
        $input_data = htmlspecialchars($input_data);
        return $input_data;
    }
    
    //Directing to another page
    function page_redirect($location = NULL)
    {
        if($location != NULL)
        {
            header("Location: {$location}");
            exit();
        }
    }
   
    
    
    //start seession if no session
    if(!isset($_SESSION))
    {
        session_start();
    }
    
    $message = "";
    $error = "";
    
    //If signout is true, end session.
    if(isset($_GET["logoff_pressed"]) == true)
    {
        session_destroy();
        echo "<script type=\"text/javascript\"> alert('You Have Been Successfully Logged Out!'); </script>";
    }
    //For Salting Password Inputs
    $salt1 = "qm&h*";
    $salt2 = "pg!@";
    
    /*********************************************************************
    *
    *Login Authentication
    *
    *********************************************************************/
    if(isset($_POST['submit']) == "Sign In" && isset($_POST['login_email']) && isset($_POST['login_password']))
    {
        //Sanitizing login email and password inputed values before send to database
        $login_email = $database->mysql_prep($_POST['login_email']);
        $login_password = $database->mysql_prep($_POST['login_password']);
        
        //hashing and salting  user's login input password
        $login_password = hash('ripemd128', "$salt2$login_password$salt1");
        
        //Creating Queries for matching  email and password inputs with the one on database for authentication
        $user_login_query = "Select * from user where email = '$login_email' and password = '$login_password'  ";
        
        $query_result1 = $database->query($user_login_query);
        
        $tuple1 = $database->fetch_array($query_result1);
        
        /**
        *Alert if login Email and Password fields are empty
        *Redirecting to uploadpage.php after successful login authentication
        **/
        if( empty($login_email) || empty($login_password) )
        {
            echo "<script> alert('Please Enter: Username and Password'); </script>";
        }
        else if($tuple1)
        {
            $_SESSION['id'] = $tuple['id'];
            page_redirect("uploadpage.php");
            
        }
        else
        {
            echo "<script type=\"text/javascript\"> alert('Invalid Username And Password Combination!'); </script>";
            
        }
    }
    
    /*********************************************************************
    *
    *Form Form Validation
    *
    *********************************************************************/
    //Initializing Variables
    $name = $email = $password = $confirm_password = $name_error = $email_error = $password_error = $confirm_password_error = $success = "";
    //Form Validation
    if(isset($_POST['submit']) =='Sign Up' && isset($_POST['name']) &&isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password']))
    {
        //Sanitizing Form's inputs
        $name = $database->mysql_entities_fix_string(test_input($_POST['name']));
        $email = $database->mysql_entities_fix_string(test_input($_POST['email']));
        //Form empty inputs catcher
        if(empty($_POST['name']))
        {
            $error .= "Please Enter Your Name!<br>";
        }
        if(empty($_POST['email']))
        {
            $error .= "Please Enter Your Email!<br>";
        }
        if(empty($_POST['password']))
        {
            $error .= "Please Enter Your Password!<br>";
        }
        if (!preg_match("/^[A-Za-z]*$/", $name)) {
            $error .= "Name Can Only Have Letters And White Space<br>";
        }
        if (!((strpos($email, ".") > 0) && (strpos($email, "@") > 0)) || preg_match("/[^a-zA-Z0-9.@_-]/", $email))
        {
            $error .= "The Email address is invalid!<br>";
        }
        /*
        *Password Confirmation and Validation
        *If form's password and confirm password are matched and not have empty fields
        *change this later
        */
        if(($_POST["password"] == $_POST["confirm_password"]) && !empty($_POST['password']))
        {
            //Sanitizing
            $password = $database->mysql_entities_fix_string(test_input($_POST["password"]));
            
            $cconfirm_password = $database->mysql_entities_fix_string(test_input($_POST["confirm_password"]));

            //password input constraints validation
            if (strlen($_POST["password"]) < '6')
            {
                $error .= "Password Must Have At Least 6 Characters!<br>";
            }
            elseif(!preg_match("#[a-z]+#",$password))
            {
                $error .= "Your Password Must Contain At Least 1 Lowercase Letter!<br>";
            }
            elseif(!preg_match("#[A-Z]+#",$password))
            {
                $error .= "Password Must Contain At Least 1 Capital Letter!<br>";
            }
            elseif(!preg_match("#[0-9]+#",$password))
            {
                $error .= "Password Must Have At Least 1 Number!<br>";
            }

            //password and confirm password matching error catcher
            if($_POST["password"] !== $_POST["confirm_password"])
            {
                $error .= "Password did not match<br>";
            }

            //hashing and salting Form's password
            $password = hash('ripemd128', "$salt2$password$salt1");


            //check if email already registered
            if($error == "")
            {
                $check_duplicate = $database->query("select * from `user` where email = '".$database->mysql_prep($email)."'");
                $username_result = $database->num_rows($check_duplicate);
                $query_insert = "";
                $statement = null;
                
                if (!$username_result)
                {
                    $query_insert = 'insert into user(name, email, password) values(?, ?, ?)';
                    $statement = $database->prepare_query($query_insert);
                    $statement->bind_param('sss', $name, $email, $password);
                    echo "<script type=\"text/javascript\">alert('You have been successfully registered. Please login');</script>";
                    $statement->execute();
                    $statement->close();
                }
                else
                {
                    $error = "Email Already Exist In Database! <br>Please Try Another Email";
                    
                }
            }
        }
    }
        $database->close_connection(); //close connection
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Homepage</title>

    <style>
        
        label{
            color:dodgerblue;
        }
        body{
            background-color:darkslategray;
            background-repeat: no-repeat;
            background-size: cover;
            align-content: center;
            
        }
        
        .navbar .dropdown-menu .form-control{
            width: 220px;
            
        }
        
        #form_text{
            text-align:center
        }
        #reg_form{
            
            align-content: center;
        }
     
    </style>
</head>

<body>
    <!-- Navigation bar BS$-->
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
        <div class="container">
            <!-- Brand -->
            <a href="index.php" class="navbar-brand" href="#">TeamDotCom</a>
            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navbar Elements -->
            <div class="collapse navbar-collapse" >
                <!-- Navbar links -->
                <ul class="navbar-nav mx">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
                    <i class="dropdown order-1">
                    <!-- Button: Login Form DropDown -->
                    <button type="button" data-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle">Login </button>
                        <ul class ="dropdown-menu dropdown-menu-right mt-2" >
                            <li class="px-3 py-2">
                                <form class="form" method = "post" >
                                    <!-- Email $ Password Input Fields -->
                                    <div id="login_dropdown"><input type="email" class="form-control form-control-sm" placeholder="Email" name="login_email" value="<?php if(isset($_POST['login_email'])){ echo $_POST['login_email']; }else{echo ""; } ?>" ></div>

                                    <!--Login Password Field-->
                                    <div id="login_dropdown"><input type="password" class="form-control form-control-sm"placeholder="Password" name="login_password" ></div>

                                    <!-- Login Button -->
                                    <div id="login_dropdown"><input type="submit" name="submit" class="btn btn-warning btn-block" value="LOG IN"></div>
                                </form>
                            </li>
                        </ul>
                    </i>
                </ul>
            </div>
        </div>
    </nav><br><br><br><br><br>

    
    <!-- Page Registration Form -->
    <div class="container">
        <div class="row">
            <div class="container .col-md-6 .offset-md-3" id="reg_form" >
                <div id="form_text">
                    <h1><kbd>LOG PARSER</kbd></h1>
                    <p>Sign Up For A Free Account!</p>
                </div>
                <form class="form" method="post" >
                    <!-- Name Field -->
                    <div class="form-group">
                        <label>Your Name</label><span class="error"> </span>
                        <input type="text" name="name" class="form-control" placeholder="Your Name" value="<?php if(isset($_POST['name'])){echo $_POST['name'];}else{echo "";}?>">
                    </div>
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email Address</label><span class="error"> </span>
                        <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php if(isset($_POST['email'])){echo $_POST['email'];}else{echo " ";}?>">
                    </div>
                    <!-- Password & Password Cnnfirmation  -->
                    <div class="form-group">
                        <label for="password">Password</label><span class="error"><?php echo "$password_error"; ?></span>
                        <input type="password" name="password" class="form-control" placeholder="Password">

                        <label for="confirm_password">Confirm Password</label><span class="error">*<?php echo "$confirm_password_error"; ?></span>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password">
                        <br>

                        <!-- Form Field' Error Notifications -->
                        <?php
                            if($error){echo '<div class="alert alert-danger">'.addslashes($error).'</div>';}
                            
                            if($success){echo '<div class="alert alert-success">'.addslashes($success).'</div>';}
                            
                            if($message) {echo '<div class="alert alert-success">'.addslashes($message).'</div>';}
                        ?>

                            <h2 class="bold " id="form_text">Sign Up And Parse Log For Free!</h2>
                            <div id="form_text">
                                <input type="submit" name="submit" value="SIGN UP" class="btn btn-success btn-lg">
                            </div>
                            <br>
                            <br>
                    </div>
                </form>
            </div>
        </div>
    </div>





</body>
</html>

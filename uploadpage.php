

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Upload Page</title>

    <style>
        body{
            background-color:white;
            background-repeat: no-repeat;
            background-size: cover;
            align-content: center;
            
            
            
        }
         #form_text{
            text-align:center
        }
        
        #invalid_file{
            color:red;
            
        }
        
    </style>
</head>

<body>

<script>
    function logoutAlert()
    {
        alert("You Have Successfully Log Out!");
    }
</script>
    
    
<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
 <div class="container">
    <!-- Brand -->
    <a class="navbar-brand" href="index.php">TeamDotCom</a>
    <!-- Toggler/collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Navbar Elements -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <!-- Navbar links -->
        <ul class="navbar-nav float-right">
            <li class="nav-item">
                <a class="nav-link" href="uploadpage.php">Home</a>
            </li>
        </ul>
        <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
            <div>
                <button type="button"  class="btn btn-outline-secondary" method="GET"><a href="index.php" onclick="logoutAlert()">Log Out</a></button>
            </div>
        </ul>
    </div>
 </div>
</nav><br><br><br><br><br>



<!-- Upload File -->
<div class="container" id="uploader_row">
    <div class="row" >
        <div class="container">
            <div id="form_text">
                <h1><kbd>LOG PARSER</kbd></h1>
                <p class="text-warning">Please Upload A Log File</p>
            </div>
            <form  method="POST" enctype="multipart/form-data">
                <!-- File Input -->
                <input type="file" name="selected_file"  class="input-large form-control bg-dark text-info">
                
                <h6 class='alert-danger' id="invalid_file"></h6><br>
    
                <!-- Upload Submit Button -->
                <input type="submit" name="upload_button" class="form-control btn btn-success btnAdmin" value="UPLOAD" >
            </form>
        </div>
    </div>
</div>
    
<br><br><br>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>


<?php
    /*
    ****REMEMBER TO EDIT YOUR PHP.INI TO SET THE MAX POST AND UPLOAD FILE SIZE!!!****
    *1) post_max_size=50M
    *2) upload_max_filesize=50M
    */

    /*start seession if no session
    if(!isset($_SESSION))
    {
        session_start();
    }
    */
        
    //Directing to another page
    function page_redirect($location = NULL)
    {
        if($location != NULL)
        {
            header("Location: {$location}");
            exit();
        }
    }

    //If Log Out is pressed, end session.
    if(isset($_GET['signout']))
    {
        session_destroy();
        echo "<script> alert('You Have Been Successfully Logged Out!'); </script>";
        page_redirect("index.php");
    }

    function execPy($pyName)
    {
        
        //echo $_SERVER['DOCUMENT_ROOT']; // C:\xampp\htdocs\Log Parser Website\app.py
        $path = getcwd(); // C:/xampp/htdocs
        $py_path = getcwd()."\app.py";
        
    }

    function no_space($path)
    {
        $spaceless_path= str_replace(' ', '%20', $path);
        return $spaceless_path;
    }

    //Validate if the the upload file is a ".txt" file
    function extensionValidation()
    {
        
        
        // Check file size
        if (isset($_FILES['selected_file']) && $_FILES['selected_file']['size'] > 50000000) 
        {
            $message = "Sorry, your file greater than 50MB and is too large.";
            echo "<script>
                    $('#invalid_file').html('$message');
                 </script>";

        }
        
        else if(isset($_FILES['selected_file'])) //if sumbit button is pressed
        {
            $file = $_FILES['selected_file']; //set selected uploaded file to a variable
            
            $file_error = $file['error']; //variable for upload error
            $file_name = $file['name']; //variable for uploaded filename (no extension)
            $file_size =$file['size']; // set uploaded file size to a variable
            $file_size_mb = $file_size/1000000;
            
            //Same as $_FILES["selectedFile"]["tmp_name"]
            //temp location of uploaded file without saving it locally
            $file_tmp_location = $file['tmp_name'];
            
            //get string or content of text tile
            $document = file_get_contents($file_tmp_location);

            //Set each part of the file's name, seperated by ".", into an array
            //[0] = filename, [1] = file extension
            $filenameArray = explode(".", $file_name);

            //the array's last index value is the real file type extension for test case
            //just in case there is a similar file name like "filename.txt.pdf"
            $realFileExtension = strtolower(end($filenameArray));
                
                
            $allowExtension = array("txt"); //array to hold allow extension
            
            
            
            //Set upload files location and directory
            $target_dir = "";
            $target_file = $target_dir.basename($file_name);
            if(move_uploaded_file($file_tmp_location, $target_file))
            {
                echo "File Upload Successful!!!<br>";
            }
            else
            {
                $message = 'Failed To Upload File!!!';
                echo "<script>
                    $('#invalid_file').html('$message');
                 </script>";
            }
            

            //If file extension and the allow extesion are the same and no upload error
            if(in_array($realFileExtension, $allowExtension) && $file_error == 0)
            {
                
                /*Testing
                echo '<h5 class="container">Successfully Uploaded The File'.$file_name.'!</h5>'."<br>";
                echo "$file_size_mb Megabytes"."<br>";
                echo $upload_file_path."<br>";
                echo $_SERVER['DOCUMENT_ROOT']."<br>";
                $env_var = 'C:\Users\XD\AppData\Local\Programs\Python\Python36-32';
                
                */
                
                //Execute Python analysis script on the uploaded file
                $upload_file_path = getcwd();
                $py_out = shell_exec("python ParseTester.py '$upload_file_path' ");
                echo "<div style='margin: 3rem;'><pre>'.$py_out.'</pre></div>";
                
                 //Delete Uploaded File After Exection
                 if (!unlink($file_name))
                 {
                    echo ("Error deleting $file_name");
                 }
                 else
                 {
                    echo ("Deleted $file_name");
                 }

            }
            else
            {
                $file = $_FILES['selected_file']; //set selected uploaded file to a variable
            
                $file_error = $file['error']; //variable for upload error
                $file_name = $file['name']; //variable for uploaded filename (no extension)
                $file_size =$file['size']; // set uploaded file size to a variable
                
                $message = 'Error: The Selected file is not a ".txt" extension file';
                
                echo "<script>
                        $('#invalid_file').html('$message');
                     </script>";
                
                //Delete Uploaded File After Exection
                 if (!unlink($file_name))
                 {
                    echo ("Error deleting $file_name");
                 }
                 else
                 {
                    echo ("Deleted $file_name");
                 }
            }

        }
        else
        {
            echo ""; //Do nothing
        }
     }
    
    //Activate Text File Validation
    extensionValidation();
    echo "<div style='margin: 1rem;'><img src='errors_chart.png' class='img-thumbnail .float-left' style='margin: 1rem;'><img src='usages_chart.png' class='img-thumbnail .float-right' style='margin: 1rem; '></div>";
    
    

?>

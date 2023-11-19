<?php
session_start();

@include 'config.php';

error_reporting(0);


$result = mysqli_query($conn,"SELECT * FROM registration WHERE email='$email'");
$resultCheck = mysqli_num_rows($result);

$roleDB = "";
if($resultCheck > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $emailDB = $row['email'];
    $roleDB = $row['role'];
  }
}

    
if (isset($_SESSION['username'])) {
    header("Location: index.html");
}

if (isset($_POST['submit'])) {
    session_start();
    
    ////////////////////////
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $user = mysqli_fetch_row($result);
    $user = $_POST['username'];
    $result = getUserByEmail($conn, $email, $password);
    if ($result->num_rows > 0) {
                $row = mysqli_fetch_row($result);
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $_POST['email'];////////////////////////
                header("Location: admin.php");
            } else {
                echo "<script>alert('Woops! Email or Password is Wrong.')</script>";
                // session_destroy();////////////////////////
            }
       

}

function getUserByEmail($conn, $email, $password) {
    $sql = "SELECT * FROM registration WHERE email='$email' AND password= '$password'";
    return mysqli_query($conn, $sql);
}

if (isset($_SESSION['email'])){
  $email = $_SESSION['email'];
} else {
  $email = "";
}   

if (isset($_GET['registration'])){
  $registration = $_GET['registration'];
} else {
  $registration = "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
     integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

</head>
<body>
<?php include 'header.php' ?>
    
<section class="my-5">
  <div class="container">
    <div class="row d-flex justify-content-center align-items-center h-50">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-2">

                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4">Login</p>
                <?php
                  if($registration == 'true'){
                      echo '<div class="container alert alert-success alert-dismissible fade show" role="alert">
                      <strong>Success!</strong> Your account has been successfully created. </div>';
                    };
                  ?> 

                <form class="mx-1 mx-md-4" method="post">
                  <div class="d-flex flex-row align-items-center">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="email" id="email" name="email" class="form-control mb-3" placeholder="Email" />
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center ">
                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" id="password" name="password" class="form-control mb-3" placeholder="Password" />
                    </div>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button name="submit" class="btn btn-danger btn-lg">Login</button>
                  </div>

                  <p class="ms-3">Don't have an account? <a href="registration.php" class="alink">Sign up</a>.</p>

                </form>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-1 d-flex justify-content-center">

                <img src="images/signin-image.png" class="img-fluid loginImage" >

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


    
            

    <!-- Footer  -->
    <?php include 'footer.php' ?>

  <script>
    function myFunction() {
      document.getElementById("myDropdown").classList.toggle("show");
    }

    window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }
  </script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
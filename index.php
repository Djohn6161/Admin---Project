<?php
require_once "database/config.php";
require_once("database/Query.php");
if(isset($_SESSION['name']) != ""){
    header("Location: reports/index.php");
  }
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $pass = mysqli_real_escape_string($link, $_POST['password']);

    $row = array();
    $row = sqlSelect("*","organizers","email = '$email' and password_hash = '$pass'");
    if($row  > 0){
        
        $_SESSION['name'] = $row['name'];
        $_SESSION['org_id'] = $row['id'];
        header("Location: reports/index.php");
      }
      else{
        $login_err = "Email or password not correct";
      }

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Event Backend</title>

    <base href="./">
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-6 mx-sm-auto px-4">
            <div class="pt-3 pb-2 mb-3 border-bottom text-center">
                <h1 class="h2">WorldSkills Event Platform</h1>
            </div>
            <?php 
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }        
            ?>
            <form class="form-signin" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post">
                <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

                <label for="inputEmail" class="sr-only">Email</label>
                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" autofocus>

                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password">
                <button class="btn btn-lg btn-primary btn-block" id="login" type="submit">Sign in</button>
            </form>

        </main>
    </div>
</div>
</body>
</html>

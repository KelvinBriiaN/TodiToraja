<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';
    include 'includes/header.php';
    $email = (isset($_POST['email']))?sanitize($_POST['email']) :'' ;
    $password = (isset($_POST['password']))?sanitize($_POST['password']) :'' ;
    //Removing blank spaces from both ends of the Password or email
    $email = trim($email);
    $password = trim($password);
    //$hashed = password_hash($password, PASSWORD_DEFAULT);
?>
<style>
  body {
    background-color: purple;
  }
</style>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
              <h3 class="text-center w3-text-white">Hotel & Tourism</h3>
                <div id="admin_login" style="margin-top:60px;" class="w3-card-12 w3-padding-large w3-white">
                <?php
                    if(isset($_POST['login'])){
                      if(empty($_POST['email']) || empty($_POST['password'])){
                        echo '<div class="w3-text-red text-center">Email and password are required to proceed.</div>';
                      } else {
                          //Ensuring password is 6 or more characters long
                          if(strlen($password) < 6){
                            echo '<div class="w3-text-red text-center">password must be at least 6 characters</div>';
                            } else {
                             //Check if Email exists in database
                              $sql = $db->query("SELECT * FROM users WHERE email = '$email' ");
                              $user = mysqli_fetch_assoc($sql);
                              $count = mysqli_num_rows($sql);
                              if ($count < 1){
                                echo '<div class="w3-text-red text-center">email not found in database.</div>';
                              } else {
                                if(password_verify($password, $user['password'])){
                                    echo '<div class="w3-text-red text-center">The password you entered was incorrect, please try again.</div>';
                                }else {
                                    //FINALLY LOG THE USER IN
                                    $userID = $user['id'];
                                    login($userID);
                                    //header("Location: index.php");
                                }
                              }
                          }
                      }
                    }

                ?>
                    <h3 class="text-center"></h3>
                    <form role="form" action="login.php" method="post">
                        <div class="form-group" >
                            <label for="email">Email:</label>
                            <input placeholder="Email here..." value="<?=$email;?>" name="email" type="email" class="form-control"/>
                        </div>

                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" class="form-control" placeholder="Password here..."/>
                        </div>
                        <input type="submit" name="login" class="w3-btn w3-indigo w3-btn-block w3-ripple" value="Login"/>
                    </form>
                    <br>
                </div>

            </div>
            <div class="col-md-3"></div>

        </div>
    </div>
<div class="breaks">

    <footer class="container-fluid text-center w3-text-white">
    	  <a href="#Home" title="To Top">
    	    <span class="glyphicon glyphicon-chevron-up"></span>
    	  </a>

    	  <p>&copy; Copyright 2003-<?php echo date("Y"); ?> Hotel & Tourism</a></p>
    </footer>

</div>

</body>
</html>

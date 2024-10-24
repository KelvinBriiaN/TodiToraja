<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';
    if(!is_logged_in()){
        login_error_check();
    }
    include 'includes/header.php';
    include 'includes/navigation.php';
    #header("Location: events.php");

    header("Location: events.php");
?>

<div class="w3-container w3-main" style="margin-left:200px">
    <header class="w3-container w3-purple">
     <span class="w3-opennav w3-xlarge w3-hide-large" onclick="w3_open()">☰</span>
     <h2 class="text-center">Home</h2>
   </header>




</div>



<script>
function w3_open() {
  document.getElementsByClassName("w3-sidenav")[0].style.display = "block";
}
function w3_close() {
  document.getElementsByClassName("w3-sidenav")[0].style.display = "none";
}
</script>

<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>

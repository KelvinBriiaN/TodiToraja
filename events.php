<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';

if(!is_logged_in()){
    login_error_check();
}

include 'includes/header.php';
include 'includes/navigation.php';
$sql = "SELECT * FROM events";
$result = $db->query($sql);

//DELETING AN EVENT FROM THE DATABASE
if(isset($_GET['delete']) && !empty($_GET['delete'])){
    $toDeleteID = $_GET['delete'];
    $sql = "DELETE FROM events WHERE id = '$toDeleteID' ";
    $db->query($sql);
    header("Location: events.php");
}

//EDITING AN EVENT
if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $_SESSION['edit'] = $_GET['edit'];
  $editID  = $_SESSION['edit'];
  header("Location: add_event.php?edit=$editID");
}
 ?>
 
 <div class="w3-container w3-main" style="margin-left:200px">
   <header class="w3-container w3-purple">
    <span class="w3-opennav w3-xlarge w3-hide-large" onclick="w3_open()">☰</span>
    <h2 class="text-center">Events</h2>
  </header>

   <?php
      if(isset($_SESSION['added_event'])){
          echo '<br>';
          echo $_SESSION['added_event'];
          unset($_SESSION['added_event']);
      }
   ?>
     <div class="page-header text-center">
         <h3><?php echo (mysqli_num_rows($result) <= 0)? 'There are no upcoming events' :'Your Upcoming events' ; ?></h3>
     </div>
     <div class="row col-sm-12">

             <a href="add_event.php" class="btn btn-md btn-primary pull-right">Add new event</a>
     </div><br>
     <div class="row">
         <br>
    <?php while($rows = mysqli_fetch_assoc($result)): ?>
         <div class="col-sm-3" >
             <div class="w3-card-4" >
                 <div class="">
                     <img src="<?= $rows['image']; ?>" style="width:100%; padding:0px; margin:0px;" alt="event image" class="img-responsive img-thumb">
                 </div>
                 <div class="w3-container text-justify">
                     <h4 class="text-center"><b><?=$rows['event_topic']; ?></b></h4>
                     <p>
                         <?=$rows['short_details']; ?>

                     </p>
                 </div>
                 <footer class="w3-container w3-blue w3-padding">
                   <div class="w3-center">
                     <a href="events.php?edit=<?=$rows['id'];?>" type="button" class="w3-btn w3-indigo">Edit</a>
                     <a href="events.php?delete=<?=$rows['id'];?>" type="button" class="w3-btn w3-red">Delete</a>
                   </div>
                 </footer>
             </div>
             <br>
         </div>
       <?php endwhile; ?>


     </div>
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

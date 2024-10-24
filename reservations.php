<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';
   //LOGGED IN CHECK
   if(!is_logged_in()){
       login_error_check();
   }

   include 'includes/header.php';
   include 'includes/navigation.php';
   $sql = "SELECT * FROM reservations";
   $result = $db->query($sql);
   $row_count = 1;

   if(isset($_GET['delete'])){
     $toDelete = $_GET['delete'];
     $sql = $db->query("DELETE FROM reservations WHERE id = '$toDelete' ");
     header("Location: reservations.php");
   }

 ?>
<div class="w3-container w3-main" style="margin-left:200px">
  <header class="w3-container w3-purple">
   <span class="w3-opennav w3-xlarge w3-hide-large" onclick="w3_open()">☰</span>
   <h2 class="text-center">Reservations</h2>
 </header>
  <div class="col-md-12">
    <br>
    <h2 class="text-center">Room Reservations</h2>
    <br />
  </div>
<div class="col-md-12">
  <table class="table table-striped table-condensed table-bordered">
      <thead>
          <tr>
              <th>#</th>
              <th>Name</th>
              <th>Room Number</th>
              <th>Checkin</th>
              <th>Checkout</th>
              <th>Phone</th>
              <th># of People</th>
              <th>Email</th>

              <th>Action</th>
          </tr>
      </thead>
      <tbody>
      <?php while($rows = mysqli_fetch_assoc($result)): ?>
          <tr>
              <td><?= $row_count++; ?></td>
              <td><?=$rows['name']; ?></td>
              <td><?=$rows['room']; ?></td>
              <td><?=$rows['checkin']; ?></td>
              <td><?=$rows['checkout']; ?></td>
              <td><?=$rows['phone']; ?></td>
              <td><?=$rows['people']; ?></td>
              <td><?=$rows['email']; ?></td>

              <td>
                  <a href="reservations.php?delete=<?=$rows['id'];?>" class="w3-btn w3-small w3-red"><span class="glyphicon glyphicon-trash"></span></a>

              </td>
          </tr>
        <?php endwhile;?>
      </tbody>
  </table>
</div>



</div>

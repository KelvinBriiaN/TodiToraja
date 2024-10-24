<?php

require_once 'core/core.php';
include 'includes/header.php';
include 'includes/navigation.php';


if(isset($_GET['room'])) {
  $roomID = $_GET['room'];
  $select = $db->query("SELECT * FROM rooms WHERE id = '{$roomID}' ");
  $s = $db->query("SELECT * FROM rooms WHERE id = '{$roomID}'");
####################################################################################
  if(isset($_POST['checkin'])) {
    if( !empty($_POST['fullname']) && !empty($_POST['in_date']) && !empty($_POST['out_date']) && !empty($_POST['phone']) && !empty($_POST['people']) )
    {

        $name = $_POST['fullname'];
        $checkin = $_POST['in_date'];
        $checkout = $_POST['out_date'];
        $phone = $_POST['phone'];
        $people = $_POST['people'];
        $email = $_POST['email'];
        @$address = $_POST['resident'];
        @$kin = $_POST['kin'];
        $r_number = mysqli_fetch_assoc($s);
        $r = $r_number['room_number'];
        $rooms = $_POST['rooms'];
        $current_date = date("Y-m-d");
 
        
        if($checkin >= $current_date){
          if($checkout >= $checkin){
          $insert = "INSERT INTO `reservations` (`id`, `name`, `checkin`, `checkout`, `phone`, `people`, `email`, `room`) VALUES (NULL, '$name', '$checkin', '$checkout', '$phone', '$people', '$email', '$r')";
            #$insert = "INSERT INTO `reservations` ('name`, `checkin`, `checkout`, `phone`, `people`, `email`, `room`, `kin`)
                #            VALUES ('$name', '$checkin', '$checkout', '$phone', '$people', '$email', '$r', '$kin')";
              $_SESSION['room_success'] = 'Reservation successfully made!';
              $save = $db->query($insert); 
                  if($save){
                    $rs = $db->query("SELECT * FROM rooms WHERE id = '{$roomID}' ");
                    $rms = mysqli_fetch_assoc($rs);
                    $newRooms = $rms['rooms'] - $rooms;
                    if($newRooms <= 0){
                      $newRooms = 0;
                    }
                    $update = $db->query("UPDATE rooms SET `rooms`='$newRooms' WHERE id = '{$roomID}' ");
                    header("Location: details.php?room=$roomID");
                  }
          
                  echo "<br /> <br />";
            } else {
              echo '<p class="text-center alert alert-danger">Invalid Check-out date provided. Please avoid using a past date.</p>';
            }

        } else {
          echo '<p class="text-center alert alert-danger">Invalid Check-in date provided. Please avoid using a past date.</p>';
        }

       
    }
    else
    {
        echo '<br /> All fields are required!';
    }
  }

} elseif( !(isset($_GET['room'])) || $_GET['room']=='' ) {
  header("Location: rooms.php");
}

?>

     <!-- Room details -->
<div class="container">
    <?php while($room = mysqli_fetch_assoc($select)): ?>
       <div class="page-header">
         <h2 class="text-center"><?= $room['room_number']; ?></h2>
       </div>

       <div class="row">
         <div class="col-md-6">
           <img class="" style="width:100%; height:400px" src="<?= $room['photo']; ?>">
         </div>


         <!-- Right collumn for details -->
         <div class="col-md-6">
           <hr />
           <p><b>Room Type:</b> <?= $room['type']; ?></p>
           <p><b>Room Price (per night):</b> <?= $room['price']; ?></p>
           <p><b>Available Rooms:</b> <?= $room['rooms']; ?></p>
           <p><b>Room details:</b> <?= $room['details']; ?></p>
           <?=($room['rooms'] <= 0)?'<p class="text-danger">reservations have been closed on this room!</p>':'';?>
         </div>
       </div>

       <!-- row for Booking form -->
       
          <div class="page-header">
            <h2 class="text-center">Booking details</h2>
          </div>


         <form action="details.php?room=<?=$roomID?>" method="POST">
              <div class="row">
                  
                    <div class="col">
                    <label class="form-control-label">Full name:</label>
                      <input type="text" class="form-control" name="fullname" placeholder="Full Name" <?=($room['rooms'] <= 0)?'readonly':'';?>>
                    </div>

                    <div class="col">
                     <label class="form-control-label">Check-in date:</label>
                      <input type="date" class="form-control" name="in_date" <?=($room['rooms'] <= 0)?'readonly':'';?>>
                    </div>
                  
                     <div class="col">
                      <label class="form-control-label">Check-out date:</label>
                      <input type="date" class="form-control" name="out_date" <?=($room['rooms'] <= 0)?'readonly':'';?>>
                    </div>

                    <div class="col">
                        <label class="form-control-label">Phone Number:</label>
                      <input type="text" class="form-control" name="phone" placeholder="Phone number..." <?=($room['rooms'] <= 0)?'readonly':'';?>>
                    </div>
                  
                     <div class="col">
                         <label class="form-control-label">People:</label>
                      <input type="number" class="form-control" max="5" name="people" <?=($room['rooms'] <= 0)?'readonly':'';?>>
                    </div>

                    <div class="col">
                         <label class="form-control-label"># of Rooms:</label>
                      <input type="number" class="form-control" name="rooms" <?=($room['rooms'] <= 0)?'readonly':'';?>>
                    </div>

                    <div class="col">
                        <label class="form-control-label">Email Address:</label>
                      <input type="email" class="form-control" name="email" placeholder="email address" <?=($room['rooms'] <= 0)?'readonly':'';?>>
                    </div>
                  
                     <div class="col-md-12">
                        <label></label>
                        <input type="submit" class="form-control btn btn-primary" value="Make Reservation" name="checkin" <?=($room['rooms'] <= 0)?'disabled':'';?>>
                    </div>
                  
                    

              </div>
         </form>
         <?php endwhile; ?>
        </div>
   
<br /><br /><br /><br />

<?php

require_once 'core/core.php';
include 'includes/header.php';
include 'includes/navigation.php';

if(isset($_GET['tour'])) {
  $tourID = $_GET['tour'];
  $select = $db->query("SELECT * FROM tourism WHERE id = '{$tourID}' ");
  $s = $db->query("SELECT * FROM tourism WHERE id = '{$tourID}' ");
  $data = mysqli_fetch_assoc($s);
####################################################################################

if(isset($_POST['reserve'])) {
  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['people']) && isset($_POST['number'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $people = $_POST['people'];
        $phone = $_POST['number'];

      $save = $db->query("INSERT INTO tour_reserves (tour_id,reservations,cus_name,`email`,`phone`)
                            VALUES ('$tourID','$people','$name','$email','$phone')");

      if($save){
        $newReservations = $data['reservations'] - $people;
        $update = $db->query("UPDATE tourism SET reservations = '$newReservations' WHERE id = '$tourID' ");
      }
      $_SESSION['tour_success'] = 'Reservation successfully made!';
      header("Location: tour.php?tour=$tourID ");


  } else {
    echo 'All fields are required!';
  }
}


} elseif( !(isset($_GET['tour'])) || $_GET['tour']=='' ) {
  header("Location: tourism.php");
}

?>

     <!-- Room details -->
<div class="container">
    <?php while($tour = mysqli_fetch_assoc($select)): ?>
       <div class="page-header">
         <h2 class="text-center"><?= $tour['title']; ?></h2>
       </div>

       <div class="row">
         <div class="col-md-6">
           <img class="" style="width:100%; height:400px" src="<?= $tour['photo']; ?>">
         </div>

         <!-- Right collumn for details -->
         <div class="col-md-6">
           <hr />
           <p><b>Location:</b> <?= $tour['location']; ?></p>
           <p><b>Price (per head):</b> K<?= $tour['price']; ?></p>
           <p><b>Tour details:</b> <?= $tour['details']; ?></p>
           <p><b>Reservations Remaining:</b> <?= $tour['reservations']; ?></p>
           <?=($tour['reservations'] <= 0)?'<p class="text-danger">reservations have been closed on this event!</p>':'';?>
           <hr />
           <div class="row">

              <div class="col-md-12">
                <div class="page-header">
                    <h2 class="text-center">Booking details</h2>
                </div>

                <form action="" method="POST" role="form">
                    <div class="row">

                    <div class="form-group col-md-6">
                        <label for=""></label>
                        <input type="text" name="name" class="form-control " id="" placeholder="Name" <?=($tour['reservations'] <= 0)?'readonly':'';?>>
                    </div>

                    <div class="form-group col-md-6">
                        <label for=""></label>
                        <input type="number" name="people" class="form-control" id="" placeholder="Number of people"  <?=($tour['reservations'] <= 0)?'readonly':'';?>>
                    </div>

                    <div class="form-group col-md-6">
                        <label for=""></label>
                        <input type="text" name="number" class="form-control" id="" placeholder="Contact Number"  <?=($tour['reservations'] <= 0)?'readonly':'';?>>
                    </div>

                    <div class="form-group col-md-6">
                        <label for=""></label>
                        <input type="text" name="email" class="form-control" id="" placeholder="Email Address"  <?=($tour['reservations'] <= 0)?'readonly':'';?>>
                    </div>

                    </div>

                    <button type="submit" name=" <?=($tour['reservations'] <= 0)?'readonly':'reserve';?>" class="btn btn-primary btn-block  <?=($tour['reservations'] <= 0)? 'disabled':'';?>">Book Now!</button>
                </form>

              </div>
           </div>

         </div>
       </div>
<?php endwhile; ?>

        </div>

<br /><br /><br /><br />

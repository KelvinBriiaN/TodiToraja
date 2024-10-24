<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';
    if(!is_logged_in()){
        login_error_check();
    }

include 'includes/header.php';
include 'includes/navigation.php';

if(@$_GET['edit'] && !empty(@$_GET['edit'])){
    $id = $_GET['edit'];
    $get = $db->query("SELECT * FROM rooms WHERE id = '$id' ");
    $edit = mysqli_fetch_assoc($get);

}

//VALIDATING AND MOVING OF FILE FROM TEMPORAL LOCATION TO INTENDED LOCATION
if(!empty($_FILES)){
   $fileName = @$_FILES['file']['name'];
   $ext = strtolower(substr($fileName, strpos($fileName,'.') + 1));
   $fileName = md5(microtime()).'.'.$ext;
   $type = @$_Files['file']['type'];
   $tmp_name = @$_FILES['file']['tmp_name'];

    if(($ext == 'jpg') || ($ext == 'jpeg') || ($ext == 'png') || ($ext == 'gif')){
       $location = $_SERVER['DOCUMENT_ROOT'].'/ht/images/';
       move_uploaded_file($tmp_name, $location.$fileName);
    } else {
      echo '<div class="w3-center w3-red">The image type must be jpg, jpeg, gif, or png.</div></br>';
    }
}

//INSERTING THE EVENT INFORMATION IN THE DATABASE
if(isset($_POST['submit'])){
  if(!empty($_POST['number']) && !empty($_POST['type']) && !empty($_POST['price']) && !empty($_POST['description'])){

        $number = $_POST['number'];
        $type = $_POST['type'];
        $price = $_POST['price'];
        $details = $_POST['description'];
        $rooms = $_POST['rooms'];
        #$photo = $_POST['photo']

        $image = 'images/'.$fileName;
        //INSERTING EVENT DETAILS IN THE DATABASE
        $sql = "INSERT INTO rooms (`room_number`,`type`,`price`,`details`,`photo`,`rooms`)
                  VALUES ('$number','$type','$price','$details','$image','$rooms') ";

        $query_run = $db->query($sql);
        if($query_run){
          $_SESSION['added_event'] = '<div class="w3-center w3-green">Room successfully added!</div></br>';
        }
        header("Location: rooms.php");
  } else {
    echo '<div class="w3-center w3-red">Please fill in all fields.</div></br>';
  }
}
//RUNNING UPDATE IF EDITING
else if(isset($_POST['update'])) {
    if(!empty($_POST['number']) && !empty($_POST['type']) && !empty($_POST['price']) && !empty($_POST['description'])){
      $number = $_POST['number'];
      $type = $_POST['type'];
      $price = $_POST['price'];
      $details = $_POST['description'];
      $rooms = $_POST['rooms'];

      @$image = 'images/'.$fileName;
      $toEditID = $_GET['edit'];
      $sqlSelect = $db->query("SELECT * FROM rooms WHERE id = '$toEditID' ");
      $row = mysqli_fetch_assoc($sqlSelect);

      if($row['photo']==''){
          $query = $db->query("UPDATE rooms SET `room_number`='$number',`photo`='$image',`type`='$type',`details`='$details',`price`='$price',`rooms`='$rooms' WHERE id = '$toEditID' ");
      } else {
        $query = $db->query("UPDATE rooms SET `room_number`='$number',`type`='$type',`details`='$details',`price`='$price',`rooms`='$rooms' WHERE id = '$toEditID' ");
      }

        $update = $db->query($query);
        header("Location: rooms.php");

  } else {
    echo '<div class="w3-center w3-red">Please fill in all fields.</div></br>';
  }
}

if(isset($_GET['delete_image'])){
    $toEditID= $_GET['delete_image'];
    $sql1 = $db->query("SELECT * FROM rooms WHERE id = '$toEditID'");
    $fetch = mysqli_fetch_assoc($sql1);
    $imageURL = $_SERVER['DOCUMENT_ROOT'].'/ht/'.$fetch['photo'];
    unlink($imageURL);
    ##################################################################
    $sql = "UPDATE rooms SET `photo` = '' WHERE id = '$toEditID' ";
    $db->query($sql);
    header("Location: add_room.php?edit=$toEditID");
}

?>

<div class="w3-container w3-main" style="margin-left:200px">
  <header class="w3-container w3-purple">
   <span class="w3-opennav w3-xlarge w3-hide-large" onclick="w3_open()">☰</span>
   <h2 class="text-center">Add a room</h2>
 </header>
<br/>
    <form class="form" action="#" method="post" enctype="multipart/form-data">

        <div class="form-group col-md-4">
            <label>Room Number:</label>
            <input type="text" class="form-control" value="<?= (isset($_GET['edit']))? ''.$edit['room_number'].'':''; ?>" name="number">
        </div>

        <div class="form-group col-md-4">
            <label>Room Type:</label>
            <input type="text" class="form-control" value="<?= (isset($_GET['edit']))? ''.$edit['type'].'':''; ?>" name="type">
        </div>

        <div class="form-group col-md-2">
            <label>Room Price:</label>
            <input type="text" class="form-control" value="<?= (isset($_GET['edit']))? ''.$edit['price'].'':''; ?>" name="price">
        </div>

        <div class="form-group col-md-2">
            <label># of rooms:</label>
            <input type="number" class="form-control" value="<?= (isset($_GET['edit']))? ''.$edit['rooms'].'':''; ?>" name="rooms">
        </div>

         <div class="form-group col-md-4">
         <?php if(isset($_GET['edit']) && !$edit['photo'] != ' '): ?>
                <figure>
                <h3>Event Image</h3>
                <img src="../<?=$edit['photo']; ?>" alt="event image" class="img-responsive">
                <figcaption>
                    <a href="add_room.php?delete_image=<?=$id;?>" class="w3-text-red">Delete Photo</a>
                </figcaption>
                </figure>
        <?php else: ?>
            <label>Room Image:</label>
            <input type="file" class="form-control" name="file">
        <?php endif; ?>
        </div>

         <div class="form-group col-md-4">
            <label>Description:</label>
             <textarea type="text" class="form-control" rows="6" name="description"> <?= (isset($_GET['edit']))? ''.$edit['details'].'':''; ?> </textarea>
        </div>

         <div class="form-group col-md-4">
            <label></label>
            <input type="submit" class="btn btn-block btn-lg btn-success" value="<?= (isset($_GET['edit']))? 'Update Room':'Add Room'; ?>" name="<?= (isset($_GET['edit']))? 'update':'submit'; ?>">
        </div>

        <?php if(isset($_GET['edit']) && !empty($_GET['edit'])): ?>
            <div class="form-group col-md-4">
            <label></label>
                <a class="btn btn-block btn-danger btn-lg" href="rooms.php">Cancel Edit</a>
        </div>


        <?php endif; ?>

    </form>
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

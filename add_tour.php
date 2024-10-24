<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';
if(!is_logged_in()){
    login_error_check();
}

include 'includes/header.php';
include 'includes/navigation.php';

//FIELD VARIABLES
@$topic = sanitize($_POST['topic']);
@$venue = sanitize($_POST['venue']);
@$date = sanitize($_POST['date']);
@$time = sanitize($_POST['time']);
@$sdetails = sanitize($_POST['sdetails']);
@$price = sanitize($_POST['price']);
@$reservations = sanitize($_POST['reservations']);
//The function nl2br() reserves line breaks
// @$fdetails = nl2br($_POST['fdetails']);

//VALIDATING AND MOVING OF FILE FROM TEMPORAL LOCATION TO INTENDED LOCATION
if(!empty($_FILES)){
   $fileName = @$_FILES['file']['name'];
   $ext = strtolower(substr($fileName, strpos($fileName,'.') + 1));
   $fileName = md5(microtime()).'.'.$ext;
   $type = @$_Files['file']['type'];
   $tmp_name = @$_FILES['file']['tmp_name'];

    if(($ext == 'jpg') || ($ext == 'jpeg') || ($ext == 'png') || ($ext == 'gif')){
       $location = BASEURL.'images/';
       move_uploaded_file($tmp_name, $location.$fileName);
    } else {
      echo '<div class="w3-center w3-red">The image type must be jpg, jpeg, gif, or png.</div></br>';
    }

}

//INSERTING THE EVENT INFORMATION IN THE DATABASE
if(isset($_POST['add'])){
  if(!empty($_POST['topic']) && !empty($_POST['venue']) && !empty($_POST['date']) &&
      !empty($_POST['time']) && !empty($_POST['sdetails']) && !empty($_POST['reservations'])
      && !empty($_POST['price']))
      {
          $image = 'images/'.$fileName;
          //INSERTING EVENT DETAILS IN THE DATABASE
          $sql = "INSERT INTO tourism (`title`,`photo`,`location`,`date`,`time`,`details`,`price`,`reservations`)
                    VALUES ('$topic','$image','$venue','$date','$time', '$sdetails','$price','$reservations') ";

          $query_run = $db->query($sql);
          if($query_run){
            $_SESSION['added_event'] = '<div class="w3-center w3-green">Tour Event successfully added!</div></br>';
          }
          header("Location: tours.php");
  } else {
    echo '<div class="w3-center w3-red">Please fill in all fields.</div></br>';
  }
}

//RUNNING UPDATE IF EDITING
else if(isset($_POST['update'])) {
  // Memastikan semua field terisi
  if(!empty($_POST['topic']) && !empty($_POST['venue']) && !empty($_POST['date']) &&
     !empty($_POST['time']) && !empty($_POST['sdetails']) && !empty($_POST['reservations']) && !empty($_POST['price'])){

      // Definisikan variabel yang dibutuhkan
      $topic = $_POST['topic'];
      $venue = $_POST['venue'];
      $date = $_POST['date'];
      $time = $_POST['time'];
      $sdetails = $_POST['sdetails'];
      $price = $_POST['price'];
      $reservations = $_POST['reservations'];
      
      // Mengambil ID dari URL
      $toEditID = $_GET['edit'];

      // Ambil data yang akan diedit
      $sqlSelect = $db->query("SELECT * FROM tourism WHERE id = '$toEditID'");
      $row = mysqli_fetch_assoc($sqlSelect);
      
      // Mengatur nama file gambar, periksa apakah gambar diunggah
      if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
          $fileName = $_FILES['image']['name'];
          $image = 'images/' . $fileName;
      } else {
          $image = $row['photo']; // Menggunakan gambar lama jika tidak ada gambar baru
      }

      // Query update
      $stmt = $db->prepare("UPDATE tourism SET title = ?, photo = ?, location = ?, date = ?, time = ?, details = ?, price = ?, reservations = ? WHERE id = ?");
      $stmt->bind_param("ssssssssi", $topic, $image, $venue, $date, $time, $sdetails, $price, $reservations, $toEditID);
      $stmt->execute();

      // Redirect setelah update
      header("Location: tours.php");
  } else {
      echo '<div class="w3-center w3-red">Please fill in all fields.</div></br>';
  }
}


//CODE TO EDIT AN events
if (isset($_GET['edit'])){
  $toEditID = $_GET['edit'];
  $sql = "SELECT * FROM tourism WHERE id = '$toEditID' ";
  $result = $db->query($sql);
  $rows = mysqli_fetch_assoc($result);
}

//Canceling EDITING
if(isset($_GET['cancelEdit'])){
    unset($_SESSION['edit']);
    header("Location: add_tour.php");
}

//DELETING IMAGE
if(isset($_GET['delete_image'])){
    $toEditID= $_GET['delete_image'];
    $sql1 = $db->query("SELECT * FROM tourism WHERE id = '$toEditID'");
    $fetch = mysqli_fetch_assoc($sql1);
    $imageURL = $_SERVER['DOCUMENT_ROOT'].'/ht'.$fetch['photo'];
    unlink($imageURL);
    ##################################################################
    $sql = "UPDATE tourism SET `photo` = '' WHERE id = '$toEditID' ";
    $db->query($sql);
    header("Location: add_tour.php?edit=$toEditID");
}
?>
<div class="w3-container w3-main" style="margin-left:200px">
  <header class="w3-container w3-purple">
   <span class="w3-opennav w3-xlarge w3-hide-large" onclick="w3_open()">☰</span>
   <h2 class="text-center">Add a tour</h2>
 </header>
<br/>

  <div class="row col-sm-12">
          <a href="tours.php" class="btn btn-md btn-primary pull-right">Go to tours</a>
  </div>
<br><br>
  <div class="row">

    <div class="col-md-9 w3-padding">

      <form class="form" method="POST" action="" enctype="multipart/form-data">

        <div class="col-sm-3 form-group">
          <label for="">Title:</label>
          <input type="text" name="topic" value="<?=(isset($toEditID))?''.$rows['title'].'' :'' ; ?>" class="form-control" placeholder="event topic">
        </div>

        <div class="col-sm-3 form-group">
          <label for="">Location:</label>
          <input type="text" name="venue" class="form-control" value="<?=(isset($toEditID))?''.$rows['location'].'' :'' ; ?>" placeholder="venue">
        </div>

        <div class="col-sm-3 form-group">
          <label for="">Date:</label>
          <input type="date" name="date" value="<?=(isset($toEditID))?''.$rows['date'].'' :'' ; ?>" class="form-control">
        </div>

        <div class="col-sm-3 form-group">
          <label for="">Price:</label>
          <input type="text" name="price" value="<?=(isset($toEditID))?''.$rows['price'].'' :'' ; ?>" class="form-control">
        </div>

        <div class="col-sm-3 form-group">
          <label for="">Time:</label>
          <input type="time" name="time" value="<?=(isset($toEditID))?''.$rows['time'].'' :'' ; ?>" class="form-control">
        </div>
        <?php if(!@$rows['photo'] || @$rows['photo']==''): ?>
          <div class="col-sm-3 form-group">
            <label for="">Photo:</label>
            <input type="file" class="form-control" name="file" id="file">
          </div>
        <?php endif;  ?>

        <div class="col-sm-3 form-group">
          <label for="">Reserve Spaces:</label>
          <input type="number" name="reservations" value="<?=(isset($toEditID))?''.$rows['reservations'].'' :'' ; ?>" class="form-control">
        </div>


        <div class="col-sm-6 form-group">
          <label for="">Tour Description:</label>
          <textarea name="sdetails" class="form-control" col="20" rows="5" ><?=(isset($toEditID))?''.$rows['details'].'' :'' ; ?></textarea>
        </div>

        <div class="col-sm-12">
          <input type="submit" name="<?=(isset($toEditID))?'update' :'add' ;?>" value="<?=(isset($toEditID))?'Edit Tour' :'Add Tour' ; ?>" class="w3-btn w3-indigo w3-btn-block"><br>
          <?php
              if(isset($toEditID)){
                echo '<br>';
                echo ' <a href="add_tour.php?cancelEdit='.$toEditID.'" type="button" name="cancelEdit" class="w3-btn w3-orange w3-btn-block">Cancel Edit</a>';
              }
           ?>
        </div>
      </form>
  </div>
    <div class="col-md-3">
      <?php if(isset($toEditID) && !$rows['photo'] != ' '): ?>
        <figure>
          <h3>Event Image</h3>
          <img src="../<?=$rows['photo']; ?>" alt="event image" class="img-responsive">
          <figcaption>
            <a href="add_tour.php?delete_image=<?=$toEditID;?>" class="w3-text-red">Delete Photo</a>
          </figcaption>
        </figure>
      <?php endif; ?>
    </div>
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

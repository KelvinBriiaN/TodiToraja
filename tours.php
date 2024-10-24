<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';
if(!is_logged_in()){
    login_error_check();
}

include 'includes/header.php';
include 'includes/navigation.php';
#header("Location: events.p$tour

$sql = $db->query("SELECT * FROM tourism");

if(isset($_GET['delete'])){
        $toDeleteRoom = $_GET['delete'];
        $sql1 = $db->query("SELECT * FROM tourism WHERE id = '$toDeleteRoom' LIMIT 1");
        $fetch = mysqli_fetch_assoc($sql1);
        $imageURL = $_SERVER['DOCUMENT_ROOT'].'/'.$fetch['photo'];
        unlink($imageURL);
        ##################################################################
        $sql = "DELETE FROM tourism WHERE id = '$toDeleteRoom' ";
        $db->query($sql);
        header("Location: tours.php");
    }


?>

<div class="w3-container w3-main" style="margin-left:200px">
  <header class="w3-container w3-purple">
   <span class="w3-opennav w3-xlarge w3-hide-large" onclick="w3_open()">☰</span>
   <h2 class="text-center">Tours</h2>
 </header>
    <div class="row"><br />
        <div class="col-md-12">
            <a href="add_tour.php" class="btn btn-primary pull-right">Add tour</a>
        </div>

        <?php while($tour = mysqli_fetch_assoc($sql)): ?>
            <div class="col-md-3">
                <h3 class="text-center"><?= $tour['title'];?></h3>
                <img src="../<?= $tour['photo'];?>" class="img-thumbnail" style="width:100%; height:200px" alt="pic">
                <div class="section">
                    <section>
                        <p><?= $tour['details'];?></p>
                    </section>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a href="add_tour.php?edit=<?=$tour['id'];?>" class="btn btn-primary btn-block ">Edit</a>
                    </div>
                    <div class="col-md-6">
                         <a href="tours.php?delete=<?=$tour['id'];?>" class="btn btn-danger btn-block">Delete</a>
                    </div>
                </div>
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

<?php
require_once 'core/core.php';
include 'includes/header.php';
include 'includes/navigation.php';

// Query untuk Rooms dan Tourism
$sql = $db->query("SELECT * FROM rooms LIMIT 4");
$tourSQL = $db->query("SELECT * FROM tourism LIMIT 4");
$result = $db->query("SELECT * FROM events"); // Pastikan event query sesuai
?>

<!-- Header -->
<header class="py-5 bg-image-full" style="background-image: url('images/slide-2.jpg'); height:300px"></header>

<!-- Content section -->
<section class="py-5">
  <div class="container">
    <h1>Tourism</h1>
    <div class="row">
      <?php if(mysqli_num_rows($tourSQL) > 0): ?>
        <?php while($tour = mysqli_fetch_assoc($tourSQL)): ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <h4 class="text-center"><?= $tour['title']; ?></h4>
            <img src="<?= $tour['photo']; ?>" class="img-responsive" alt="<?= $tour['title']; ?>" width="100%" height="200px">
            <section class="text-justify">
              <p><?= $tour['details']; ?></p>
              <a href="tour.php?tour=<?= $tour['id']; ?>" class="btn btn-block btn-primary">More Details</a>
            </section>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No tourism spots available at the moment.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Event Section -->
<div class="container">
  <div class="page-header text-center">
    <h3><?= (mysqli_num_rows($result) <= 0) ? 'There are no upcoming events' : 'Upcoming events'; ?></h3>
  </div>
  <div class="row">
    <?php if(mysqli_num_rows($result) > 0): ?>
      <?php while($rows = mysqli_fetch_assoc($result)): ?>
        <div class="col-sm-3">
          <div class="w3-card-4">
            <div>
              <img src="<?= $rows['image']; ?>" style="width:100%; height:200px;" alt="event image" >
            </div>
            <div class="w3-container text-justify">
              <h4 class="text-center"><b><?= $rows['event_topic']; ?></b></h4>
              <p><?= $rows['short_details']; ?></p>
            </div>
            <footer class="w3-container w3-blue w3-padding">
              <a href="view.php?view=<?= $rows['id']; ?>" class="w3-btn w3-black w3-btn-block">More details</a>
            </footer>
          </div>
          <br>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Rooms Section -->
<section class="py-5">
  <div class="container">
    <h1>Rooms</h1><hr />
    <div class="row">
      <?php if(mysqli_num_rows($sql) > 0): ?>
        <?php while($room = mysqli_fetch_assoc($sql)): ?>
          <div class="col-lg-3 col-md-4 col-sm-6">
            <h4 class="text-center"><?= $room['room_number']; ?></h4>
            <img src="<?= $room['photo']; ?>" class="img-responsive" alt="room" width="100%" height="200px">
            <section class="text-justify">
              <p><?= $room['details']; ?></p>
              <a href="details.php?room=<?= $room['id']; ?>" class="btn btn-block btn-primary">More Details</a>
            </section>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No rooms available at the moment.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Image Section -->
<section class="py-5 bg-image-full" style="background-image: url(images/slide-2.jpg);">
  <div style="height: 200px;"></div>
</section>

<!-- Footer -->
<footer class="py-5 bg-dark text-white">
  <div class="container">
    <p class="m-0 text-center">Copyright &copy; Hotel & Tourism</p>
  </div>
</footer>

<!-- JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/popper/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>

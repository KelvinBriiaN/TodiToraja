<?php
require_once 'core/core.php';
include 'includes/header.php';
include 'includes/navigation.php';

$sql = $db->query("SELECT * FROM tourism");

?>
    <!--END NAV SECTION -->
    <div class="container"><br />
      <div class="row">

    <?php while($tour = mysqli_fetch_assoc($sql)): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
          <h4 class="text-center"><?= $tour['title']; ?></h4>
          <img src="<?= $tour['photo']; ?>" class="img-responsive" alt="tour" width="100%" height="200px">
          <section class="text-justify">
            <p>
              <?= $tour['details']; ?>
            </p>
            <a href="tour.php?tour=<?= $tour['id']; ?>" class="btn btn-block btn-primary">More Details</a>
          </section>
        </div>
    <?php endwhile; ?>
      </div>
    </div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ht/core/core.php';
// LOGGED IN CHECK
if (!is_logged_in()) {
    login_error_check();
}

include 'includes/header.php';
include 'includes/navigation.php';

$sql = "SELECT * FROM tour_reserves";
$result = $db->query($sql);
$row_count = 1;

$tours = $db->query("SELECT * FROM tourism");

// Handle deletion
if (isset($_GET['delete'])) {
    $toDelete = intval($_GET['delete']); // Validate ID to avoid SQL injection
    $stmt = $db->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $toDelete); // Use prepared statement
    if ($stmt->execute()) {
        header("Location: tour_reserves.php?msg=success"); // Redirect with success message
    } else {
        echo "Error deleting record.";
    }
    $stmt->close();
}

if (isset($_POST['print'])) {
    $id = $_POST['tour'];
    header("Location: register.php?tour=$id");
}

if (isset($_POST['clear'])) {
    $id = intval($_POST['tour']);
    $del = $db->prepare("DELETE FROM tour_reserves WHERE tour_id = ?");
    $del->bind_param("i", $id);
    $del->execute();
    $del->close();
    header("Location: tour_reserves.php");
}
?>

<div class="w3-container w3-main" style="margin-left:200px">
  <header class="w3-container w3-purple">
   <span class="w3-opennav w3-xlarge w3-hide-large" onclick="w3_open()">☰</span>
   <h2 class="text-center">Reservations</h2>
 </header>
  <div class="col-md-12">
    <br>
    <h2 class="text-center">Tour Reservations</h2>
    <br />
  </div>
<div class="col-md-12">

<form class="form col-md-4" method="POST">
   <select class="form-control form-group" name="tour">
    <?php while($tour = mysqli_fetch_assoc($tours)): ?>
        <option value="<?=$tour['id'];?>"><?=$tour['title'];?> ~ <?= ($tour['reservations'] == 0) ? '(Fully Booked)' : '('.$tour['reservations'].' reserves remaining)';?></option>
    <?php endwhile; ?>
   </select>
   <button type="submit" name="print" class="btn btn-primary">Print register</button>
   <button type="submit" name="clear" class="btn btn-danger">Clear Records</button>
</form>

  <table class="table table-striped table-condensed table-bordered">
      <thead>
          <tr>
              <th>#</th>
              <th>Name</th>
             <th>Tour Event</th>
              <th>Phone</th>
              <th># of People</th>
              <th>Email</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
      <?php while($rows = mysqli_fetch_assoc($result)): ?>
      <?php 
        $id = $rows['tour_id'];
        $s = $db->query("SELECT * FROM tourism WHERE id = '{$id}' ");
        $data = mysqli_fetch_assoc($s);
      ?>
          <tr>
              <td><?= $row_count++; ?></td>
              <td><?=$rows['cus_name']; ?></td>
              <td><?=$data['title']; ?></td>
              <td><?=$rows['phone']; ?></td>
              <td><?=$rows['reservations']; ?></td>
              <td><?=$rows['email']; ?></td>

              <td>
                <a href="tour_reserves.php?delete=<?=$rows['id'];?>" class="w3-btn w3-small w3-red" onclick="return confirm('Are you sure you want to delete this reservation?');">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
          </tr>
        <?php endwhile;?>
      </tbody>
  </table>
</div>
</div>

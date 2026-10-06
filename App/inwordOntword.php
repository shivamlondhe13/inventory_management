<?php
$page = "inwordOntword";
// including the header component
include_once 'components/header.php';

$search = '';
if (isset($_GET["trash"])) {
  $trash = 1;
} else {
  $trash = 0;
}
if (isset($_GET["search"])) {
  // getting inword/outword data to show in table with serach function
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $inword_sql = "SELECT i.* , m.material_name AS material, c.category_name AS category FROM `inwords` AS i LEFT JOIN categories AS c ON c.category_id = i.category_name LEFT JOIN materials AS m ON m.material_id = i.material_name WHERE i.`is_deleted` = '$trash' AND m.material_name LIKE '%$search%'";
} else {
  // getting inword/outword data to show in table
  $inword_sql = "SELECT i.* , m.material_name AS material, c.category_name AS category FROM `inwords` AS i LEFT JOIN categories AS c ON c.category_id = i.category_name LEFT JOIN materials AS m ON m.material_id = i.material_name WHERE i.`is_deleted` = '$trash'";
}


$inword_result = mysqli_query($conn, $inword_sql);
$inwords = mysqli_fetch_all($inword_result, MYSQLI_ASSOC)

  ?>

<div class="container">
  <?= displayAlert() ?>
  <div class="my-1">
    <?php
    if (isset($_GET["trash"])) {
      ?>
      <a class="btn btn-light" href="inwordOntword">Exit Trash</a>
      <?php
    } else {
      ?>
      <a class="btn btn-secondary" href="inwordOntword?trash">View Trash</a>
      <?php
    }
    ?>

  </div>
  <div class="my-2">
    <form action="" method="get">
      <div class="input-group">
        <input value="<?= $search ?>" class="form-control" type="text" name="search" id="search"
          placeholder="Search Material">
        <button class="btn btn-primary">Search</button>
        <?php if (isset($_GET['search']) && !empty($_GET['search'])) { ?>
          <a href="inwordOntword" class="btn btn-info">View All</a>
        <?php } ?>
      </div>
    </form>
  </div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Sr.No.</th>
        <th>Material Category</th>
        <th>Material Name</th>
        <th>Quantity</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $srno = 1;
      foreach ($inwords as $i => $inword) {
        ?>
        <tr>
          <td><?= $srno++ ?></td>
          <td><?= $inword["category"] ?></td>
          <td><?= $inword["material"] ?></td>
          <td><?= $inword["quantity"] ?></td>
          <td><?= $inword["inword_date"] ?></td>
          <td>
            <?php
            if (!isset($_GET["trash"])) {
              ?>
              <a href="addinoutword?edit=<?= $inword["inword_id"] ?>" class="btn btn-primary">Edit</a>
              <button type="submit" name="delete_inword" inword_id="<?= $inword["inword_id"] ?>"
                class="btn btn-danger delete-inword" data-bs-toggle="modal" data-bs-target="#deleteinword">Delete</button>
              <?php
            } else {
              ?>
              <button type="submit" name="restore_inword" inword_id="<?= $inword["inword_id"] ?>"
                class="btn btn-danger restore-inword" data-bs-toggle="modal"
                data-bs-target="#Restoreinword">Restore</button>
              <?php
            }
            ?>

          </td>
        </tr>
        <?php
      }
      ?>

    </tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" id="deleteinword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="deleteinwordLabel" aria-hidden="true">
  <form class="modal-dialog" method="post" action="action/inwordoutword">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deleteinwordLabel">Alert!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="delete_id" name="inword_id" value="">
        <p>Are you sure you want to delete this inword/outword data?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="delete_inword" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </form>
</div>
<!-- Modal -->
<div class="modal fade" id="Restoreinword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="RestoreinwordLabel" aria-hidden="true">
  <form class="modal-dialog" method="post" action="action/inwordoutword">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="RestoreinwordLabel">Alert!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="Restore_id" name="inword_id" value="">
        <p>Are you sure you want to Restore this inword/outword data?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="Restore_inword" class="btn btn-danger">Restore</button>
      </div>
    </div>
  </form>
</div>

<script>
  $(document).ready(function () {
    //delete inword Handler
    $('.delete-inword').click(function () {
      var inwordId = $(this).attr('inword_id');
      $('#deleteinword #delete_id').val(inwordId);
    });
    //restore data Handler
    $('.restore-inword').click(function () {
      var inwordId = $(this).attr('inword_id');
      $('#Restoreinword #Restore_id').val(inwordId);
    });
  });

</script>

<?php

// including the foogter component
include_once 'components/footer.php';
?>
<?php
$page = "";
// including the header component
include_once 'components/header.php';

if (isset($_GET["trash"])) {
  $trash = 1;
} else {
  $trash = 0;
}
$search = '';
if (isset($_GET["search"])) {
  // getting material data to show in table with serach function
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $material_sql = "SELECT m.* , c.category_name  AS category_name , SUM(i.quantity) AS total_quantity FROM `materials` AS m LEFT JOIN `categories` AS  c ON c.category_id = m.material_category LEFT JOIN inwords as i ON i.material_name = m.material_id AND i.is_deleted=$trash WHERE m.`is_deleted`= $trash GROUP BY m.material_id AND m.`material_name` LIKE '%$search%'";
} else {
  // getting material data to show in table
  $material_sql = "SELECT m.* , c.category_name  AS category_name , SUM(i.quantity) AS total_quantity FROM `materials` AS m LEFT JOIN `categories` AS  c ON c.category_id = m.material_category LEFT JOIN inwords as i ON i.material_name = m.material_id AND i.is_deleted = $trash WHERE m.`is_deleted`= $trash GROUP BY m.material_id";
}

$material_result = mysqli_query($conn, $material_sql);
$materials = mysqli_fetch_all($material_result, MYSQLI_ASSOC);

?>
<div class="container py-3">
  <?= displayAlert() ?>




  <div class="p-2 container">
    <div class="my-1">
      <?php
      if (isset($_GET["trash"])) {
        ?>
        <a class="btn btn-light" href="index">Exit Trash</a>
        <?php
      } else {
        ?>
        <a class="btn btn-secondary" href="index?trash">View Trash</a>
        <?php
      }
      ?>

    </div>
    <div class="mb-2">
      <form action="" method="get">
        <div class="input-group">
          <input value="<?= $search ?>" class="form-control" type="text" name="search" id="search"
            placeholder="Search Material">
          <button class="btn btn-primary">Search</button>
          <?php if (isset($_GET['search']) && !empty($_GET['search'])) { ?>
            <a href="index" class="btn btn-info">View All</a>
          <?php } ?>
        </div>
      </form>
    </div>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Sr.No.</th>
          <th>Material Name</th>
          <th>Material Category</th>
          <th>Opening Balance</th>
          <th>Current Balance</th>

          <th>Action</th>


        </tr>
      </thead>
      <tbody>
        <?php
        $srno = 1;
        foreach ($materials as $i => $material) {
          ?>
          <tr>
            <td><?= $srno++ ?></td>
            <td><?= $material["material_name"] ?></td>
            <td><?= $material["category_name"] ?></td>
            <td><?= $material["opening_balance"] ?></td>
            <!-- adding inword/outword value with Opening Balance -->
            <td><?= $material["opening_balance"] + $material["total_quantity"] ?></td>
            <td>
              <?php
              if (!isset($_GET["trash"])) {
                ?>

                <a href="addMaterial?edit_id=<?= $material['material_id'] ?>" class="btn btn-primary">Edit</a>

                <button type="submit" name="delete_material" material_name="<?= $material["material_name"] ?>"
                  material_id="<?= $material["material_id"] ?>" class="btn btn-danger delete-Material"
                  data-bs-toggle="modal" data-bs-target="#deleteMaterial">Delete</button>


                <?php
              } else {
                ?>
                <button type="submit" name="restore_material" material_id="<?= $material["material_id"] ?>" material_name="<?= $material["material_name"] ?>"
                  class="btn btn-danger restore-material" data-bs-toggle="modal"
                  data-bs-target="#restoreMaterial">Restore</button>
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
  <div class="modal fade" id="deleteMaterial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="deleteMaterialLabel" aria-hidden="true">
    <form class="modal-dialog" method="post" action="action/material">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="deleteMaterialLabel">Alert!</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="delete_id" name="material_id" value="">
          <p>Are you sure you want to delete "<b class="delete_name"></b>" material?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="delete_material" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="restoreMaterial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="restoreMaterialLabel" aria-hidden="true">
    <form class="modal-dialog" method="post" action="action/material">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="restoreMaterialLabel">Alert!</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="restore_id" name="material_id" value="">
          <p>Are you sure you want to restore "<b class="restore_name"></b>" material?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="restore_material" class="btn btn-danger">Restore</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    //delete material Handler
    $(document).ready(function () {
      $('.delete-Material').click(function () {
        var MaterialId = $(this).attr('material_id');
        var MaterialName = $(this).attr('material_name');
        $('#deleteMaterial .delete_name').text(MaterialName);
        $('#deleteMaterial #delete_id').val(MaterialId);
      });
    });
    $(document).ready(function () {
      $('.restore-material').click(function () {
        var MaterialId = $(this).attr('material_id');
        var MaterialName = $(this).attr('material_name');
        $('#restoreMaterial .restore_name').text(MaterialName);
        $('#restoreMaterial #restore_id').val(MaterialId);
      });
    });
  </script>

  <?php

  // including the foogter component
  include_once 'components/footer.php';
  ?>
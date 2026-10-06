<?php
$page = "addMaterial";
// including the header component
include_once 'components/header.php';

$edit_id = '';
$category_id = '';
$material_name = '';
$opening_balance = '';
if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
  // getting materials data for update
  $edit_id = mysqli_real_escape_string($conn, $_GET['edit_id']);
  $material_sql = "SELECT * FROM `materials` WHERE `material_id`= '$edit_id'";
  $material_result = mysqli_query($conn, $material_sql);
  $material = mysqli_fetch_assoc($material_result);
  $category_id = $material['material_category'];
  $material_name = $material['material_name'];
  $opening_balance = $material['opening_balance'];
}

// getting categories for select options
$categories_sql = "SELECT * FROM `categories` WHERE `is_deleted`= 0";
$categories_result = mysqli_query($conn, $categories_sql);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

?>

<div class="container">
  <!-- to display error and alerts -->
  <?= displayAlert() ?>
  <form action="action/material" method="post" class="mt-2">
    <?php
    // checkding for edit values
    if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
      ?>
      <input type="hidden" name="material_id" value="<?= $edit_id ?>">
    <?php } ?>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mb-3">
          <label for="material_category" class="form-label requried">Material Category</label>
          <select class="form-select" name="material_category" id="material_category" required>
            <option value="">Select Material Category</option>
            <?php foreach ($categories as $category) { ?>
              <option value="<?= $category['category_id'] ?>" <?= $category_id == $category["category_id"] ? "selected" : "" ?>><?= $category['category_name'] ?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mb-3">
          <label for="material_name" class="form-label requried">Material Name</label>
          <input type="text" value="<?= $material_name ?>" name="material_name" id="material_name" class="form-control"
            placeholder="Enter Material Name" required>
        </div>
      </div>

      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mb-3">
          <label for="opening_balance" class="form-label requried">Opening Balance</label>
          <input type="number" value="<?= $opening_balance ?>" name="opening_balance" id="opening_balance"
            class="form-control" placeholder="Enter Opening Balance" required>
        </div>
      </div>
      <div class="col-12">
        <?php if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) { ?>
          <button type="submit" name="update_material" class="btn btn-primary">Update Material</button>
        <?php } else { ?>
          <button type="submit" name="add_material" class="btn btn-primary">Add Material</button>
        <?php } ?>
      </div>
    </div>
  </form>
</div>

<script>
  $(document).ready(() => {
    //Opening balance validation for  Max 2 digits after decimal point
    $(document).on("input", "#opening_balance", function () {
      let val = $(this).val();
      if (val === "") return;
      if (isNaN(val)) {
        $(this).val("");
        return;
      }
      if (/\.\d{3,}$/.test(val)) {
        let parts = val.split(".");
        $(this).val(parts[0] + "." + parts[1].slice(0, 2));
      }
    });
  });
</script>
<?php

// including the foogter component
include_once 'components/footer.php';
?>
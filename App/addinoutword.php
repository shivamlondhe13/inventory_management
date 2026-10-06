<?php
$page = "addInwordOntword";
// including the header component
include_once 'components/header.php';

$material_id = '';
$category_id = '';
$quantity = "";
$date = "";
$inword_id = "";

if (isset($_GET["edit"]) && !empty($_GET['edit'])) {

  // getting inword data for update fields
  $inword_id = mysqli_real_escape_string($conn, $_GET["edit"]);
  $inword_sql = "SELECT * FROM `inwords` WHERE `inword_id` = '$inword_id'";
  $inword_result = mysqli_query($conn, $inword_sql);
  $inword = mysqli_fetch_assoc($inword_result);

  // checking if data is available
  if (empty($inword)) {
    setAlert("Inword/Outword data not found!");
    redirect("inwordOntword");
  }

  $material_id = $inword["material_name"];
  $category_id = $inword["category_name"];
  $date = $inword["inword_date"];
  $quantity = $inword["quantity"];


}

//taking material and category data for select options
$material_sql = "SELECT * FROM `materials`  WHERE `is_deleted`= 0";
$categories_sql = "SELECT * FROM `categories` WHERE `is_deleted`= 0";
$material_result = mysqli_query($conn, $material_sql);
$materials = mysqli_fetch_all($material_result, MYSQLI_ASSOC);

$categories_result = mysqli_query($conn, $categories_sql);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

?>

<div class="container my-3">
  <!-- to display error and alerts -->
  <?= displayAlert() ?>
  <form action="action/inwordoutword" method="post" class="mt-2">
    <?php
    // checkding for edit values
    if (isset($_GET["edit"]) && !empty($_GET['edit'])) {
      ?>
      <input type="hidden" name="inword_id" value="<?= $inword_id ?>">
      <?php
    }
    ?>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mb-3">
          <label for="material_category" class="form-label requried">Material Category</label>
          <select class="form-select" name="material_category" id="material_category">
            <option value="">Select Material Category</option>
            <?php
            foreach ($categories as $i => $category) {
              ?>
              <option value="<?= $category["category_id"] ?>" <?= $category_id == $category["category_id"] ? "selected" : "" ?>><?= $category["category_name"] ?></option>
              <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mb-3">
          <label for="material_name" class="form-label requried">Material Name</label>
          <select class="form-select" name="material_name" id="material_name">
            <option value="">Select Material name</option>
            <?php
            foreach ($materials as $i => $material) {
              ?>
              <option class="category_<?= $material["material_category"] ?> category_name"
                value="<?= $material["material_id"] ?>" <?= $material_id == $material["material_id"] ? "selected" : "" ?>>
                <?= $material["material_name"] ?>
              </option>
              <?php
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mb-3">
          <label for="date" class="form-label requried">Date</label>
          <input type="date" value="<?= $date ?>" name="date" id="date" class="form-control" placeholder="Enter date">
        </div>
      </div>

      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="mb-3">
          <label for="quantity" class="form-label requried">Inward-Outward Quantity</label>
          <input type="test" value="<?= $quantity ?>" name="quantity" id="quantity" class="form-control"
            placeholder="Enter Inward-Outward Quantity">
        </div>
      </div>
      <div class="col-12">
        <?php
        if (isset($_GET["edit"])) {
          ?>
          <button type="submit" name="update_inword" class="btn btn-primary">Update Inward-Outward</button>
          <?php

        } else {
          ?>
          <button type="submit" name="add_inword" class="btn btn-primary">Add Inward-Outward</button>
          <?php
        }
        ?>

      </div>
    </div>
  </form>
</div>

<script>
  $(document).ready(() => {
    $(document).on("change", "#material_category", function () {
      $("#material_name .category_name").hide();
      let category = $(this).val();
      $(`#material_name .category_${category}`).show();
    })

    //quantity validation for  Max 2 digits after decimal point
    $(document).on("input", "#quantity", function () {
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
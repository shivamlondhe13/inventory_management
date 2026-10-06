<?php
include '../components/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // check if the form is submitted for adding, updating, or deleting a materials
  if (isset($_POST["add_material"])) {
    $material_name = mysqli_real_escape_string($conn, $_POST['material_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['material_category']);
    $opening_balance = mysqli_real_escape_string($conn, $_POST['opening_balance']);

    //checking for mandetory fields
    if (empty($material_name) || empty($category_id) || empty($opening_balance)) {
      setAlert("Enter required fields", "warning");
      redirect("../addMaterial");
    }

    //chaking floating point value. Max 2 digits after decimal point
    if (!valueCheck($opening_balance)) {
      setAlert("Please enter valid value, max 2 digits after decimal point", "warning");
      redirect("../addMaterial");
    }

    $sql = "INSERT INTO `materials` (`material_name`, `material_category`,`opening_balance`) VALUES ('$material_name', '$category_id', '$opening_balance')";

    try {
      if (mysqli_query($conn, $sql)) {
        setAlert("Material added successfully!", "success");
        redirect("../addMaterial");
      } else {
        setAlert("Material Not Added, please try again!" . mysqli_error($conn), "danger");
        redirect('../addMaterial');
      }
    } catch (\Throwable $th) {
      setAlert("Material not added, error:" . $th->getMessage(), "danger");
      redirect('../addMaterial');
    }

  } else if (isset($_POST["update_material"])) {
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);
    $material_name = mysqli_real_escape_string($conn, $_POST['material_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['material_category']);
    $opening_balance = mysqli_real_escape_string($conn, $_POST['opening_balance']);

    //checking for mandetory fields
    if (empty($material_name) || empty($category_id) || empty($opening_balance)) {
      setAlert("Enter required fields", "warning");
      redirect("../addMaterial");
    }

    //chaking floating point value. Max 2 digits after decimal point
    if (!valueCheck($opening_balance)) {
      setAlert("Please enter valid value, max 2 digits after decimal point", "warning");
      redirect("../addMaterial");
    }

    $sql = "UPDATE `materials` SET `material_name`='$material_name', `material_category`='$category_id',`opening_balance`='$opening_balance' , `updated_at`=now() WHERE `material_id`=$material_id";

    try {
      if (mysqli_query($conn, $sql)) {
        setAlert("Material updated successfully!", "success");
        redirect("../index");
      } else {
        setAlert("Material Not Updated, please try again!" . mysqli_error($conn), "danger");
        redirect('../index');
      }
    } catch (\Throwable $th) {
      setAlert("Material not updated, error:" . $th->getMessage(), "danger");
      redirect('../index');
    }
  } else if (isset($_POST["restore_material"])) {
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);
    $sql = "UPDATE `materials` SET `is_deleted`= '0' , `deleted_at` = NULL WHERE `material_id`='$material_id'";
    try {
      if (mysqli_query($conn, $sql)) {
        setAlert("Material Restored successfully!", "success");
        redirect("../index");
      } else {
        setAlert("Material Not restore, please try again!" . mysqli_error($conn), "danger");
        redirect('../index');
      }
    } catch (\Throwable $th) {
      setAlert("Material not restore, error:" . $th->getMessage(), "danger");
      redirect('../index');
    }
  }

}
?>
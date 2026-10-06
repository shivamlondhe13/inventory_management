<?php
include '../components/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // check if the form is submitted for adding, updating, or deleting a materials
  if (isset($_POST["add_inword"])) {
    $material_name = mysqli_real_escape_string($conn, $_POST['material_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['material_category']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);

    //checking for mandetory fields
    if (empty($material_name) || empty($category_id) || empty($quantity) || empty($date)) {
      setAlert("Enter required fields", "warning");
      redirect("../addMaterial");
    }

    //chaking floating point value. Max 2 digits after decimal point
    if (!valueCheck($quantity)) {
      setAlert("Please enter valid value, max 2 digits after decimal point", "warning");
      redirect("../addinoutword");
    }

    $sql = "INSERT INTO `inwords` (`category_name`, `material_name`, `inword_date`, `quantity`) VALUES ('$category_id', '$material_name','$date' ,'$quantity')";

    try {
      if (mysqli_query($conn, $sql)) {
        setAlert("Inword/Outword added successfully!", "success");
        redirect("../addinoutword");
      } else {
        setAlert("Inword/Outword Not Added, please try again!" . mysqli_error($conn), "danger");
        redirect('../addinoutword');
      }
    } catch (\Throwable $th) {
      setAlert("Inword/Outword not added, error:" . $th->getMessage(), "danger");
      redirect('../addinoutword');
    }

  } else if (isset($_POST["update_inword"])) {
    $inword_id = mysqli_real_escape_string($conn, $_POST['inword_id']);
    $material_name = mysqli_real_escape_string($conn, $_POST['material_name']);
    $category_id = mysqli_real_escape_string($conn, $_POST['material_category']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $date = mysqli_real_escape_string($conn, $_POST["date"]);

    //checking for mandetory fields
    if (empty($material_name) || empty($category_id) || empty($quantity) || empty($date)) {
      setAlert("Enter required fields", "warning");
      redirect("../addMaterial");
    }

    //chaking floating point value. Max 2 digits after decimal point
    if (!valueCheck($quantity)) {
      setAlert("Please enter valid value, max 2 digits after decimal point", "warning");
      redirect("../addinoutword");
    }

    $sql = "UPDATE `inwords` SET `category_name`='$category_id',`material_name`='$material_name',`inword_date`='$date',`quantity`='$quantity' ,`updated_at`= now() WHERE `inword_id`='$inword_id'";

    try {
      if (mysqli_query($conn, $sql)) {
        setAlert("Inword/Outword updated successfully!", "success");
        redirect("../inwordOntword");
      } else {
        setAlert("Inword/Outword Not Updated, please try again!" . mysqli_error($conn), "danger");
        redirect('../inwordOntword');
      }
    } catch (\Throwable $th) {
      setAlert("Inword/Outword not updated, error:" . $th->getMessage(), "danger");
      redirect('../inwordOntword');
    }
  } else if (isset($_POST["delete_inword"])) {
    $inword_id = mysqli_real_escape_string($conn, $_POST['inword_id']);
    $sql = "UPDATE `inwords` SET `is_deleted`= '1' , `deleted_at` = now() WHERE `inword_id`='$inword_id'";
    try {
      if (mysqli_query($conn, $sql)) {
        setAlert("Inword/Outword data deleted successfully!", "success");
        redirect("../inwordOntword");
      } else {
        setAlert("Inword/Outword data Not Deleted, please try again!" . mysqli_error($conn), "danger");
        redirect('../inwordOntword');
      }
    } catch (\Throwable $th) {
      setAlert("Inword/Outword data not deleted, error:" . $th->getMessage(), "danger");
      redirect('../inwordOntword');
    }
  } else if (isset($_POST["Restore_inword"])) {
    $inword_id = mysqli_real_escape_string($conn, $_POST['inword_id']);
    $sql = "UPDATE `inwords` SET `is_deleted`= '0' , `deleted_at` = NULL WHERE `inword_id`='$inword_id'";
    try {
      if (mysqli_query($conn, $sql)) {
        setAlert("Inword/Outword data Restored successfully!", "success");
        redirect("../inwordOntword");
      } else {
        setAlert("Inword/Outword data Not Restore, please try again!" . mysqli_error($conn), "danger");
        redirect('../inwordOntword');
      }
    } catch (\Throwable $th) {
      setAlert("Inword/Outword data not Restore, error:" . $th->getMessage(), "danger");
      redirect('../inwordOntword');
    }
  }
}
?>
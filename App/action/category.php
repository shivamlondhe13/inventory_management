<?php
include '../components/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if the form is submitted for adding, updating, or deleting a category
  if (isset($_POST['add_category'])) {
    // Get the category name and description from the form
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    // checking category is empty or not
    if (empty($category_name)) {
      // Set a success message and redirect to the category page
      setAlert("Enter category name!", "success");
      redirect("../category");
    }

    // Insert the new category into the database
    $sql = "INSERT INTO `categories` (`category_name`) VALUES ('$category_name')";

    try {
      if (mysqli_query($conn, $sql)) {
        // Set a success message and redirect to the category page
        setAlert("Category added successfully!", "success");
        redirect("../category");
      } else {
        // Set an error message and redirect to the category page
        setAlert("Category Not Added, please try again!" . mysqli_error($conn), "danger");
        redirect('../category');
      }
    } catch (\Throwable $th) {
      // Set an error message and redirect to the category page
      setAlert("Category not added, error:" . $th->getMessage(), "danger");
      redirect('../category');
    }
    // Execute the query and check for success

  } else if (isset($_POST['update_category'])) {
    // Get the category ID and new name from the form
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    // checking category is empty or not
    if (empty($category_name)) {
      // Set a success message and redirect to the category page
      setAlert("Enter category name!", "success");
      redirect("../category");
    }
    // Update the category in the database
    $sql = "UPDATE `categories` SET `category_name`='$category_name', `updated_at` = now() WHERE `category_id`=$category_id";
    try {
      if (mysqli_query($conn, $sql)) {
        // Set a success message and redirect to the category page
        setAlert("Category updated successfully!", "success");
        redirect('../category');
      } else {
        // Set an error message and redirect to the category page
        setAlert("Category Not Updated, please try again!", "danger");
        redirect('../category');
      }
    } catch (\Throwable $th) {
      // Set an error message and redirect to the category page
      setAlert("Category not updated, error:" . $th->getMessage(), "danger");
      redirect('../category');
    }

  } else if (isset($_POST['delete_category'])) {
    // Get the category ID from the form
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    // Mark the category as deleted in the database
    $sql = "UPDATE `categories` SET `is_deleted`= '1' , `deleted_at` = now() WHERE `category_id`='$category_id'";
    try {
      if (mysqli_query($conn, $sql)) {
        // Set a success message and redirect to the category page
        setAlert("Category deleted successfully!", "success");
        redirect('../category');
      } else {
        // Set an error message and redirect to the category page
        setAlert("Category Not Deleted, please try again!", "danger");
        redirect('../category');
      }
    } catch (\Throwable $th) {
      // Set an error message and redirect to the category page
      setAlert("Category not deleted, error:" . $th->getMessage(), "danger");
      redirect('../category');
    }


  } else if (isset($_POST['restore_category'])) {
    // Get the category ID from the form
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    // Mark the category as deleted in the database
    $sql = "UPDATE `categories` SET `is_deleted`= '0' , `deleted_at` = NULL WHERE `category_id`='$category_id'";
    try {
      if (mysqli_query($conn, $sql)) {
        // Set a success message and redirect to the category page
        setAlert("Category Restored successfully!", "success");
        redirect('../category');
      } else {
        // Set an error message and redirect to the category page
        setAlert("Category Not Restore, please try again!", "danger");
        redirect('../category');
      }
    } catch (\Throwable $th) {
      // Set an error message and redirect to the category page
      setAlert("Category not Restore, error:" . $th->getMessage(), "danger");
      redirect('../category');
    }


  }

}
?>
<?php
$page = "category";
// including the header component
include_once 'components/header.php';

if (isset($_GET["trash"])) {
  $trash = 1;
} else {
  $trash = 0;
}
// Fetch categories from the database
$search = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $categories_sql = "SELECT * FROM `categories` WHERE `is_deleted`=$trash AND `category_name` LIKE '%$search%'";
} else {
  $categories_sql = "SELECT * FROM `categories` WHERE `is_deleted`= $trash";
}
$categories_result = mysqli_query($conn, $categories_sql);
$categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);

?>

<div class="container mt-3">
  <h4>Add Category</h4>
  <div class="mt-3">
    <?php displayAlert(); ?>
    <form action="action/category" method="post">
      <label for="category_name" class="requried">Category Name</label>
      <div class="input-group">
        <input type="text" name="category_name" id="category_name" class="form-control"
          placeholder="Enter Category Name" required>
        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
      </div>
    </form>
  </div>
</div>

<div class="container mt-3">
  <h4>Categories</h4>
  <div class="my-1">
    <?php
    if (isset($_GET["trash"])) {
      ?>
      <a class="btn btn-light" href="category">Exit Trash</a>
      <?php
    } else {
      ?>
      <a class="btn btn-secondary" href="category?trash">View Trash</a>
      <?php
    }
    ?>

  </div>
  <form class="mb-2" method="get">
    <div class="input-group">
      <input class="form-control" value="<?= $search ?>" type="text" name="search" id="search"
        placeholder="Search Category">
      <button type="submit" class="btn btn-primary">Serach</button>
      <?php if ($search != "") { ?>
        <a href="category" class="btn btn-info">View All</a>
      <?php } ?>
    </div>
  </form>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Sr.No.</th>
        <th>Category</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $srno = 1;
      foreach ($categories as $i => $category) {
        ?>
        <tr>
          <td><?= $srno++ ?></td>
          <td><?= $category["category_name"] ?></td>
          <?php
          if (!isset($_GET["trash"])) {
            ?>
            <td>
              <button category_id="<?= $category["category_id"] ?>" category_name="<?= $category["category_name"] ?>"
                class="btn btn-primary edit-category" data-bs-toggle="modal" data-bs-target="#editCategory">Edit</button>
              <button category_id="<?= $category["category_id"] ?>" category_name="<?= $category["category_name"] ?>"
                class="btn btn-danger delete-category" data-bs-toggle="modal"
                data-bs-target="#deleteCategory">Delete</button>
            </td>
            <?php
          } else {
            ?>
            <td>
              <button category_id="<?= $category["category_id"] ?>" category_name="<?= $category["category_name"] ?>"
                class="btn btn-danger restore-category" data-bs-toggle="modal"
                data-bs-target="#restoreCategory">restore</button>
            </td>
            <?php
          }
          ?>

        </tr>
        <?php
      }
      ?>

    </tbody>
  </table>
</div>

<!-- Modal -->
<div class="modal fade" id="editCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="editCategoryLabel" aria-hidden="true">
  <form class="modal-dialog" method="post" action="action/category">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editCategoryLabel">Edit Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="update_id" name="category_id" value="">
        <div class="mb-3">
          <label for="category_name" class="form-label">Category Name</label>
          <input type="text" class="form-control" id="category_name" name="category_name" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="update_category" class="btn btn-success">Update</button>
      </div>
    </div>
  </form>
</div>
<!-- Modal -->
<div class="modal fade" id="deleteCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="deleteCategoryLabel" aria-hidden="true">
  <form class="modal-dialog" method="post" action="action/category">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deleteCategoryLabel">Alert!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="delete_id" name="category_id" value="">
        <p>Are you sure you want to delete "<b class="delete_name"></b>" category?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="delete_category" class="btn btn-danger">Delete</button>
      </div>
    </div>
  </form>
</div>

<!-- Modal -->
<div class="modal fade" id="restoreCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="restoreCategoryLabel" aria-hidden="true">
  <form class="modal-dialog" method="post" action="action/category">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="restoreCategoryLabel">Alert!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="restore_id" name="category_id" value="">
        <p>Are you sure you want to restore "<b class="restore_name"></b>" category?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="restore_category" class="btn btn-danger">Restore</button>
      </div>
    </div>
  </form>
</div>

<script>
  // edit category Handler
  $(document).ready(function () {
    $('.edit-category').click(function () {
      var categoryId = $(this).attr('category_id');
      var categoryName = $(this).attr('category_name');
      $('#editCategory #update_id').val(categoryId);
      $('#editCategory input[name="category_name"]').val(categoryName);
    });
  });

  //delete category Handler
  $(document).ready(function () {
    $('.delete-category').click(function () {
      var categoryId = $(this).attr('category_id');
      var categoryName = $(this).attr('category_name');
      $('#deleteCategory .delete_name').text(categoryName);
      $('#deleteCategory #delete_id').val(categoryId);
    });
  });
  //restore category Handler
  $(document).ready(function () {
    $('.restore-category').click(function () {
      var categoryId = $(this).attr('category_id');
      var categoryName = $(this).attr('category_name');
      $('#restoreCategory .restore_name').text(categoryName);
      $('#restoreCategory #restore_id').val(categoryId);
    });
  });
</script>

<?php

// including the foogter component
include_once 'components/footer.php';
?>
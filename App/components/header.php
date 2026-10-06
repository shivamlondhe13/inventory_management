<?php
include_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Management</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/jquery.min.js"></script>
</head>

<body>

  <header>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a href="index" class="nav-link <?= $page == "" ? "active" : "" ?>"> Material </a></li>
            <li class="nav-item"><a href="addMaterial" class="nav-link <?= $page == "addMaterial" ? "active" : "" ?>">
                Add
                Material </a></li>
            <li class="nav-item"><a href="category" class="nav-link <?= $page == "category" ? "active" : "" ?>">
                Category
              </a></li>
            <li class="nav-item"><a href="inwordOntword"
                class="nav-link <?= $page == "inwordOntword" ? "active" : "" ?>">
                Inword/Outword
              </a></li>
            <li class="nav-item"><a href="addinoutword"
                class="nav-link <?= $page == "addInwordOntword" ? "active" : "" ?>">
                Add Inword/Outword
              </a></li>
          </ul>
        </div>
      </div>

    </nav>
  </header>
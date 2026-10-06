<?php
session_start();
// Start the session to manage alert messages

// Database configuration
$db = "inventory_management";
$user = "root";
$password = "";
$host = "localhost";

// Create a connection to the database
try {
  $conn = mysqli_connect($host, $user, $password, $db);
} catch (\Throwable $th) {
  // Handle connection error
  die("Connection failed: " . $th->getMessage());
}
// Function to redirect to a different page
function redirect($to = "")
{
  if ($to == "") {
    $request_uri = $_SERVER['HTTP_REFERER'];
  } else {
    $request_uri = $to;
  }
  header("Location:$request_uri");
  exit;
}
// Function to set an alert message
function setAlert($message, $type = 'success')
{
  $_SESSION['alert_message'] = $message;
  $_SESSION['alert_type'] = $type;
}

//  Function to display an alert message
function displayAlert()
{
  if (isset($_SESSION['alert_message'])) {
    $message = $_SESSION['alert_message'];
    $type = $_SESSION['alert_type'];

    ?>
    <div class="my-2 alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
      <?= $message ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php

    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_type']);
  }
}

function valueCheck($num)
{
  //chaking floating point value. Max 2 digits after decimal point
  return (bool) preg_match('/^-?\d+(\.\d{1,2})?$/', (string) $num);
}
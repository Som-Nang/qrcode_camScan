<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<?php

// Array with names
$a[] = "Anna";
$a[] = "Brittany";
$a[] = "Cinderella";
$a[] = "Diana";
$a[] = "Eva";
$a[] = "Fiona";
$a[] = "Gunda";
$a[] = "Hege";
$a[] = "Inga";
$a[] = "Johanna";
$a[] = "Kitty";
$a[] = "Linda";
$a[] = "Nina";
$a[] = "Ophelia";
$a[] = "Petunia";
$a[] = "Amanda";
$a[] = "Raquel";
$a[] = "Cindy";
$a[] = "Doris";
$a[] = "Eve";
$a[] = "Evita";
$a[] = "Sunniva";
$a[] = "Tove";
$a[] = "Unni";
$a[] = "Violet";
$a[] = "Liza";
$a[] = "Elizabeth";
$a[] = "Ellen";
$a[] = "Wenche";
$a[] = "Vicky";

try {
  // Get the "q" parameter from the URL
  $q = $_REQUEST["q"];
  $pattern = '/\battID=(\d+)&date=([^&]+)&subjectName=([^&]+)/';

  // Match the pattern against the entire string

  $roles = explode(',', $q);
  // Database connection parameters
  $servername = "127.0.0.1";
  $username = "root";
  $password = "root";
  $dbname = "ocasdb";

  // Create a PDO connection
  $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Check if the employee is already registered
  $checkQuery = "SELECT COUNT(*) as count FROM tbl_attendant_detail WHERE attendant_id = ?";
  $checkStmt = $con->prepare($checkQuery);
  $checkStmt->execute([$roles[0]]);
  $rowcount = $checkStmt->fetchColumn();

  if ($rowcount == 0) {
    // Use a prepared statement for INSERT
    $insertQuery = "INSERT INTO tbl_attendant_detail (attendant_id, user_id, status) VALUES (?, ?, ?)";
    $insertStmt = $con->prepare($insertQuery);

    // Assign values to individual variables
    $role1 = $roles[0];
    $role2 = 15;
    $role3 = "permissionNo";

    // Bind parameters for the INSERT statement
    $insertStmt->bindParam(1, $role1);
    $insertStmt->bindParam(2, $role2);
    $insertStmt->bindParam(3, $role3);

    // Execute the INSERT statement
    $insertStmt->execute();

    // Check if the INSERT was successful
    if ($insertStmt->rowCount() > 0) {
      echo '<div class="alert alert-success"><strong>Success!</strong> Employee successfully registered</div>';
      echo date('l jS \of F Y h:i:s A');
    } else {
      echo '<div class="alert alert-danger"><strong>Error!</strong> Failed to register employee</div>';
    }
  } else {
    // Employee is already registered
    echo '<div class="alert alert-success"><strong>Success!</strong> Employee is already registered</div>';
    echo date('l jS \of F Y h:i:s A');
  }
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}
?>
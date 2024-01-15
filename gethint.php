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
  $currentDate = gmdate('m/d/Y-h:00:00', time() + 7 * 3600);
  list($attendantID, $date, $subjectName) = explode(",", $q);
  $uid = 78;

  $servername = "127.0.0.1";
  $username = "root";
  $password = "root";
  $dbname = "ocasdb";

  // Create a PDO connection
  $con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "SELECT tbl_attendant.ID AS attendantID, tbl_classstudent.userID AS class_user, 
  tbl_attendant_detail.user_id AS att_checked_user,tbl_attendant_detail.attendant_id
          FROM
          tbl_attendant
          JOIN tblclass ON tblclass.ID = tbl_attendant.class_id
          LEFT JOIN tbl_classstudent ON tblclass.ID = tbl_classstudent.classID
          LEFT JOIN tbl_attendant_detail ON tbl_attendant.ID = tbl_attendant_detail.attendant_id
          WHERE tbl_attendant.ID = :attendantID AND tbl_attendant.attendantDate = :date AND tbl_classstudent.userID =:uid";
  $query = $con->prepare($sql);

  // Corrected parameter name in bindParam
  $query->bindParam(':attendantID', $attendantID, PDO::PARAM_STR);
  $query->bindParam(':date', $date, PDO::PARAM_STR);
  $query->bindParam(':uid', $uid, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);


  if (count($results) > 0) {
    foreach ($results as $row) {
      if ($currentDate == $date && $attendantID == $row->attendantID && $uid == $row->class_user) {
        // Check if the employee is already registered
        $checkQuery = "SELECT COUNT(*) as count FROM tbl_attendant_detail WHERE attendant_id = ? AND user_id = ?";
        $checkStmt = $con->prepare($checkQuery);

        // Corrected the way parameters are passed to execute
        $checkStmt->execute([$attendantID, $uid]);
        $rowcount = $checkStmt->fetchColumn();

        if ($rowcount == 0) {
          // Use a prepared statement for INSERT
          $insertQuery = "INSERT INTO tbl_attendant_detail (attendant_id, user_id, status) VALUES (?, ?, ?)";
          $insertStmt = $con->prepare($insertQuery);

          // Assign values to individual variables
          $role1 = $attendantID;
          $role2 = 78;
          $role3 = "permission";

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
          echo '<div class="alert alert-danger"><strong>Success!</strong> Employee is already registered</div>';
          echo date('l jS \of F Y h:i:s A');
        }
      } else {
        echo "<p>You not allow to check in $row->class_user</p>";
      }
    }
  } else {
    echo "No results found";
  }
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

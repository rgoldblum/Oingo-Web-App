<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

$userlat;
$userlng;
$username;

// Prepare a select statement
$sql = "SELECT * FROM users WHERE uid = ? ";

if($stmt = $conn->prepare($sql)){

  // Bind variables to the prepared statement as parameters
  $stmt->bind_param("i", $param_uid);

  // Set parameters
  $param_uid = $uid;

  // Attempt to execute the prepared statement
  if($stmt->execute()){
    //store results
    $result = $stmt->get_result();

    //iterate through rows
    while ($row = $result->fetch_assoc()) {
      //store row in notes array
      $userlat = $row['latitude'];
      $userlng = $row['longitude'];
      $username = $row['username'];
    }

  } else{
      echo "Oops! Something went wrong. Please try again later.";
  }
}

?>

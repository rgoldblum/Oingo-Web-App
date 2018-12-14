<?php
//check if user is logged in
require_once "check_login.php";


// Include config file
require_once "config.php";

//array to store notes
//$notes = array();

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
      // echo '<p>Row:'.$row.'</p>';
      //store row in notes array
      $userlat = $row['latitude'];
      $userlng = $row['longitude'];
      $username = $row['username'];
    }
    // echo $userlat;
    // echo $userlng;

    // $notes = json_encode($notes);
    //
    // echo $notes;

  } else{
      echo "Oops! Something went wrong. Please try again later.";
  }
}




?>

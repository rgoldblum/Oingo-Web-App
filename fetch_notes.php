<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

//array to store notes
$notes = array();

//get viewable notes from database
// Prepare a select statement
$sql = "SELECT * FROM note ";

if($stmt = $conn->prepare($sql)){

  // Attempt to execute the prepared statement
  if($stmt->execute()){
    //store results
    $result = $stmt->get_result();

    //iterate through rows
    while ($row = $result->fetch_assoc()) {
      // echo '<p>Row:'.$row.'</p>';
      //store row in notes array
      $notes[] = $row;
    }

    // $notes = json_encode($notes);
    //
    // echo $notes;

  } else{
      echo "Oops! Something went wrong. Please try again later.";
  }
}

?>

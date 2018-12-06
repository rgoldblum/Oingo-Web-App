<?php
// Include config file
require_once "config.php";

//array to store notes
$notes = array();

//get viewable notes from database
// Prepare a select statement
$sql = "SELECT * FROM notes ";

if($stmt = $conn->prepare($sql)){

    // Attempt to execute the prepared statement
    if($stmt->execute()){
      //store results
      $result = $stmt->get_result();

      //iterate through rows
      while ($row = $result->fetch_assoc()) {
        //store row in notes array
        $notes[] = $row;
      }

      $notes = json_encode($notes);

      echo 'var notes = '.$notes.';';

      // End XML file
      // echo '</markers>';

    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}

?>

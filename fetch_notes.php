<?php
//check if user is logged in
require_once "check_login.php";

// Include config file
require_once "config.php";

//array to store notes
$notes = array();

//get viewable notes from database
// Prepare a select statement
$sql = "SELECT DISTINCT note.nid, note.ntext
FROM users, note NATURAL JOIN schedules
WHERE
(
  users.uid = ?
  AND (getDistance(users.latitude, users.longitude, note.latitude, note.longitude) <= note.radius)
  AND (withinSchedule('Sun', '2018-11-10', '15:00:00', schedules.activeDays, schedules.startDate, schedules.endDate, schedules.startTime, schedules.endTime) = 'true')
  AND ((note.notePrivacy = 'self' AND users.uid = note.uid) OR
  (note.notePrivacy = 'friends' AND EXISTS (SELECT * FROM Friendship WHERE users.uid = friendship.uid AND note.uid = friendship.friends_uid)) OR (note.notePrivacy = 'public'))
  AND note.nid NOT IN

  (
    SELECT DISTINCT note.nid
    FROM users NATURAL JOIN state, filters NATURAL JOIN schedules, note NATURAL JOIN tag_in_note NATURAL JOIN tag
    WHERE
    (
      users.uid = ?
      AND ((state.isActive = 'true' AND filters.sid = state.sid) OR (users.uid = filters.uid AND filters.sid IS NULL))
      AND ((filters.radius IS NOT NULL AND (getDistance(users.latitude, users.longitude, filters.latitude, filters.longitude) >= filters.radius))
      OR (filters.sched_id IS NOT NULL AND (withinSchedule('Sun', '2018-11-10', '15:00:00', schedules.activeDays, schedules.startDate, schedules.endDate, schedules.startTime, schedules.endTime) = 'false'))
      OR (tag.tid != filters.tid AND filters.tid IS NOT NULL)
      OR (filters.filter_privacy IS NOT NULL AND (note.notePrivacy NOT LIKE (filters.filter_privacy))))
    )
  )
)";

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

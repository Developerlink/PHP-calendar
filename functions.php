<?php


function confirmQuery($result){
  global $no_fr_conn;
  if(!$result){
    die("Query failed. " . mysqli_error($conn)); // prøv $conn->connect_error
  } else {
    return true;
  }
}

function getDateData(){
    // read from stored value which month is being viewed and update month that is shown
    $GLOBALS['current_displayed_year'] = date('Y', $_SESSION['time_currently_displayed']);
    $GLOBALS['current_displayed_month'] = date('F', $_SESSION['time_currently_displayed']);
    $GLOBALS['current_displayed_week'] = date('W', $_SESSION['time_currently_displayed']);
    $GLOBALS['current_displayed_day'] = date('l', $_SESSION['time_currently_displayed']);
    $GLOBALS['current_displayed_day_int'] = date('d', $_SESSION['time_currently_displayed']);
    $GLOBALS['ym'] = date('Y-m', $_SESSION['time_currently_displayed']);
    $GLOBALS['ymd'] = date('Y-m-d', $_SESSION['time_currently_displayed']);
}





 ?>

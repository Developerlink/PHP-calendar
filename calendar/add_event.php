<?php
if(isset($_SESSION['time_currently_displayed'])) {
  $event_date_loaded = date('Y-m-d', $_SESSION['time_currently_displayed']);
}

if(isset($_POST['add_data'])) {
  $event_date = $_POST['date'];
  if($event_date == ''){
    $event_date = $event_date_loaded;
  }
  $time_start = $_POST['time_start'];
  $title = mysqli_real_escape_string($no_fr_conn, $_POST['title']);
  $content = mysqli_real_escape_string($no_fr_conn, $_POST['content']);

  $sql = "INSERT INTO calendar(event_date, time_start, title, content) ";
  $sql .= "VALUES ('{$event_date}', '{$time_start}', '{$title}', '{$content}') ";

  $add_event_q = $no_fr_conn->query($sql);

  if(confirmQuery($add_event_q)){
    // get last inserted id to navigate
    $event_id = mysqli_insert_id($no_fr_conn);

    $announce = "Event titled <a class='blue' href='?source=edit&event_id={$event_id}'>'{$title}'</a> has successfully been created on <a class='blue' href='?source=day&view=that_day&day={$event_date}'>'{$event_date}'</a>";
  }
} else if(isset($_POST['add_dummy'])) {
  $random_hour = mt_rand(6,19);
  $event_date = $event_date_loaded;
  $time_start = $random_hour . ":30:00";
  $title = "Dummy appointment";
  $content = "This was made so that I could test the delete function";

  $sql = "INSERT INTO calendar(event_date, time_start, title, content) ";
  $sql .= "VALUES ('{$event_date}', '{$time_start}', '{$title}', '{$content}') ";

  $add_event_q = $no_fr_conn->query($sql);

  if(confirmQuery($add_event_q)){
    // get last inserted id to navigate
    $event_id = mysqli_insert_id($no_fr_conn);

    $announce = "Event titled <a class='blue' href='?source=edit&event_id={$event_id}'>'{$title}'</a> has successfully been created on <a class='blue' href='?source=day&view=that_day&day={$event_date}'>'{$event_date}'</a>";
  }
  $random_hour = mt_rand(6,19);
  $event_date = $event_date_loaded;
  $time_start = $random_hour . ":00:00";
  $title = "Dummy go to the docctor";
  $content = "Test for nasal problems";

  $sql = "INSERT INTO calendar(event_date, time_start, title, content) ";
  $sql .= "VALUES ('{$event_date}', '{$time_start}', '{$title}', '{$content}') ";

  $add_event_q = $no_fr_conn->query($sql);

  if(confirmQuery($add_event_q)){
    // get last inserted id to navigate
    $event_id = mysqli_insert_id($no_fr_conn);

    $announce .= "<br> Event titled <a class='blue' href='?source=edit&event_id={$event_id}'>'{$title}'</a> has successfully been created on <a class='blue' href='?source=day&view=that_day&day={$event_date}'>'{$event_date}'</a>";
  }
  $random_hour = mt_rand(6,19);
  $event_date = $event_date_loaded;
  $time_start = $random_hour . ":30:00";
  $title = "Dummy run 5 km";
  $content = "Put on 2,5 kg ankle weights and run it in max 20 min.";

  $sql = "INSERT INTO calendar(event_date, time_start, title, content) ";
  $sql .= "VALUES ('{$event_date}', '{$time_start}', '{$title}', '{$content}') ";

  $add_event_q = $no_fr_conn->query($sql);

  if(confirmQuery($add_event_q)){
    // get last inserted id to navigate
    $event_id = mysqli_insert_id($no_fr_conn);

    $announce .= "<br> Event titled <a class='blue' href='?source=edit&event_id={$event_id}'>'{$title}'</a> has successfully been created on <a class='blue' href='?source=day&view=that_day&day={$event_date}'>'{$event_date}'</a>";
  }

} else {
  $announce = '';
}




 ?>


<div class="col-md-10 body-column-center">

<h1>Add an event</h1>
<label class="announce" for=""><?php echo $announce; ?></label>

<hr>
<form class="" action="" method="post">
  <div class="form">


    <div class="form-group">
      <label class="">Date (Currently set to: <?php echo $event_date_loaded; ?>)</label>
      <br>
      <input type="date" class="" name="date" value="">
    </div>

    <div class="form-group">
      <label for="">Time</label>
      <br>
      <input type="time" class="" name="time_start" min="00:00" max="23:59" value="">
    </div>

    <div class="form-group">
      <label for="">Title</label>
      <input type="text" class="form-control" name="title" value="">
    </div>

    <div class="form-group">
      <label for="">Content</label>
      <textarea type="text" class="form-control" name="content" id="" cols="30" rows="7"></textarea>
    </div>

    <input type="hidden" name="confirmation" value="set">

    <div class="form-group">
      <input type="submit" class="btn btn-primary" name="add_data" value="Add">
    </div>
    <div class="form-group">
      <input type="submit" class="btn btn-success" name="add_dummy" value="Add dummy date and time">
    </div>

  </div>
</form>

</div>

<?php

if(isset($_GET['event_id'])){

  $event_id = $_GET['event_id'];

  $sql = "SELECT * FROM calendar WHERE event_id = {$event_id} ";
  $load_event_q = $no_fr_conn->query($sql);

  if(confirmQuery($load_event_q)){

    while($row = $load_event_q->fetch_assoc()){
      $event_date_loaded = $row['event_date'];
      $time_start_loaded = $row['time_start'];
      $title_loaded = $row['title'];
      $content_loaded = $row['content'];
    }

    if(isset($_GET['changes'])){
      $announce = "Event titled '{$title_loaded}' has successfully been updated on <a class='blue' href='?source=day&view=that_day&day={$event_date_loaded}'>'{$event_date_loaded}'</a>";
    } else {
      $announce = '';
    }
  }
}


if(isset($_POST['edit_data'])) {
  $event_date = $_POST['date'];
  if($event_date == ''){
    $event_date = $event_date_loaded;
  }
  $time_start = $_POST['time_start'];
  if($time_start == ''){
    $time_start = $time_start_loaded;
  }
  $title = mysqli_real_escape_string($no_fr_conn, $_POST['title']);
  $content = mysqli_real_escape_string($no_fr_conn, $_POST['content']);

  $sql = "UPDATE calendar SET ";
  $sql .= "event_date = '{$event_date}', ";
  $sql .= "time_start = '{$time_start}', ";
  $sql .= "title = '{$title}', ";
  $sql .= "content = '{$content}' ";
  //no komma before WHERE and at the end!
  $sql .= "WHERE event_id = {$event_id} ";
  $edit_event_q = $no_fr_conn->query($sql);

  if(confirmQuery($no_fr_conn)){
    header("Location: no_framework_calendar.php?source=edit&event_id={$event_id}&changes=true");
  }
}

if(isset($_POST['delete'])){

  $sql = "DELETE FROM calendar WHERE event_id = $event_id ";
  $delete_event_q = $no_fr_conn->query($sql);

  if(confirmQuery($no_fr_conn)){
    header("Location: no_framework_calendar.php?source=day&view=that_day&day={$event_date_loaded}");
  }

}


 ?>


<div class="col-md-10 body-column-center">

<h1>Edit an event</h1>
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
      <label for="">Time (Currently set to: <?php echo $time_start_loaded; ?>)</label>
      <br>
      <input type="time" class="" name="time_start" min="00:00" max="23:59" value="">
    </div>

    <div class="form-group">
      <label for="">Title</label>
      <input type="text" class="form-control" name="title" value="<?php echo $title_loaded; ?>" required>
    </div>

    <div class="form-group">
      <label for="">Content</label>
      <textarea type="text" class="form-control" name="content" id="" cols="30" rows="7"><?php echo $content_loaded; ?></textarea>
    </div>

    <input type="hidden" name="confirmation" value="set">

    <div class="form-group">
      <input type="submit" class="btn btn-primary" name="edit_data" value="Edit">
    </div>
    <div class="form-group">
      <input type="submit" class="btn btn-danger" name="delete" value="Delete" onClick="javascript: return confirm('Are you sure you want to delete?'); ">
    </div>

  </div>
</form>

</div>

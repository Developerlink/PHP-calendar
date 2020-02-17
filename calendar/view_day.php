<?php
  // setting timezone
  date_default_timezone_set('Europe/Copenhagen');

  // if coming from a link and in a new window nothing has been set set default date
  if (!isset($_SESSION['time_currently_displayed'])){
    // find curent month and store it as the month that is currently being viewed
    $_SESSION['time_currently_displayed'] = time();
    // read from stored value which month is being viewed and update month that is shown
    getDateData();
  }

  // Get and display prev, next and current month when link is clicked or just current month on first load of page
  if (isset($_GET['view'])){
    $view = $_GET['view'];

    switch ($view) {
      case 'previous_day':
        // go back 1 month and store it as the month that is currently being viewed
        $_SESSION['time_currently_displayed'] = date(strtotime("-1 day", $_SESSION['time_currently_displayed']));
        // read from stored value which month is being viewed and update month that is shown
        getDateData();
        break;
      case 'next_day':
        $_SESSION['time_currently_displayed'] = date(strtotime("+1 day", $_SESSION['time_currently_displayed']));
        // read from stored value which month is being viewed and update month that is shown
        getDateData();
        break;
        case 'that_day':
          $year_month_day = $_GET['day'];
          $pieces = explode("-", $year_month_day);
          $year = $pieces[0];
          $month = $pieces[1];
          $day = $pieces[2];
          $_SESSION['time_currently_displayed'] = mktime(12, 0, 0, $month, $day, $year);
          // read from stored value which month is being viewed and update month that is shown
          getDateData();
          break;
      default:
        // find curent month and store it as the month that is currently being viewed
        $_SESSION['time_currently_displayed'] = time();
        // read from stored value which month is being viewed and update month that is shown
        getDateData();
        break;
    }
  } else { // if no buttons have been pushed and view is undefined or any other scenario
      // find curent month and store it as the month that is currently being viewed
      $_SESSION['time_currently_displayed'] = time();
      // read from stored value which month is being viewed and update month that is shown
      getDateData();
  }



   ?>
<div class="col-md-10 body-column-center">
  <a class="btn btn-dark" href="?source=day&view=current_day">Back to current day</a>
  <hr class="gray">
  <h3><a href="?source=week&view=that_week&week=<?php echo $ymd; ?>">Week <?php echo $current_displayed_week . " "; ?></a>
    <a href="?source=month&view=that_month&month=<?php echo $ym; ?>"><?php echo $current_displayed_month . " "; ?></a>
    <a href="?source=year&view=that_year&year=<?php echo $current_displayed_year; ?>"><?php echo $current_displayed_year; ?></a>
  </h3>
  <!-- month display -->
  <div class="row row-headline">
    <h3 class="left headline"><a class="blue" href="?source=day&view=previous_day">&lt  </a></h3>
    <div class="middle">
      <h3 class="middle headline"><?php echo $current_displayed_day_int; ?></h3>
    </div>
    <h3 class="right headline"><a class="blue" href="?source=day&view=next_day">  &gt</a></h3>
  </div>
  <table class="table table-bordered table-day">
    <thead head-month>
      <!-- <th class="purple time-day">Time</th> -->
      <?php
        // if the day being viewed is today then color the table head cell cyan
        if(date('Y-m-d', $_SESSION['time_currently_displayed']) == date('Y-m-d', time())){

          echo "<th class='today'>{$current_displayed_day}</th>";

        } else if ($current_displayed_day == 'Saturday') {
          echo "<th class='blue'>{$current_displayed_day}</th>";
        } else if ($current_displayed_day == 'Sunday') {
          echo "<th class='red'>{$current_displayed_day}</th>";
        } else {
          echo "<th class=''>{$current_displayed_day}</th>";
        }

        ?>
    </thead>
    <tbody>
      <?php
        // loading events from database

        $sql = "SELECT * FROM calendar WHERE event_date = '{$ymd}' ";
        $sql .= "ORDER BY time_start ASC ";
        $view_events_q = $no_fr_conn->query($sql);

        if(confirmQuery($view_events_q)){

          // check if there are no events
          if($view_events_q->num_rows == 0){
            echo "<tr>";
            echo "<td class='td-content'>There are no events for this day</td>";
            echo "</tr>";
          } else {

            while($row = $view_events_q->fetch_assoc()){
              $event_id = $row['event_id'];
              $time_start = $row['time_start'];
              $title = $row['title'];
              $content = $row['content'];

              ?>
      <tr>
        <td class="td-content left day-table">
          <table class="table day-table">
            <td class="no-border title delete"><?php echo $time_start; ?></td>
            <td class="no-border"><a href="?source=edit&event_id=<?php echo $event_id; ?>"><b><?php echo $title; ?></b><?php echo "<br>" . $content; ?></a></td>
            <td class="no-border delete test-right">
              <form class="" action="" method="post">
                <input type="hidden" class="" name="event_id" value="<?php echo $event_id; ?>">
                <input type="submit" class="btn btn-danger" name="delete" value="Delete" onClick="javascript: return confirm('Are you sure you want to delete?'); ">
              </form>
            </td>
          </table>
        </td>
      </tr>
      <?php }
        //$row =
        }

        }

        ?>
      <?php
        if(isset($_POST['delete'])){

          $event_id = $_POST['event_id'];

          $sql = "DELETE FROM calendar WHERE event_id = $event_id ";
          $delete_event_q = $no_fr_conn->query($sql);

          if(confirmQuery($no_fr_conn)){
            header("Location: no_framework_calendar.php?source=day&view=that_day&day={$ymd}");
          }

        }


         ?>
      <?php
        // // the day that is currently being viewed
        // $year = date('d', $_SESSION['month_currently_displayed']);
        // // the month that is currently being viewed
        // $month = date('m', $_SESSION['month_currently_displayed']);
        // // the year that is currently being viewed
        // $year = date('Y', $_SESSION['month_currently_displayed']);
        // // the total number of days in the currently viewed month
        // $day_count = date('t', $_SESSION['month_currently_displayed']);
        // // storing standard time for labeling hours and on the day table
        // $time_label_time = mktime(05, 00, 0, $month, 01, $year);
        // // formatting standard time to a human readable string
        // $time_label_text = date("H:i", $time_label_time);


        // // making the rows and cells in table every 30 minutes
        // for ($i=1; $i <= 17; $i++) {
        //   echo "<tr>";
        //   $hour = date('H', $time_label_time)+1;
        //   $min = date('i', $time_label_time);
        //   $time_label_time = mktime($hour, $min, 0, $month, 01, $year);
        //   $time_label_text = date("H:i", $time_label_time);
        //   echo "<td class='purple week-cyan'>$time_label_text</td>";
        //   // if it's the weekend make it gray with week-gray
        //   echo "<td class='td-content'></td>";
        //
        //   echo "</tr>";
        // }


           ?>
    </tbody>
  </table>
</div>

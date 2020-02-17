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
      case 'previous_week':
        // go back 1 month and store it as the month that is currently being viewed
        $_SESSION['time_currently_displayed'] = date(strtotime("-1 weeks", $_SESSION['time_currently_displayed']));
        // read from stored value which month is being viewed and update month that is shown
        getDateData();
        break;
      case 'next_week':
        $_SESSION['time_currently_displayed'] = date(strtotime("+1 weeks", $_SESSION['time_currently_displayed']));
        // read from stored value which month is being viewed and update month that is shown
        getDateData();
        break;
      case 'that_week':
          $year_month_day = $_GET['week'];
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
  <a class="btn btn-dark" href="?source=week&view=current_week">Back to current week</a>
  <hr class="gray">
  <h3><a href="?source=month&view=that_month&month=<?php echo $ym; ?>"><?php echo $current_displayed_month . " "; ?></a>
    <a href="?source=year&view=that_year&year=<?php echo $current_displayed_year; ?>"><?php echo $current_displayed_year; ?></a>
  </h3>
  <!-- month display -->
  <div class="row row-headline">
    <h3 class="left headline"><a class="blue" href="?source=week&view=previous_week">&lt  </a></h3>
    <div class="middle">
      <h3 class="middle headline">Week <?php echo $current_displayed_week; ?></h3>
    </div>
    <h3 class="right headline"><a class="blue" href="?source=week&view=next_week">  &gt</a></h3>
  </div>
  <table class="table table-bordered table-day">
    <thead head-month>
      <!-- <th class='purple'>Time</th> -->
      <?php
        // the day that is currently being viewed
        $day = date('d', $_SESSION['time_currently_displayed']);
        // the month that is currently being viewed
        $month = date('m', $_SESSION['time_currently_displayed']);
        // the year that is currently being viewed
        $year = date('Y', $_SESSION['time_currently_displayed']);
        // the total number of days in the currently viewed month
        $day_count = date('t', $_SESSION['time_currently_displayed']);
        // storing standard time for labeling hours and on the day table
        $time_label_time = mktime(05, 00, 0, $month, 01, $year);
        // formatting standard time to a human readable string
        $time_label_text = date("H:i", $time_label_time);

        //using the week that is currently displayed
        $dateTime = new DateTime();
        $dateTime->setISODate($year, $current_displayed_week);
        //loop through each day of the week
        for ($day=0; $day <= 6 ; $day++) {
          // formatting string for the modify-function
          $mod_string = '+' . $day . " days";
          // adding the number of days to the week
          $dateTime->modify($mod_string);
          $ymd = $dateTime->format('Y-m-d');
          $day_as_string = $dateTime->format('D');

          // if the day is the same as the current day
          if($ymd == date('Y-m-d', time())) {
            echo "<th class='today'><a href='?source=day&view=that_day&day={$ymd}'>{$day_as_string}</a></th>";
          } else if ($day_as_string == 'Sat') {
            echo "<th class='blue'><a href='?source=day&view=that_day&day={$ymd}'>{$day_as_string}</a></th>";
          } else if ($day_as_string == 'Sun') {
            echo "<th class='red'><a href='?source=day&view=that_day&day={$ymd}'>{$day_as_string}</a></th>";
          } else {
            echo "<th><a href='?source=day&view=that_day&day={$ymd}'>{$day_as_string}</a></th>";
          }
          $dateTime->setISODate($year, $current_displayed_week);
        }

         ?>
      <!-- <th>Mon</th>
        <th>Tue</th>
        <th class="today">Wed</th>
        <th>Thu</th>
        <th>Fri</th>
        <th class='blue'>Sat</th>
        <th class='red'>Sun</th> -->
    </thead>
    <tbody>
      <?php
        // making the rows and cells in table every hour
        // for ($x=1; $x <= 17; $x++) {
        //   echo "<tr>";
        //   $hour = date('H', $time_label_time)+1;
        //   $min = date('i', $time_label_time);
        //   $time_label_time = mktime($hour, $min, 0, $month, 01, $year);
        //   $time_label_text = date("H:i", $time_label_time);
        //   echo "<td class='purple week-cyan'>$time_label_text</td>";
        echo "<tr>";
        //using the week that is currently displayed
          $dateTime = new DateTime();
          $dateTime->setISODate($year, $current_displayed_week);
          for ($i=0; $i <= 6; $i++) {
            // formatting string for the modify-function
            $mod_string = '+' . $i . " days";
            // adding the number of days to the week
            $dateTime->modify($mod_string);
            $ymd = $dateTime->format('Y-m-d');

            // if it's the weekend change background color to gray
            if ($i == 5 || $i == 6) { ?>
              <td class="td-year">
              <table class="month">
                <tr>
                  <td class="no-padding">
                  <?php
                  $ymd;

                  $sql = "SELECT * FROM calendar WHERE event_date = '{$ymd}' ";
                  $sql .= "ORDER BY time_start ASC ";
                  $view_events_q = $no_fr_conn->query($sql);

                  mysqli_num_rows($view_events_q);

                  if(confirmQuery($view_events_q)){
                    while($row = $view_events_q->fetch_assoc()){

                      $event_id = $row['event_id'];
                      $time = $row['time_start'];
                      $title = $row['title']; ?>

                      <div class='td-content week-gray td-week'><a href="?source=edit&event_id=<?php echo $event_id; ?>"><?php echo $time . "<br>" . $title; ?></a></div>

                      <?php

                    }
                  }

                   ?>
                   </td>
                </tr>
              </table>
              </td>
            <?php }
            else { ?>
              <td class="td-year">
              <table class="month">
                <tr>
                  <td>
                  <?php
                $ymd;

                $sql = "SELECT * FROM calendar WHERE event_date = '{$ymd}' ";
                $sql .= "ORDER BY time_start ASC ";
                $view_events_q = $no_fr_conn->query($sql);

                mysqli_num_rows($view_events_q);

                if(confirmQuery($view_events_q)){
                  while($row = $view_events_q->fetch_assoc()){

                    $event_id = $row['event_id'];
                    $time = $row['time_start'];
                    $title = $row['title']; ?>

                    <div class='td-content week-gray td-week'><a href="?source=edit&event_id=<?php echo $event_id; ?>"><?php echo $time . "<br>" . $title; ?></a></div>

                    <?php

                  }
                }

                 ?>
               </td>
            </tr>
          </table>
          </td>

              <?php

            }
            $dateTime->setISODate($year, $current_displayed_week);
          }
          echo "</tr>";
        //}


           ?>
    </tbody>
  </table>
</div>

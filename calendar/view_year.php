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

  // Get and display prev, next and current year when link is clicked or just current year on first load of page
  if (isset($_GET['view'])){
    $view = $_GET['view'];

    switch ($view) {
      case 'previous_year':
        // go back 1 year and store it as the year that is currently being viewed
        $_SESSION['time_currently_displayed'] = date(strtotime("-1 years", $_SESSION['time_currently_displayed']));
        // read from stored value which year is being viewed and update year that is shown
        getDateData();
        //$current_displayed_year = date('Y', $_SESSION['time_currently_displayed']);
        break;
      case 'next_year':
        $_SESSION['time_currently_displayed'] = date(strtotime("+1 years", $_SESSION['time_currently_displayed']));
        // read from stored value which year is being viewed and update year that is shown
        getDateData();
        break;
      case 'that_year':
      $_SESSION['time_currently_displayed'] = mktime(12, 0, 0, 01, 01, $_GET['year']);
      // read from stored value which year is being viewed and update year that is shown
      getDateData();
        break;
      default:
        // find curent year and store it as the year that is currently being viewed
        $_SESSION['time_currently_displayed'] = time();
        // read from stored value which year is being viewed and update year that is shown
        getDateData();
        break;
    }
  } else { // if no buttons have been pushed and view is undefined or any other scenario
      // find curent year and store it as the year that is currently being viewed
      $_SESSION['time_currently_displayed'] = time();
      // read from stored value which year is being viewed and update year that is shown
      getDateData();
  }

   ?>

<div class="col-md-10 body-column-center-year">
  <a class="btn btn-dark" href="?source=year&view=current_year">Back to current year</a>
  <hr class="gray">
  <h3><a class="blue" href="?source=year&view=previous_year">&lt  </a><?php echo $current_displayed_year; ?><a class="blue" href="?source=year&view=next_year">  &gt</a></h3>


  <table class="table table-bordered">
    <thead>
      <?php
        for ($month=1; $month <= 12 ; $month++) {
           $jd=gregoriantojd($month, 1, $current_displayed_year);
           $month_as_string = jdmonthname($jd,0);
          // creating all headers for months and send correct data with get
          echo "<th><a class='black' href='?source=month&view=that_month&month={$current_displayed_year}-{$month}'>{$month_as_string}</a></th>";
        }
        ?>
    </thead>



    <tbody>
      <tr>
        <?php
          $year = date('Y', $_SESSION['time_currently_displayed']);
          $todays_date = date('y-m-d', time());

            // for each column representing a month
            for ($month=1; $month <= 12 ; $month++) {
              // create table inside every column(td) in the year table
              echo "<td class='td-year'>";
              echo "<table class='month'>";

              // the total number of days in the month
              $day_count = date('t', mktime(12, 0, 0, $month, 1, $year));

                  // create cells for as many times as there are days
                  for ($day=1; $day <= $day_count ; $day++) {

                    // find out what weekday it is
                    $weekday = date("N", mktime(12, 0, 0, $month, $day, $year));
                    $weekday_letter = date("l", mktime(12, 0, 0, $month, $day, $year));
                    $weekday_letter = $weekday_letter[0];
                    $date_being_rendered = date('y-m-d', mktime(12, 0, 0, $month, $day, $year));

                    // check if it's a monday
                    if($weekday == 1){
                      // make a cel with week number first
                      // find the week number
                      $week_number = date('W', mktime(12, 0, 0, $month, $day, $year));
                      echo "<tr>";
                      echo "<td class='td-year td-week week-cyan td-hover'><a href='?source=week&view=that_week&week={$year}-{$month}-{$day}'>{$week_number}</a></td>";
                      echo "</tr>";

                      // check if the monday being rendered is the same as today local time and change color if it is
                      if ($date_being_rendered == $todays_date){
                        echo "<tr>";
                        echo "<td class='td-year td-day today td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$day}'><div class='row td-day'><div class='td-left'>$weekday_letter</div><div class='td-right'>$day</div></div></a></td>";
                        echo "</tr>";
                      } else {
                      // if the monday being rendered is not today then make normal cell
                      echo "<tr>";
                      echo "<td class='td-year td-day td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$day}'><div class='row td-day'><div class='td-left'>$weekday_letter</div><div class='td-right'>$day</div></div></a></td>";
                      echo "</tr>";
                      }
                    } else if ($date_being_rendered == $todays_date) {
                      // check if the date being rendered is the same as today local time
                        echo "<tr>";
                        echo "<td class='td-year td-day today td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$day}'><div class='row td-day'><div class='td-left'>$weekday_letter</div><div class='td-right'>$day</div></div></a></td>";
                        echo "</tr>";

                    } else if ($weekday == 6){
                      // if it's saturday change color to blue and background to grey
                      echo "<tr>";
                      echo "<td class='td-year td-day blue week-gray td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$day}'><div class='row td-day'><div class='td-left'>$weekday_letter</div><div class='td-right'>$day</div></div></a></td>";
                      echo "</tr>";
                    } else if ($weekday == 7){
                      // if it's sunday change color to red and background to grey
                      echo "<tr>";
                      echo "<td class='td-year td-day red week-gray td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$day}'><div class='row td-day'><div class='td-left'>$weekday_letter</div><div class='td-right'>$day</div></div></a></td>";
                      echo "</tr>";
                    } else {
                      // make cell with day
                      echo "<tr>";
                      echo "<td class='td-year td-day td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$day}'><div class='row td-day'><div class='td-left'>$weekday_letter</div><div class='td-right'>$day</div></div></a></td>";
                      echo "</tr>";
                    }
                  }
                  // all days in month have been made end table and make new table in next month
                  echo "</table>";
                  echo "</td>";
            }

           ?>
      </tr>
    </tbody>
  </table>
</div>

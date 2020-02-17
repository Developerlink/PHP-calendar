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
      case 'previous_month':
        // go back 1 month and store it as the month that is currently being viewed
        $_SESSION['time_currently_displayed'] = date(strtotime("-1 months", $_SESSION['time_currently_displayed']));
        // read from stored value which month is being viewed and update month that is shown
        getDateData();
        break;
      case 'next_month':
        $_SESSION['time_currently_displayed'] = date(strtotime("+1 months", $_SESSION['time_currently_displayed']));
        // read from stored value which month is being viewed and update month that is shown
        getDateData();
        break;
      case 'that_month':
        $year_month = $_GET['month'];
        $pieces = explode("-", $year_month);
        $year = $pieces[0];
        $month = $pieces[1];
        $_SESSION['time_currently_displayed'] = mktime(12, 0, 0, $month, 1, $year);
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
  <a class="btn btn-dark" href="?source=month&view=current_month">Back to current month</a>
  <hr class="gray">
  <h3><a href="?source=year&view=that_year&year=<?php echo $current_displayed_year; ?>"><?php echo $current_displayed_year; ?></a></h3>
  <!-- month display -->
  <div class="row row-headline">
    <h3 class="left headline"><a class="blue" href="?source=month&view=previous_month">&lt  </a></h3>
    <div class="middle">
      <h3 class="middle headline"><?php echo $current_displayed_month; ?></h3>
    </div>
    <h3 class="right headline"><a class="blue" href="?source=month&view=next_month">  &gt</a></h3>
  </div>
  <table class="table table-bordered">
    <thead>
      <th class="purple">Week</th>
      <th>Mon</th>
      <th>Tue</th>
      <th>Wed</th>
      <th>Thu</th>
      <th>Fri</th>
      <th class='blue'>Sat</th>
      <th class='red'>Sun</th>
    </thead>
    <tbody>
      <?php
        // the month that is currently being viewed
        $month = date('m', $_SESSION['time_currently_displayed']);
        // the year that is currently being viewed
        $year = date('Y', $_SESSION['time_currently_displayed']);
        // the total number of days in the currently viewed month
        $day_count = date('t', $_SESSION['time_currently_displayed']);
        // finding the first day and last day of the currently viewed month
        $first_day = mktime(12, 0, 0, $month, 01, $year);
        $last_day = mktime(12,0,0, $month, $day_count, $year);
        // finding the integer value for first day and last day - 1: monday .... 7: sunday
        $weekday_of_first_day = date("N", $first_day);
        $weekday_of_last_day = date("N", $last_day);

        // making the rows and cells in table
        echo "<tr>"; // start first row

        // calculating variables needed to create the correct amount of empty cells in the first and last week of the month
        $number_of_empty_cells_in_first_week = $weekday_of_first_day - 1;
        $number_of_empty_cells_in_last_week = 7 - $weekday_of_last_day;

        // counter to keep track of many cells have been made for a given week
        $count = 1;
        // finding the week number from the first day of the month
        $week_number = date('W', mktime(12 ,0 , 0, $month, 01, $year));
        $day = date('d', mktime(12 ,0 , 0, $month, 01, $year));

        // making first cell with week number
        echo "<td class='purple month week-cyan td-hover'><a href='?source=week&view=that_week&week={$year}-{$month}-{$day}'>{$week_number}</a></td>";
        // making empty cells for first week
        for($x = 1; $x <= $number_of_empty_cells_in_first_week; $x++){
          echo "<td class='month'></td>";
          $count++;
        }

        // making nonempty cells for the rest
        for($d = 1; $d <= $day_count; $d++){

          $day_being_rendered = mktime(12, 0, 0, $month, $d, $year);
          // finding the integer value for the day - 1: monday .... 7: sunday
          $day_as_integer = date("N", $day_being_rendered);

          // if it's the current date then change color to cyan
          if(date('y-m-d', time()) == date('y-m-d', $day_being_rendered)){
            echo "<td class='today month td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$d}'>$d</a></td>";

            // if it's the current date and a sunday remember to start a new row unless it's the last day of the month
            if($day_as_integer == 7 && $d < $day_count){
              // then close row and start next row/week
              echo "</tr>";
              echo "<tr>";
              // find the week number by using the first day in the next week and make a cell for it before continuing to display the days
              $week_number = date('W', mktime(12 ,0 , 0, $month, $d+1, $year));
               $day = date('d', mktime(12 ,0 , 0, $month, $d+1, $year));
              echo "<td class='purple month week-cyan td-hover'><a href='?source=week&view=that_week&week={$year}-{$month}-{$day}'>{$week_number}</a></td>";
            }

          } else if ($day_as_integer == 6) {
            // if it's a regular saturday change color to red
            echo "<td class='blue month week-gray td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$d}'>$d</a></td>";
          } else if ($day_as_integer == 7) {
            // if it's a sunday check if it is NOT last day of the month change color to red
               if($d < $day_count) {
                 // make the sunday first
                 echo "<td class='red month week-gray td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$d}'>$d</a></td>";
                 // then close row and start next row/week
                 echo "</tr>";
                 echo "<tr>";
                 // find the week number by using the first day in the next week and make a cell for it before continuing to display the days
                 $week_number = date('W', mktime(12 ,0 , 0, $month, $d+1, $year));
                 $day = date('d', mktime(12 ,0 , 0, $month, $d+1, $year));
                 echo "<td class='purple month week-cyan td-hover'><a href='?source=week&view=that_week&week={$year}-{$month}-{$day}'>{$week_number}</a></td>";
               } else {
                 // if it is indeed the last day then make only the cell for the day not the week
                 echo "<td class='red month week-gray td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$d}'>$d</a></td>";
               }
          } else {
            echo "<td class='month td-hover'><a href='?source=day&view=that_day&day={$year}-{$month}-{$d}'>$d</a></td>";
          }

        }

        // making empty cells for the last week
        for($x = 1; $x <= $number_of_empty_cells_in_last_week; $x++){
          echo "<td class='month'></td>";
        }

        echo "</tr>"; // close row the last row

           ?>
    </tbody>
  </table>
</div>
<?php
  // playing around with dates in php

  // // count the number of days in the current month(
  // $day_count = date('t', $_SESSION['month_currently_displayed']);
  // echo "The number of days in this month is " . $day_count;
  // // find todays number
  // echo "<br>";
  // $todays_date = date('Y-m-d');
  // echo "The current date is " . $todays_date;
  // echo "<br>";
  //
  // $list_days_in_current_month = array();
  // echo $month = date('m', $_SESSION['month_currently_displayed']);
  // echo $year = date('Y', $_SESSION['month_currently_displayed']);
  //
  //
  // for($d=1; $d<=$day_count; $d++){
  //   $time = mktime(12, 0, 0, $month, $d, $year);
  //     if (date('m', $time) == $month)
  //         $list_days_in_current_month[]= date('Y-m-d-D', $time);
  // }

   ?>

<?php include "includes/header.php"; ?>


<!-- Creating a GRID for the layout -->
<div class="layout">
  <!-- This is the top navigation div -->
  <?php include "includes/navigation.php"; ?>

  <!-- This the content div -->
  <div class="content">

   <?php

   if(isset($_GET['source'])){

     $goto_source = $_GET['source'];

     switch ($goto_source) {
       case 'statistics':
         include "calendar/calendar_statistics.php";
         break;
       case 'day':
         include "calendar/view_day.php";
         break;
       case 'month':
         include "calendar/view_month.php";
         break;
       case 'year':
         include "calendar/view_year.php";
         break;
       case 'add':
         include "calendar/add_event.php";
         break;
       case 'edit':
         include "calendar/edit_event.php";
         break;
       default:
         include "calendar/view_week.php";
         break;
     }

   } else {
     // default page
     include "calendar/view_week.php";
   }


    ?>
    
  </div>
  <!-- This is the sidebar navigation div -->
  <?php include "includes/sidebar.php"; ?>
</div>





<?php include "includes/footer.php"; ?>

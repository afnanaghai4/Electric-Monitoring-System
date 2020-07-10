<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');
include_once('../functions/updateDailyReport.php');

if (!isLoggedIn())
  header('Location: login.php')

  ?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body class="bg-gray-200">
  <?php include('../includes/navbar.php'); ?>
  <?php
  if (debug($GLOBALS['debug'])) {
    echo print_r($_POST);
  }

  if (isset($_SESSION['house_id'])) {
    $status = "";
    DailyReport();
    $row = get_daily_details($_SESSION['house_id']);
    if ($row != 0) {
      ?>
      <div class="lg:flex py-4 px-6 bg-white border-b border-black mb-6">
        <div class="text-left w-full flex justify-center">
          <p class="text-center text-3xl">Report</p>
        </div>
        <div class="align-center lg:text-base text-xs lg:-ml-64 text-center lg:text-left">
          <p class="font-semibold ">Time of report: <br /><?php echo  date_format(date_create($row['report_time']), 'h:ia dS F Y') ?></p>
        </div>
      </div>
      <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4">
        <p class="text-center font-bold text-xl">Total Consumption</p>
      </div>
      <div class="text-center p-4 mb-6 bg-white shadow">
        <p class="text-center font-bold text-2xl"><?php echo setUnits($row['total_consumption']) . ' ' . $_SESSION['unit'] ?></p>
      </div>
      <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4">
        <p class="text-center font-bold text-xl">Electricity Wasted</p>
      </div>
      <div class="text-center p-4 mb-6 bg-white shadow">

        <p class="text-center font-bold text-2xl text-red-600"><?php echo setUnits($row['electricity_wasted']) . ' ' . $_SESSION['unit'] ?></p>
      </div>
      <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4">
        <p class="text-center font-bold text-xl">Electricity saved by the application (Approximation)</p>
      </div>
      <div class="text-center p-4 mb-4 bg-white shadow">

        <p class="text-center font-bold text-2xl text-green-600"> <?php echo setUnits($row['saved']) . ' ' . $_SESSION['unit'] ?></p>
      </div>
      <div class="flex justify-center mb-4">
        <div class="mt-4 text-center">
          <p class="block text-gray-700 text-lg font-bold mb-2">
            Generate again for updated values
          </p>
          <a href="report.php">
            <button class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded">Generate Now</button>
          </a>
        </div>
      </div>
    <?php
      } else { ?>
      <img src="../images/noreport.png" alt="" class="lg:w-32 lg:h-32 w-64 h-64 mx-auto lg:mt-32 mt-16" />
      <div class="flex justify-center mb-4">
        <div class="mt-4 text-center">
          <p class="block text-gray-700 text-lg font-bold mb-2">
            Generate again for updated values
          </p>
          <a href="report.php">
            <button class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded">Generate Now</button>
          </a>
        </div>
      </div>
    <?php }
    } else { ?>
    <script>
      location.replace("404.php");
    </script>
  <?php }
  ?>

  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
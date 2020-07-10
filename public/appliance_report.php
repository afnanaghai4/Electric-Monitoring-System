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
    $status = "";
    $result = get_all_electricity_appliance($_SESSION['house_id']);
    if (mysqli_fetch_assoc($result)>0) {
      ?>
      <div class="flex py-4 px-6 bg-white border-b border-black mb-6">
        <div class="text-left w-full flex justify-center">
          <p class="text-center text-xl lg:text-3xl">Appliance Consumption</p>
        </div>
      </div>
      <div class="flex shadow-lg mb-4">
        <div class="w-1/2">
          <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4 border-r">
            <p class="text-center font-bold text-xl">Appliance</p>
          </div> <?php $result = get_all_electricity_appliance($_SESSION['house_id']);
          while($row = mysqli_fetch_assoc($result)) { ?>
            <form action="appliance.php" method="GET" class="mb-0">
            <div class="text-center bg-white border-r border-gray-500 border-b border-gray-500 h-24">
              <button class="w-full p-4">
              <p class="text-center font-bold lg:text-3xl text-xl leading-tight"><?php echo $row['appliance_name'] . ' - ' . $row['appliance_id'] ?></p>
              <p class="text-center lg:text-2xl text-base leading-tight">Room name: <span class="font-bold"><?php echo $row['room_name'] ?></span></p>
              </button>
            </div>
            <input type="hidden" name="name" value="<?php echo $row['appliance_name'] ?>">
            <input type="hidden" name="id" value="<?php echo $row['appliance_id'] ?>">
            </form>
         <?php } ?>
        </div>
        <div class="w-1/2">
          <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4">
            <p class="text-center font-bold text-xl">Energy Consumed</p>
          </div>
          <?php $result = get_all_electricity_appliance($_SESSION['house_id']); 
          while($row = mysqli_fetch_assoc($result)) { ?>
          <div class="p-4 bg-white border-b border-gray-500  h-24 flex flex-wrap justify-center items-center">
            <p class="text-center font-bold lg:text-3xl text-2xl leading-tight "><?php echo setUnits($row['current_consumption']) . ' ' . $_SESSION['unit'] ?></p>
            <p class="text-center lg:text-xl text-base w-full">Per month</p>
          </div>
          <?php } ?>
        </div>
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
  ?>

  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
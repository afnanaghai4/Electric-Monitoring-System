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
    echo print_r($_SESSION);
  }
  if (isset($_POST['submit'])) {
    $_SESSION['unit'] = $_POST['unit'];
    $_SESSION['wait_time'] =  $_POST['time'] . ' ' . $_POST['timeunit'];
  }

  ?>
  <p class="text-center text-3xl py-4 mb-6 border-b border-gray-500 bg-white">Settings</p>
  <?php if (explode(" ", $_SESSION['wait_time'])[1] == 'h') {
    ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mb-6 rounded relative" role="alert">
      <strong class="font-bold">Warning: </strong>
      <span class="block sm:inline">It is recommended to keep wait time in minutes</span>
    </div>
  <?php
  } ?>

  <form action="settings.php" id="myForm" method="POST">
    <div class="mx-6 mb-6 rounded-lg flex items-center justify-center bg-white shadow-lg justify-start lg:px-10 px-4 py-4">
      <div class="text-left w-full">
        <p class="font-bold text-2xl mb-1">
          Select Unit
        </p>
        <p>Current unit: <span class="font-semibold"><?php echo $_SESSION['unit'] ?></span></p>

      </div>
      <div>
      
        <div>
          <select name='unit' class="lg:w-32 w-24 h-8 pl-2 bg-white border border-gray-400 hover:border-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
            <option value="kW/H" <?= $_SESSION['unit'] == 'kW/H' ? ' selected="selected"' : ''; ?>>kW/H</option>
            <option value="kW/m" <?= $_SESSION['unit'] == 'kW/m' ? ' selected="selected"' : ''; ?>>kW/m</option>
            <option value="kW/s" <?= $_SESSION['unit'] == 'kW/s' ? ' selected="selected"' : ''; ?>>kW/s</option>
            <option value="W/H" <?= $_SESSION['unit'] == 'W/H' ? ' selected="selected"' : ''; ?>>W/H</option>
            <option value="W/m" <?= $_SESSION['unit'] == 'W/m' ? ' selected="selected"' : ''; ?>>W/m</option>
            <option value="W/s" <?= $_SESSION['unit'] == 'W/s' ? ' selected="selected"' : ''; ?>>W/s</option>
          </select>
        </div>
      </div>
    </div>
    <div class="mx-6 mb-6 rounded-lg flex bg-white shadow-lg justify-start lg:px-10 px-4 py-4">
      <div class="text-left w-full">
        <p class="font-bold lg:text-2xl text-xl mb-1 items-center flex">
          Select time until lights close off automatically
        </p>
        <p class="lg:text-base text-base">Current wait time: <span class="font-semibold"><?php echo $_SESSION['wait_time'] ?></span></p>
      </div>
      <div class="items-center lg:flex">
        <div class="lg:mr-8 mr-0">
          <label class="block text-gray-500 lg:text-sm text-xs mb-2 text-center" for="appliance">
            <i>Time (eg 1, 2, 10)</i>
          </label>
          <input required class="lg:mb-0 mb-2 bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="time" type="number" placeholder="Time" value="<?php echo explode(" ", $_SESSION['wait_time'])[0] ?>" />
        </div>
        <div class="text-center">
          <label class="block text-gray-500 text-sm mb-4 text-center" for="appliance">
            <i>Select unit</i>
          </label>
          
          <select class="w-16 h-8 bg-white border border-gray-400 hover:border-gray-500 rounded shadow leading-tight focus:outline-none focus:shadow-outline" name='timeunit'>
            <option value="h" <?= explode(" ", $_SESSION['wait_time'])[1] == 'h' ? ' selected="selected"' : ''; ?>>h</option>
            <option value="m" <?= explode(" ", $_SESSION['wait_time'])[1] == 'm' ? ' selected="selected"' : ''; ?>>m</option>
          </select>
        </div>
      </div>
    </div>
    <div class="flex justify-center mb-4">
      <div class="mt-4 text-center">
        <input type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded" value="Save Changes" />
      </div>
    </div>
  </form>
</body>

</html>

<?php mysqli_close($connection) ?>
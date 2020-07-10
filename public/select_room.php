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

<body class="bg-gray-300">
  <?php include('../includes/navbar.php'); ?>
  <?php
  if (debug($GLOBALS['debug'])) {
    echo print_r($_POST);
  }

  if (isset($_SESSION['house_id'])) {
    //If yes then check for appliances
    $result = get_rooms($_SESSION['house_id']);
    //If no rooms
    if (mysqli_num_rows($result) <= 0) {
      echo '<p class="text-center text-3xl mb-4">You have no rooms.</p>';
      echo '<p class="text-center text-xl mb-4">Go back to homepage and add rooms</p>';
      ?>
      <?php
        } else {
          echo '<p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Please Select a room</p>';
          while ($row = mysqli_fetch_assoc($result)) {
            ?>
        <div class="pl-6 pr-6 pb-6">
          <form action="rooms_report.php" method="POST">
            <button type='submit' class="bg-white shadow-lg py-4 rounded-lg w-full">
              <div class="text-center">
                <p class="font-bold sm:text-3xl text-2xl mb-1">
                  <?php echo $row['room_name'] == null ? 'Room id: ' . $row['room_id'] : $row['room_name']; ?>
                </p>
                <div class="flex justify-center mb-1">
                  <div class="bg-green-400 w-2 h-2 rounded-full mt-2 mr-2">
                  </div>
                  <p class="text-sm text-gray-500">
                    Online
                  </p>
                </div>
              </div>
            </button>
            <input type="hidden" name="room_name" value="<?php echo $row['room_name'] ?>" />
            <input type="hidden" name="room_id" value="<?php echo $row['room_id'] ?>" />
          </form>
        </div>
    <?php
        }
      }
    } else { ?>
    <script>
      location.replace("login.php");
    </script>
  <?php }
  ?>

  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
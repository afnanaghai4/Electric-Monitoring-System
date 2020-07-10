<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');

if(!isLoggedIn()) 
  header('Location: login.php')
?>

<html>
<?php
if(debug($GLOBALS['debug'])) {
  echo print_r($_SESSION);
}
$time = getTime();
include_once('../includes/header.php'); ?>

<body class="bg-gray-300">
  <?php include('../includes/navbar.php'); ?>
  <div class="">
    <?php 
    $time=getTime();
    // updateTime($time);
    $email = $_SESSION['email'];
    $result = get_rooms($_SESSION['house_id']);

    if (mysqli_num_rows($result) <= 0) {
      echo '<p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Hmmm... Seems like you do not have any rooms</p>';
      ?>
      <a href="addroom.php">
        <div class="pl-6 pr-6 pb-6">
          <div class="bg-white shadow-md py-4 rounded-lg">
            <div class="flex justify-center items-center">
              <div class="relative bg-transparent border-dashed border-gray-600 hover:border-blue-600 border-2 w-20 h-20 rounded-full">
                <div class="circle"></div>
              </div>
              <p class="text-3xl pl-10">
                Add a Room
              </p>
            </div>
          </div>
        </div>
      </a>
      <?php
      } else {
        echo '<p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Your rooms</p>';
        while ($row = mysqli_fetch_assoc($result)) {
          ?>
        <div class="pl-6 pr-6 pb-6">
          <div class="bg-white shadow-md py-4 rounded-lg text-center">
          <form action="room.php" method="POST">
          <button type='submit'  class="w-full">
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
              <div class="flex justify-center">
                <p class="text-xs mr-2">🕑</p>
                <p class="text-sm text-gray-500">
                 <?php 
                  $update_time = get_update_time($_SESSION['house_id']);
                  $current_time = strtotime(getTime());
                  $update_time = strtotime($update_time);
                  $diff = abs($update_time - $current_time);
                  if($diff < 0 ) {
                    $diff = 0;
                  }
                  echo $diff . ' ' . 'seconds ago';
                 ?>
                </p>
              </div>
            </div>
        </button>
          <input type="hidden" name="room_name" value="<?php echo $row['room_name'] ?>" />
          <input type="hidden" name="room_id" value="<?php echo $row['room_id'] ?>" />
          </form>
          <!-- <form action="home.php" class="w-full">
            <input type="submit" name="flag" value="DEV" class="appearance-none bg-blue-500 text-white py-1 px-8 border border-blue-700 rounded-lg font-semibold m-0"/>
          </form> -->
          <form action="edit.php" class="w-full m-0" method="POST">
            <input type="submit" name="Edit" value="Edit Room" class="appearance-none bg-blue-500 text-white py-1 px-8 border border-blue-700 rounded-lg font-semibold m-0"/>
            <input type="hidden" name="room_id" value="<?php echo $row['room_id'] ?>" />
          </form>
          <?php if($row['flag'] == 1) { ?>
            <button class="appearance-none bg-orange-400 text-white py-1 px-2 text-xs mt-2 border border-orange-500 rounded-sm">Real</button>
          <?php } else { ?>
            <button class="appearance-none bg-green-400 text-white py-1 px-2 text-xs mt-2 border border-green-500 rounded-sm">Fake</button>
          <?php } ?>
          
          </div>
         
        </div>
      <?php
        }
      }
      if (mysqli_num_rows($result) != 0) {
        ?>
      <a href="addroom.php">
        <div class="pl-6 pr-6 pb-6">
          <div class="bg-white shadow-md py-4 rounded-lg">
            <div class="flex justify-center items-center">
              <div class="relative bg-transparent border-dashed border-gray-600 hover:border-blue-600 border-2 w-20 h-20 rounded-full">
                <div class="circle"></div>
              </div>
              <p class="text-3xl pl-10">
                Add a Room
              </p>
            </div>
          </div>
        </div>
      </a>
    <?php } ?>
  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
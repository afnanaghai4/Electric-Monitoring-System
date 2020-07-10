<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');

if (!isLoggedIn())
  header('Location: login.php')

  ?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body class="bg-gray-300">
  <?php include('../includes/navbar.php'); ?>
  <div class="">

    <?php
    //Check if this room exists

    $time = getTime();
    if (debug($GLOBALS['debug'])) {
      echo print_r($_POST) . '<br />';
    }

    if (isset($_POST['room_id'])) {
      if (isset($_POST["switch"])) {
        if ($_POST['switch'] == 'Turn On') {
          toggleButton($_POST['appliance_id'], 'on');
          add_into_activity_turnOn($_POST['appliance_id'], $time, 'online');
        } else {
          toggleButton($_POST['appliance_id'], 'off');
          update_autoOffTime($_POST['appliance_id'], false);
          update_electricity($_POST['appliance_id']);
          add_into_activity_turnOff($_POST['appliance_id'], $time, 'offline');
        }
      }
      if (isRoom($_POST['room_id'])) {
        //If yes then check for appliances
        $result = get_appliances($_POST['room_id']);
        //If no appliance
        if (mysqli_num_rows($result) <= 0) {
          echo '<p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Hmmm... Seems like you do not have any appliances Added yet</p>';
          ?>
          <a href="<?php echo 'found.php?room_id=' . $_POST['room_id'] ?>">
            <div class="pl-6 pr-6 pb-6">
              <div class="bg-white shadow py-4 rounded-lg">
                <div class="flex justify-center items-center">
                  <div class="relative bg-transparent border-dashed border-gray-600 hover:border-blue-600 border-2 w-20 h-20 rounded-full">
                    <div class="circle"></div>
                  </div>
                  <p class="text-3xl pl-10">
                    Add Appliance
                  </p>
                </div>
              </div>
            </div>
          </a>
          <?php
              } else {
                echo '<p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Your Appliances</p>';
                while ($row = mysqli_fetch_assoc($result)) {
                  ?>
            <div class="mx-6 mb-8 rounded-lg flex bg-white shadow-lg justify-start">
              <form action="appliance.php" method="GET" class="w-full">
                <button type='submit' class="pr-32 pt-2 pl-6">
                  <input type="hidden" name="id" value="<?php echo $row['appliance_id'] ?>" />
                  <input type="hidden" name="name" value="<?php echo $row['appliance_name'] ?>" />
                  <div class="text-left">
                    <p class="font-bold text-2xl mb-1">
                      <?php echo $row['appliance_name'] ?>
                    </p>
                    <p class="font-bold text-xl mb-1">
                      <?php echo 'ID: #' . $row['appliance_id'] ?>
                    </p>

                    <div class="flex justify-center mb-1">
                      <?php if ($row['is_on'] == 'on') { ?>
                        <div class="bg-green-400 w-2 h-2 rounded-full mt-2 mr-2">
                        </div>
                        <p class="text-sm text-gray-500">
                          Device is On
                        </p>
                      <?php } else { ?>
                        <div class="bg-red-400 w-2 h-2 rounded-full mt-2 mr-2">
                        </div>
                        <p class="text-sm text-gray-500">
                          Device is Off
                        </p>
                      <?php } ?>
                    </div>
                    <div class="flex justify-center">
                      <p class="text-center text-xs font-bold text-blue-500">Click to see more</p>
                    </div>
                  </div>
                </button>
              </form>
              <form action="room.php" method="POST" class="-ml-20 align-center flex pr-4">
                <?php if ($row['is_on'] == 'on') { ?>
                  <input type='hidden' name="room_name" value='<?php echo $_POST["room_name"] ?>' />
                  <input type='hidden' name="room_id" value='<?php echo $_POST["room_id"] ?>' />
                  <input type='submit' name="switch" class="bg-green-500 py-2 px-2 rounded-lg m-auto" value="Turn Off" />
                <?php } else { ?>
                  <input type='hidden' name="room_name" value='<?php echo $_POST["room_name"] ?>' />
                  <input type='hidden' name="room_id" value='<?php echo $_POST["room_id"] ?>' />
                  <input type='submit' name="switch" class="bg-red-500 py-2 px-2 rounded-lg m-auto" value="Turn On" />
                <?php } ?>
                <input type="hidden" name="appliance_id" value='<?php echo $row["appliance_id"] ?>' />
              </form>
            </div>
        <?php
              }
            }
          } else { ?>
        <script>
          location.replace("404.php");
        </script>
      <?php }
        ?>
      <form id="form" method="post" onsubmit="return formSubmit();" class="block m-0">
        <div class="pl-6 pr-6 pb-6">
          <div class="bg-white shadow py-4 rounded-lg">
            <div class="mb-4 px-4">
              <label class="block text-gray-700 text-lg font-bold mb-2" for="appliance">
                Count
              </label>
              <input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="count" type="number" value="" />
            </div>
            <div class="mt-4 flex justify-center">
              <input type='hidden' name='room_name' value="<?php echo $_POST['room_name'] ?>" />
              <input type='hidden' name='room_id' value="<?php echo $_POST['room_id'] ?>" />
              <input type='submit' value='change count' class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded" />
            </div>
          </div>
        </div>
      </form>
      <a href="home.php">
        <p class="text-center text-base pb-2 text-blue-500">Go back to Homepage</p>
      </a>
    <?php } else { ?>
      <script>
        location.replace("404.php");
      </script>
    <?php } ?>

  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script>
    function formSubmit() {
      $.ajax({
        type: 'POST',
        url: 'count.php',
        data: $('#form').serialize()
      })
    }
  </script>
</body>

</html>

<?php mysqli_close($connection) ?>
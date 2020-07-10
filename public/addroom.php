<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');

if(!isLoggedIn()) 
  header('Location: login.php');
?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body class="bg-gray-300">
  <?php include('../includes/navbar.php'); ?>
  <div class="bg-gray-300">
    <p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Add Room</p>
    <form action="searching.php" method="post">
      <div class="pl-6 pr-6 pb-6">
        <div class="bg-white shadow py-4 rounded-lg">
          <div class="mb-4 px-4">
            <label class="block text-gray-700 text-lg font-bold mb-2" for="name">
              Room Name
            </label>
            <input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="room_name" type="text" placeholder="Room Name" value="" />
          </div>
          <div class="mb-4 px-4">
            <label class="block text-gray-700 text-lg font-bold mb-2" for="appliance">
              Number of Appliances
            </label>
            <input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="appliances" type="number" placeholder="Number of Appliances" value="" />
          </div>
          <div class="mt-4 flex justify-center">
            <input type='submit' name='submit' value='Add room' formmethod="post" class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded" />
          </div>
        </div>
      </div>
    </form>
    <a href="home.php">
      <p class="text-center text-base pb-2 text-blue-500">Go back to Homepage</p>
    </a>
  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
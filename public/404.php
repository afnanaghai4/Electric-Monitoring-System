<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');

?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body>

  <div class="w-screen h-screen flex items-center justify-center bg-gray-200">
    <div class="bg-white py-24 max-w-3xl flex-1 shadow-lg">
      <div class="w-full text-center text-6xl leading-none font-black mb-2" style="font-size: 120px">
        <p class=" text-gray-900">
          4<span class="text-blue-500">0</span>4</p>
      </div>
      <div>
        <p class="text-center text-black text-base tracking-tight uppercase font-semibold leading-relaxed mb-4">
          The page you requested could not be found
        </p>
        <a href="home.php">
          <p class="text-center text-base pb-2 text-blue-500">Go back to Homepage</p>
        </a>
      </div>
    </div>



  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
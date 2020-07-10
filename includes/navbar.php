<?php
// include_once('home/automar7/public_html/ems/functions/queries.php');
include_once('../functions/queries.php') ?>
<html>

<head>
</head>

<body>
  <nav class="flex items-center justify-between flex-wrap bg-teal-500 py-2 px-6">
    <div class="flex items-center flex-shrink-0 text-white mr-6">
      <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg">
        <path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z" /></svg>
      <span class="font-semibold lg:text-xl text-lg tracking-tight">Electric Monitoring System</span>
    </div>
    <div class="block lg:hidden" onclick="toggle();">
      <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <title>Menu</title>
          <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
        </svg>
      </button>
    </div>

    <div class="w-full hidden flex-grow lg:flex lg:items-center lg:w-auto" id="toggle">
      <div class="text-sm lg:flex-grow">
        <a href="home.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
          Home
        </a>
        <a href="report.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
          Reports
        </a>
        <a href="select_room.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
          Room Report
        </a>
        <a href="appliance_report.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
          Appliance Report
        </a>
        <a href="settings.php" class="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
          Settings
        </a>
      </div>
      <div class="flex">
        <?php
        $update_time = get_update_time($_SESSION['house_id']);
        $current_time = strtotime(getTime());
        $update_time = strtotime($update_time);
        $diff = abs($update_time - $current_time);
        $diff = 60 - $diff;
        if($diff<0) {
          $diff = 0;
        }
        ?>
        <p class="pr-4 items-center flex text-white">
          time left to update: <?php echo $diff ?>s
        </p>
        <form action="logout.php" method="POST" class="lg:m-0">
          <input type='submit' name='submit' value="Log out" class="appearance-none inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 bg-transparent hover:bg-white mt-4 lg:mt-0 " />
        </form>

      </div>
    </div>
  </nav>
  <script>
    let bool = false;
    const toggle = () => {
      if (bool) {
        bool = false;
        document.getElementById('toggle').classList.remove('block');
        document.getElementById('toggle').classList.add('hidden');
      } else {
        document.getElementById('toggle').classList.remove('hidden');
        document.getElementById('toggle').classList.add('block');
        bool = true;
      }
    }
  </script>
</body>

</html>
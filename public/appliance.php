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

<body class="bg-gray-200">
  <?php include('../includes/navbar.php'); ?>
  <div class="">

    <?php
    if (debug($GLOBALS['debug'])) {
      echo print_r($_POST);
    }


    $status = "";
    
    if (isset($_GET['id'])) {
      $activity_set = get_activity($_GET['id']);
      $result = get_appliance_details($_GET['id']); ?>
      <div class="flex items-center py-4 px-6 bg-white border-b border-gray-500 shadow-md">
        <div class="flex-grow flex items-center w-auto ">
          <?php while ($row = mysqli_fetch_assoc($result)) {
              $status = $row['is_on'];
              ?>
            <?php echo '<p class="flex-grow text-center text-xl lg:text-3xl">' . $row['appliance_name'] . ' - ID: #' . $_GET['id'] . '</p>' ?>          
        </div>
      </div>
  </div>
  <p class="text-center text-base py-6 ">Number of people in the room: <?php echo get_count($row['appliance_id']) ?></p>
  <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4 pl-6">
    <p class="text-center font-bold text-xl">Device On Off Activity</p>
  </div>
  <div class="text-center p-4 mb-6 bg-white shadow">
    <div class=" max-w-lg max-h-lg m-auto">
      <canvas id="myChart"></canvas>
    </div>

  </div>
  <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4 pl-6">
    <p class="text-center font-bold text-xl">Current Electricity Consumed</p>
  </div>
  <div class="text-center p-4 mb-6 bg-white shadow">
    <?php $result = get_electricity_detials($_GET['id']);
      $row = mysqli_fetch_assoc($result);  
    ?>
    <p class="text-center font-bold text-2xl"><?php echo setUnits($row['current_consumption']) . ' ' . $_SESSION['unit']?> </p>
  </div>
  <div class="text-center bg-gray-400 border-b border-t border-gray-700 py-4 pl-6">
    <p class="text-center font-bold text-xl">Electricity Consumption Yesterday</p>
  </div>
  <div class="text-center p-4 mb-6 bg-white shadow">
    <p class="text-center font-bold text-2xl">0 kW/day</p>
  </div>
<?php
  }
} else { ?>
<script>
  location.replace("404.php");
</script>
<?php }
?>
<a href="home.php">
  <p class="text-center text-base pb-2 text-blue-300">Go back to Homepage</p>
</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
  let timeStamps = [];
  let statusArray = [];
 
  <?php while ($row = mysqli_fetch_assoc($activity_set)) {
    if ($row['current_state'] == 'online') {
      $date = date_create($row['turnOn_time']); ?>
      if (timeStamps[timeStamps.length - 1] != '<?php echo date_format($date, 'D h:i a') ?>') {
        timeStamps.push('<?php echo date_format($date, 'D h:i a') ?>')
        console.log(timeStamps);
        statusArray.push(1);
      }
      if (timeStamps.length > 10) {
        timeStamps.shift()
        statusArray.shift()
      }
    <?php } else {
        $date = date_create($row['turnOff_time']); ?>
      if (timeStamps[timeStamps.length - 1] != '<?php echo date_format($date, 'D h:i a') ?>') {
        timeStamps.push('<?php echo date_format($date, 'D h:i a') ?>')
        console.log(timeStamps)
        statusArray.push(0);
      }

      if (timeStamps.length > 10) {
        timeStamps.shift()
        statusArray.shift()
      }
    <?php } ?>
   
  <?php } ?>
  const doDate = () => {
    const now = new Date();
  }
  

    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
      type: 'line',

      // The data for our dataset
      data: {
        labels: timeStamps,
        datasets: [{
          // lineTension: 0,
          label: 'Activity',
          backgroundColor: 'rgb(255, 99, 132, 0.5)',
          borderColor: 'rgb(255, 99, 132)',
          data: statusArray,
          steppedLine: true,
          snapGaps: true,
        }]
      },
      // Configuration options go here
      options: {
        animation: {
          duration: 300,
          easing: 'linear'
        },
        scales: {
          yAxes: [{
            ticks: {
              // Include a dollar sign in the ticks
              callback: function(value, index, values) {
                if (value == 1)
                  return 'On';
                return 'Off'
              },
              max: 1,
              min: 0,
              stepSize: 1
            }
          }]
        }
      }
    });

</script>
</body>

</html>

<?php mysqli_close($connection) ?>
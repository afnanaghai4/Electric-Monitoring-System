<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');
$_SESSION['random_ids'] =  UniqueRandomNumbersWithinRange(2000, 3001, 7);
//check if logged in
if(isLoggedIn()) {
  header("Location: home.php");
}
$_SESSION['room_id'] = $starting_id_room = 01;
if (isset($_POST['submit'])) {
  //form was submitted
  $email = $_POST["email"];
  $password = $_POST["password"];
  $message = "Logged in";
  $link = "login.php";

  //perfom queries
  $query = "Select email, pass from accounts where email='{$email}' and pass='{$password}'";
  $result = mysqli_query($connection, $query);
  if (!$result) {
    die('Database query failed.' . mysqli_error($connection));
  }
  if (mysqli_num_rows($result) <= 0) {
    $message = 'Wrong credentials or account doesn\'t exists';
    $link = "login.php";
  } else {
    header("Location: home.php");
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    $_SESSION['house_id'] = get_house_id($email);
    $_SESSION['loggedin'] = true;
    $room_id = get_room_id($_SESSION['house_id']);
    $_SESSION['unit'] = get_unit($_SESSION['house_id']);
    $_SESSION['wait_time'] = get_wait_time($_SESSION['house_id']);
    if($room_id==0) {
      $_SESSION['room_id'] = $_SESSION['house_id'] . $starting_id_room;
    } else {
      $room_id++;
      $_SESSION['room_id'] = $room_id;
    }
    
  }
} else {
  $firstname = "";
  $lastname = "";
  $email = "";
  $password = "";
  $message = "Please log in";
  $link = "login.php";
}


?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body>
  <div class="bg-cover bg-fixed bg-no-repeat bg-center flex" style="background-image: url(../images/loginbg.jpg)">
    <div class="w-screen h-screen flex items-center justify-center">
      <form class="rounded px-8 pt-6 pb-8 mb-4 max-w-lg flex-1" action='<?php $link ?>' method="post">
        <div class="flex justify-center text-center mb-2">
          <p class="text-center text-white text-3xl">
            <?php echo $message ?>
          </p>
        </div>
        <div class="mb-4">
          <div class="inputWithIcon text-sm">
            <input class="bg-transparent custom border-b border-gray-200 appearance-none w-full py-2 px-3 text-base text-white leading-tight" name="email" type="text" placeholder="Email" value="<?php $email ?>" />
            <i class="fa fa-envelope fa-lg fa-w" aria-hidden="true"></i>
          </div>
        </div>
        <div class="mb-6">
          <div class="inputWithIcon">
            <input class="bg-transparent custom border-b border-gray-200 appearance-none w-full py-2 px-3 text-white leading-tight" name="password" type="password" placeholder="Password" value="" />
            <i class="fa fa-lock fa-lg fa-w" aria-hidden="true"></i>
          </div>
        </div>
        <div class="flex justify-center mb-4">
          <input type="submit" name="submit" value="LOG IN" class="cursor-pointer appearance-none bg-white hover:bg-blue-600 hover:text-white text-blue-500 font-bold py-3 w-full rounded-full" />
        </div>
        <div class="flex justify-center mb-4">
          <p class="text-base text-white font-bold">
            Don't have an account?
            </a>
        </div>
        <div class="flex justify-center ">
          <a class="text-base text-yellow-400 font-bold hover:underline hover:text-yellow-200" href="signup.php">
            Register Now
          </a>
        </div>

      </form>
    </div>
  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
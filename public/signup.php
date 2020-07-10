<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');
//Check if signed in
$_SESSION['random_ids'] =  UniqueRandomNumbersWithinRange(2000, 3001, 7);
$_SESSION['room_id'] = $starting_id_room = 01;
if(isLoggedIn()) {
  header("Location: home.php");
}
if (isset($_POST['submit'])) {
  //form was submitted
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $message = "Signed in";

  //perfom queries
  $query = "Select email from accounts where email='{$email}'";
  $result = mysqli_query($connection, $query);
  if (mysqli_num_rows($result) > 0 ) {
    $message = "Account already exists".'<br />'." Please Log in or use different email";
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
  } else {

    insert_into_account($firstname, $lastname, $email, $password);
    $result = get_account($email);
    while ($row = mysqli_fetch_assoc($result)) {
      insert_into_houses($row['account_id'], null, $row['owner_name'],4);
      insert_into_settings($row['account_id']);
      header("Location: home.php");
      $_SESSION['email'] = $email;
      $_SESSION['password'] = $password;
      $_SESSION['house_id'] = get_house_id($email);
      $_SESSION['unit'] = get_unit($_SESSION['house_id']);
      $_SESSION['wait_time'] = get_wait_time($_SESSION['house_id']);
      if(isset($_SESSION['loggedin'])) {
        $_SESSION['loggedin'] = true;
      }
      $_SESSION['room_id'] = $_SESSION['house_id'] . $starting_id_room;
    }
  }
} else {
  $firstname = "";
  $lastname = "";
  $email = "";
  $password = "";
  $message = "Please Sign in";
}


?>

<html>
<?php
include_once('../includes/header.php'); ?>

<body>

  <div class="bg-cover bg-fixed bg-no-repeat bg-center flex" style="background-image: url(../images/loginbg.jpg)">
    <div class="w-screen h-screen flex items-center justify-center">
      <form class="rounded px-8 pt-6 pb-8 mb-4 max-w-lg flex-1" action="signup.php" method="post">
        <div class="flex justify-center text-center mb-2">
          <p class="text-center text-white text-3xl">
            <?php echo $message ?>
          </p>
        </div>
          <div class="mb-4">
            <div class="inputWithIcon">
              <input class="bg-transparent placeholder-white custom border-b border-gray-200 appearance-none w-full py-2 px-3 text-white leading-tight" name="firstname" type="text" placeholder="First Name" value='<?php echo $firstname ?>' />
              <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>
            </div>
          </div>
          <div class="mb-4">
            <div class="inputWithIcon">
              <input class="bg-transparent placeholder-white custom border-b border-gray-200 appearance-none w-full py-2 px-3 text-white leading-tight" name="lastname" type="text" placeholder="Last Name" value='<?php echo $lastname ?>' />
              <i class="fa fa-user fa-lg fa-fw" aria-hidden="true"></i>
            </div>
          </div>
          <div class="mb-4">
            <div class="inputWithIcon text-sm">
              <input class="bg-transparent placeholder-white custom border-b border-gray-200 appearance-none w-full py-2 px-3 text-base text-white leading-tight" name="email" type="text" placeholder="Email" value='' />
              <i class="fa fa-envelope fa-lg fa-w" aria-hidden="true"></i>
            </div>
          </div>
          <div class="mb-6">
            <div class="inputWithIcon">
              <input class="bg-transparent placeholder-white custom border-b border-gray-200 appearance-none w-full py-2 px-3 text-white leading-tight" name="password" type="password" placeholder="Password" value='' />
              <i class="fa fa-lock fa-lg fa-w" aria-hidden="true"></i>
            </div>
          </div>
          <div class="flex justify-center mb-4">
            <input type="submit" name="submit" value="SIGN IN" class="cursor-pointer appearance-none bg-white hover:bg-blue-600 hover:text-white text-blue-500 font-bold py-3 w-full rounded-full" />
          </div>
          <div class="flex justify-center ">
            <a class="text-base text-yellow-400 font-bold hover:underline hover:text-yellow-200" href="login.php">
              Already have an account? Log in
            </a>
          </div>

      </form>
    </div>
  </div>
</body>

</html>

<?php mysqli_close($connection) ?>
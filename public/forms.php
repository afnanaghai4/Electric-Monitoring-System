<?php
if(!isLoggedIn()) 
header('Location: login.php')
?>
if(isset($_POST['submit'])) {
    //form was submitted
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $message = "Done";
    
//perfom queries

} else {
    $firstname = "";
    $lastname = "";
    $email = "";
    $password = "";
    $message = "Please log in";
}


?>

<html>

<head>
  <link href="styles/output.css" type="text/css" rel="stylesheet" />
</head>

<body>
  <?php echo $message; ?>
  <div class="w-screen h-screen flex items-center justify-center bg-gray-200">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 max-w-sm" action="forms.php" method="post">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="firstname">
          First Name
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" name="firstname" type="text" placeholder="First Name" value="" />
      </div> 
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="lastname">
          Last Name
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" name="lastname" type="lastname" placeholder="Last Name" value="" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
          Email
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" name="email" type="email" placeholder="Email" value="" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
          Password
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" name="password" type="password" placeholder="Password" value="" />
      </div>
      <div class="flex items-center justify-between">
        <input type="submit" name="submit" value="Submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" />
        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
          Forgot Password?
        </a>
      </div>
    </form>
  </div>
</body>

</html>

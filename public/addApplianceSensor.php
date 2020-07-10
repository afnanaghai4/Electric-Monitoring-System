<?php
session_start();
include_once('../includes/connection.php');
include_once('../functions/queries.php');

if (!isLoggedIn())
	header('Location: login.php');

if (isset($_GET['room_id'])) {
	$room_id = $_GET['room_id'];
} else {
	$room_id = $_SESSION['room_id'];
	$room_id--;
}

$flag = $_SESSION['flag'];
$link = '';
if ($flag == '1') {
	$link = "found.php";
} else {
	$link = "found_fake.php";
}
//DATA FROM found.php
if (isset($_POST['submit'])) {
	//add the same sensor to rooms
	insert_into_rooms_sensor($room_id, $_POST['id']);

	//add sensor to sensor table
	add_new_sensor($_POST['id'], $_POST['name'], $_POST['type']);
	$count = get_appliance_count($room_id, $_SESSION['house_id']);
	unset($_SESSION['random_ids'][array_search($_POST['id'], $_SESSION['random_ids'])]);
}

?>

<html>
<?php include_once('../includes/header.php'); ?>

<body class="bg-gray-300">
	<?php include('../includes/navbar.php'); ?>
	<div class="bg-gray-300">
		<p class="text-center text-3xl py-4 mb-8 bg-gray-200 shadow-md border-b border-gray-500">Appliance Sensor - # <?php echo $_POST['id'] ?></p>
		<form action="<?php echo $link ?>" method="post">
			<div class="pl-6 pr-6 pb-6">
				<div class="bg-white shadow py-4 rounded-lg">
					<p class="text-center text-xl py-2 mb-4">Found <?php echo $count ?> appliances. Please fill in the Details.</p>
					<?php for ($i = 0; $i < $count; $i++) { ?>
						<div class="flex flex-wrap">
							<div class="flex-grow px-3 mb-6 ">
								<label class="block uppercase text-gray-700 text-xs font-bold mb-2">
									Appliance Name
								</label>
								<input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" required name="<?php echo 'appliance_name' . $i ?>" type="text" placeholder="Appliance Name" value="" />
							</div>
							<div class="flex-2 px-3 mb-6 ">
								<label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
									Rating in kW/h
								</label>
								<input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" required name="<?php echo 'rating' . $i ?>" type="text" placeholder="0" value="" />
							</div>
						</div>
					<?php } ?>

					<div class="mb-4 px-4">
						<label class="block text-gray-700 text-lg font-bold mb-2" for="name">
							Enter Sensor Name
						</label>
						<input class="bg-gray-100 placeholder-gray-400 border border-gray-200 appearance-none w-full py-2 px-3 text-base leading-tight" name="appliance_sensor_name" type="text" placeholder="Sensor Name" value="" />
						<p class="text-gray-500 text-xs italic">For eg: Living Room Sensor</p>
					</div>
					<input type="hidden" name="id" value='<?php echo $_POST['id'] ?>' />
					<input type="hidden" name="page" value='appliance_page' />
					<input type="hidden" name="count" value='<?php echo $count ?>' />
					<div class="mt-4 flex justify-center">
						<input type='submit' name="submit" value="Add Sensor" class="bg-green-500 hover:bg-green-700 text-white font-medium py-2 px-4 rounded" />
					</div>
				</div>
		</form>
	</div>
</body>

</html>

<?php mysqli_close($connection) ?>
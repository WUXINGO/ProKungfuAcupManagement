<?php

if (!isset($_POST['firstName']) || 
!isset($_POST['lastName']) || 
!isset($_POST['email']) || 
!isset($_POST['phone']) ) {
?>

<a href="index.html">Go Back</a>

<?php
	exit("Sorry! You should not be here at this moment!");
}
// Set the include paths
set_include_path('config'
	.PATH_SEPARATOR.'helpers'
	.PATH_SEPARATOR.'libraries'
	// .PATH_SEPARATOR.'/'
	.PATH_SEPARATOR.'amail');


//Include Config files.
require_once('config.php');
//Helper Files
require_once('system_helper.php');
require_once('Database.php');

// echo "===========================";
$fname = trim($_POST['firstName']);
$lname = trim($_POST['lastName']);
$email = trim($_POST['email']);
$phone = clean(trim($_POST['phone']));
$month = $_POST['month'];
$day = $_POST['day'];
$gender = $_POST['gender'];
$birthday = $month."-".$day;
$phone_len = strlen($phone);

// $newline1 = "<br>";

// echo "first name: ".$fname.$newline1;
// echo "last name: ".$lname.$newline1;
// echo "email: ".$email.$newline1;
// echo "phone: ".$phone.$newline1;
// echo "month: ".$month.$newline1;
// echo "day: ".$day.$newline1;
// echo "gender: ".$gender.$newline1;
// echo "birthday: ".$birthday.$newline1;
// echo "the length of phone: ".$phone_len;


// exit("===========================");

$errorMessage = "";
$signmark = "<i class='icon-exclamation'></i>";
$numErrorComplete = 0;
$dbwrerr = 0;
$statusDb; // Use it to hold the return value of $db->execute()

if ($fname == "" || $fname == null || !isset($fname)) {
	$errorMessage = $errorMessage.$signmark." First Name is missing.<br>";
	$numErrorComplete += 1;
} 
if ($lname == "" || $lname == null || !isset($lname)) {
	$errorMessage = $errorMessage.$signmark." Last Name is missing.<br>";
	$numErrorComplete += 1;
} 
if ($email == "" || $email == null || !isset($email)) {
	$errorMessage = $errorMessage.$signmark." Email Address is missing.<br>";
	$numErrorComplete += 1;
} 
if ($phone == "" || $phone == null || !isset($phone) || $phone_len != 10 ) {
	$errorMessage = $errorMessage.$signmark." Phone number is missing or 10 digits of phone is wrong.<br>";
	$numErrorComplete += 1;
} 
if ($month === "0" || $month == null || !isset($month)) {
	$errorMessage = $errorMessage.$signmark." The month of Birth is missing.<br>";
	$numErrorComplete += 1;
} 
if ($day === "0" || $day == null || !isset($day)) {
	$errorMessage = $errorMessage.$signmark." The day of Birth is missing.<br>";
	$numErrorComplete += 1;
} 
if ($gender === "0" || $gender == null || !isset($gender)) {
	$errorMessage = $errorMessage.$signmark." The gender is missing.<br>";
	$numErrorComplete += 1;
}


// Going to check if email and phone are unique in systems.
$db = new Database;
$sql = "SELECT * FROM `customer` WHERE email = '$email' OR phone = '$phone'";
$db->query($sql);
$db->execute();

if ($db->rowCount() > 0) {
	$errorMessage = $errorMessage.$signmark." Email or Phone number is already existed.<br>";
	$numErrorComplete += 1;
}
?>

<!DOCTYPE html> 
<html>
	<head>
		<title>Sign Up - WU XING</title>
		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		
		<link rel="shortcut icon" href="img/headline.ico">
		<link rel="stylesheet" href="css/demo.css">
		<link rel="stylesheet" href="css/sky-forms.css">
		<!--[if lt IE 9]>
			<link rel="stylesheet" href="css/sky-forms-ie8.css">
		<![endif]-->
		
		<!--[if lt IE 10]>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
			<script src="js/jquery.placeholder.min.js"></script>
		<![endif]-->		
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<script src="js/sky-forms-ie8.js"></script>
		<![endif]-->
	</head>
	<body <?php if ($numErrorComplete !== 0): ?>class="bg-red"<?php else: ?> class="bg-green" <?php endif ?> >
		<div  <?php if ($numErrorComplete !== 0): ?>class="body"<?php else: ?> class="body centercenter" <?php endif ?> >
			<div class="sky-form">
				<header style="background: rgba(248, 248, 248, 0.9);">sign up</header>
				<fieldset style="background: rgba(255, 255, 255, 0.9);">		

				<?php if ($numErrorComplete !== 0): ?>			
					<section>
						<div class="error1">
							<em>
								<?php echo $errorMessage; ?>
							</em>
						</div>
					</section>
				<?php else: ?>

					<?php

                	 $sql = "INSERT INTO `customer` (first_name, last_name,  email,  phone,  gender, birthday)
								 VALUES (:fname, :lname, :email, :phone, :gender, :birthday)";

								//Bind Values 
					
                	$db->query($sql);

                	$db->bind(':fname', $fname);
					$db->bind(':lname', $lname);
					$db->bind(':email', $email);
					$db->bind(':phone', $phone);
					$db->bind(':gender', $gender);
					$db->bind(':birthday', $birthday);

                	$statusDb = $db->execute();
                	if ($statusDb) {
                	 ?>
                	 <div class="success1">
	                   
	                      <h5 style="font-weight:800;">Thank you for registering with WU XING.</h5>
	                      <p style="font-weight:800;">Register successfully!</p>
	                   </div>

                   <?php 
                   		 sendEmail($email, $fname, $lname);
                    } else { ?> 

                   		<?php $dbwrerr = 2; ?>
                   		<div class="error1">
							<em>
								<?php echo "There is a <strong>Fatal Error</strong>, Contact with Administration, please!!!"; ?>
							</em>
						</div> 
                    <?php  } ?> 

			<?php endif ?>
					
				
				</fieldset>
					
				<footer>
					<?php if ($numErrorComplete !== 0): ?>
						<button  onclick="goBack();" type="button" class="button">Try Again!!!</button></a>
					<?php else: ?>
						<?php if ($dbwrerr === 2) : ?>
							<a href="signup.html"><button type="button" class="button">Failure. Try again!!!</button></a>
						<?php else: ?>
							<a href="signup.html"><button type="button" class="button">Succeeded and Go Back</button></a>
						<?php endif ?>		
					<?php endif ?>
				</footer>
			</div>

		</div>

		<div class="bottom1">
	        <p>Copyright © 2014 - 2015 by <a href="index.html">WU XING</a></p>
	    </div>

	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="js/bootstrap-switch.js"></script>
		<script src="js/signup.js"></script>
	</body>
</html>

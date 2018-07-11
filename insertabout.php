<?php
include('config.php');
$id = $_SESSION['userid'];

if(!$id)
{
  $url="index.php";
  header("Location:$url");
}

if(isset($_POST['aboutdata']))
{
	$email  = $_POST['email'];
	$bday = $_POST['bday'];
	$profession = $_POST['profession'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	try
	{
		$db= getdb();
		$query = $db->prepare("INSERT into about values(:i,:c,:s,:b,:e,:p)");
		$query->bindParam(':i', $id);
		$query->bindParam(':c' ,$city);
		$query->bindParam(':s' ,$state);
		$query->bindParam(':b' ,$bday);
		$query->bindParam(':e' ,$email);
		$query->bindParam(':p' ,$profession);
		$query->execute();
	}
	catch(Exception $e)
	{

	}
}

if(isset($_POST['picssub']))
{
	$errMsg=null;
	if(!empty($_FILES['select_cover']['name']))
	{
	$file = $_FILES['select_cover'];
	$returnedvalue= photoupload($file);
	$errMsg = $returnedvalue[0];
	$coverpic = $returnedvalue[1];
	}
	else
	{
		$coverpic = "";
	}
	echo $errMsg;

// error is not found then add pic to database

if(!isset($errMsg) && $coverpic !="")
{
	$db = getdb();
	$sql = "UPDATE user set coverpic=:pic where userid=:x";
	$query = $db->prepare($sql);
	$query->bindParam(':pic',$coverpic);
	$query->bindParam(':x',$id);
	$query->execute();
}
	

	$errMsg=null;
	if(!empty($_FILES['select_profile']['name']))
	{
	$file = $_FILES['select_profile'];
	$returnedvalue= photoupload($file);
	$errMsg = $returnedvalue[0];
	$coverpic = $returnedvalue[1];
	}
	else
	{
		$coverpic = "";
	}
	echo $errMsg;

// error is not found then add pic to database

if(!isset($errMsg) && $coverpic!="")
{
	$db = getdb();
	$sql = "UPDATE user set profilepic=:pic where userid=:x";
	$query = $db->prepare($sql);
	$query->bindParam(':pic',$coverpic);
	$query->bindParam(':x',$id);
	$query->execute();
}
}

try
{
	$db = getdb();
	$query = $db->prepare("SELECT * from about where userid=:i");
	$query->bindParam(':i',$id);
	$query->execute();
	if($query->rowCount())
	{
		$url="feed.php";
		header("Location:$url");
	}

	$query = $db->prepare("SELECT * from user where userid=:i");
	$query->bindParam(":i",$id);
	$query->execute();
	$data = $query->fetch();
}
catch(Exception $e)
{
	echo "We are sorry for trouble , please refresh page";
}

function photoupload($file)
{
	$errMSG=NULL;
	$tmp_dir = $file['tmp_name'];
  	$imgSize = $file['size'];
  	$imgFile = $file['name'];
  	$upload_dir = 'public/'; // upload directory
 
   	$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
  
   	// valid image extensions
   	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
  
   	// rename uploading image
   	$userpic = rand(1000,1000000).".".$imgExt;
    
   	// allow valid image file formats
   	if(in_array($imgExt, $valid_extensions))
   	{   
    // Check file size '5MB'
    	if($imgSize < 5000000)
    	{
    	move_uploaded_file($tmp_dir,$upload_dir.$userpic);
    	
    	}
    	else
    	{
    		$errMSG = "Sorry, your file is too large.";
    	}
	}
	else
	{
    	$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";  
	}
	return array($errMSG,$userpic);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Enlink</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="public/css/profilestyle.css">
    <link rel="javascript" type="text/javascript" href="public/js/script.js">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="header">
    <img src="public/logo.png" class="" width="120px" height="40px">
    <div style="float: right;">
        <img src="public/<?php echo $data['profilepic']?>" width="40px" height="40px" style="border-radius: 18px">
        <div class="usernametop">   
            <?php
                echo $data['username'];
            ?>
        </div>
    </div>
</div>
<h2 class="boxtitle">Insert Your Profile Details</h2>
<div class="box">
	<div class="banner">
		<img src="public/<?php echo $data['coverpic'];?>" id="showcover" style="border-radius: 8px" width="100%" height="100%">
	</div>
	<div style="position: absolute;top:145px;left:30px">
		<img src="public/<?php echo $data['profilepic']?>" id="showprofile" width="80px" height="80px" style="border: 5px solid white;border-radius: 45px">
	</div>
	<form name="pics" method="post" action="insertabout.php" enctype="multipart/form-data" class="picsform" style="margin:50px 20px">
		<label>Profile photo</label>
		<input type="file" name="select_profile" id="selct_profile" accept="image/*" onchange="showImage(this,'showprofile');">
		<br>
		<label>Cover photo</label>
		<input type="file" name="select_cover" id="select_cover" accept="image/*" onchange="showImage(this,'showcover');">
		<br>
		<input type="Submit" name="picssub" style="width:100px;color: white;background-color: green;border:1px solid white;line-height: 1.7em;border-radius: 5px;float: right;margin-right: 50px;vertical-align: middle" value="upload">
	</form>
</div>


<div class="box">
<h3 class="boxtitle">Details about you</h3>
<form name="about" method ="post" action="insertabout.php" class="insertabout" style="margin:50px 20px">
	<label>Birthdate</label>
	<input type="date" name="bday" required>
	<br>
	<label>Profession</label>
	<input type="text" name="profession" placeholder="Student ,Designer etc..." required>
	<br>
	<label>Email</label>
	<input type="email" name="email" placeholder="Enter your email " required>
	<br>
	<label>City</label>
	<input type="text" name="city" placeholder="Enter your city" required>
	<br>
	<label>State</label>
	<input type="text" name="state" placeholder="Enter your state" required>
	<br>
	<input type="Submit" name="aboutdata" style="width:100px;color: white;background-color: green;border:1px solid white;line-height: 1.7em;border-radius: 5px;float: right;margin-right: 50px;vertical-align: middle" value="OK">
</form>
</div>
<script type="text/javascript">
	function showImage(element,target)
	{
		var tar = document.getElementById(target);

		var fr = new FileReader();
		fr.onload = function(){
			tar.src = fr.result;
		}
		fr.readAsDataURL(element.files[0]);
	}
</script>
</body>
</html>
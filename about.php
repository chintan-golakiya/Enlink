<?php
include('config.php');
$id = $_SESSION['userid'];
$err = null;
if(!$id)
{
	$url="index.php";
	header("Location:$url");
}
try{
if(isset($_POST['update']))
{
	$user = $_POST['username'];
	$first = $_POST['firstname'];
	$last = $_POST['lastname'];
	$email  = $_POST['email'];
	$bday = $_POST['bday'];
	$profession = $_POST['profession'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$oldpass = $_POST['oldpass'];
	$newpass = $_POST['newpass'];
	$conpass = $_POST['conpass'];
	if($oldpass=="")
	{
		$changepassword = false;
	}
	else
	{
		$changepassword = true;
	}
	try
	{
		$db = getdb();
		$smt = $db->prepare("SELECT username from user where username=:z and userid !=:i");
		$smt->bindParam(":z",$user);
		$smt->bindParam(':i',$id);
		$smt->execute();
		$count = $smt->rowCount();
		if($count)
		{
            $err="This username already exists....";
		}
	}
	catch(Exception $e)
	{
		echo "We are sorry for trouble , please refresh page";
	}

	if(!$err)
	{
		try
		{
		$db = getdb();
		$smt = $db->prepare("UPDATE user set username = :u,firstname = :f , lastname = :l where userid = :i");
		$smt->bindParam(':u',$user);
		$smt->bindParam(':f',$first);
		$smt->bindParam(':l',$last);
		$smt->bindParam(':i',$id);
		$smt->execute();

		$smt= null;
		$smt = $db->prepare("UPDATE about set email=:e,birthdate=:b,city=:c,state=:s,profession=:p where userid=:i ");
		$smt->bindParam(':i',$id);
		$smt->bindParam(':e',$email);
		$smt->bindParam(':b',$bday);
		$smt->bindParam(':c',$city);
		$smt->bindParam(':s',$state);
		$smt->bindParam(':p',$profession);
		$smt->execute();
		}
		catch(Exception $e)
		{
			echo "We are sorry for trouble , please refresh page";
		}
	}
	if($changepassword)
	{
	try
	{
		$db = getdb();
		$smt = $db->prepare("SELECT password from user where userid=:z");
		$smt->bindParam(":z",$id);
		$smt->execute();
		$row = $smt->fetch();

		if($row['password'] != $oldpass || $newpass!= $conpass)
		{
            $err="Your password doesn't matched";
		}
		else
		{
			$db = getdb();
			$smt = $db->prepare("UPDATE user set password=:w where userid=:i");
			$smt->bindParam(':i',$id);
			$smt->bindParam(':w',$newpass);
			$smt->execute();
		}
	}
	catch(Exception $e)
	{
		echo "We are sorry for trouble , please refresh page";
	}
	}
	
}
}
catch(Exception $e)
{
	echo $e->getMessage();
}
try
	{
		$db =getdb();
		$smt = $db->prepare("SELECT * FROM user WHERE userid = :x");
		$smt->bindParam(":x",$id);
		$smt->execute();
		$data = $smt->fetch(PDO::FETCH_BOTH);
		// now $data have all details of user

		$db =getdb();
		$smt = $db->prepare("SELECT * FROM about WHERE userid = :x");
		$smt->bindParam(":x",$id);
		$smt->execute();
		$about = $smt->fetch(PDO::FETCH_BOTH);


		
	}
	catch(PDOException $e)
	{
		echo "We are sorry for trouble , please refresh page";
	}
?>

<html>
<head>
	<title>Enlink</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="public/css/profilestyle.css">
	<link rel="javascript" type="text/javascript" href="public/js/script.js">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style type="text/css">
		.postpart1
        {
            width:100%;
            padding-right: 17px;
            height:100%;
            overflow-y: hidden;
            overflow-x: hidden;
            box-sizing: content-box;
        }
        .postpart2
        {
            width:100%;
            padding-right: 17px;
            height:100%;
            overflow-y: scroll;
            overflow-x: hidden;
            box-sizing: content-box;
        }
	</style>
</head>
<body>
	<div class="header" id="header">
		<img src="public/logo.png" class="" width="120px" height="40px">
		<div style="float: right;">
			<img src="public/<?php echo $data['profilepic']?>" width="40px" height="40px" style="border-radius: 18px;">
			<div class="usernametop">	
			<?php
				echo $data['username'];
			?>
			</div>
		</div>
	</div>

	<div class="banner">
	<ul class="menu" id="menu">
	<li class="tab active">about</li>
	<a href="feed.php"><li class="tab">News Feed</li></a>
	<a href="Profile.php"><li class="tab">Profile</li></a>
	<a href=""><li class="tab">Friends</li></a>
	<a href="peoplesearch.php"><li class="tab">Search</li></a>
	<a href="Logout.php"><li class="tab">Log out</li></a>
	</ul>
	<img src="public/<?php echo $data['coverpic']?>" class="bannerimg">
	</div>

<div class="main_content" style="height: 620px" id="main">

<div class="sidebar" style="overflow: hidden;">
<div class="postpart2" id="sidescroll">
    <div class="followsuggestion">Suggestion to follow</div>
    <?php

$r=$_SESSION['userid'];
try{    
$db=getdb();
$stts=$db->prepare("SELECT * FROM user WHERE userid!='$r' and userid not in (SELECT userid2 FROM friends WHERE userid1='$r') order by userid desc");
$stts->execute();


}
catch(Exception $e)
{
    echo $e;
}

while($row=$stts->fetch(PDO::FETCH_BOTH))
{

    ?>

    <div class="postheader" style="margin:15px 10px">
            <img src="public/<?php echo $row['profilepic']?>" width="40px" height="40px" style="border-radius: 18px">
            <div class="postuserdetail" style="color: green;font-size: 1em;">    
            <?php
                echo $row['username'];
            ?><br>
            <div class="followbtn" id="follow<?php echo $row['userid']?>" onclick="follow(this);">Follow</div>
            </div>
            <hr style="border-top:1px solid lightgreen;">
        </div>
<?php
}


?>
</div>
</div>
<div class="postpart" style="overflow:hidden">
<div class="postpart1" id="postscroll">
	<div class="subheading"> Edit Your Details... </div>

	<form method="post" action="about.php" name="aboutform" class="aboutform">
		<fieldset>
		<label>Username</label>
		<input type="text" name="username" value="<?php echo $data['username']?>" required>
		<br>
		<label>Firstname</label>
		<input type="text" name="firstname" value="<?php echo $data['firstname']?>" required>
		<br>
		<label>Lastname</label>
		<input type="text" name="lastname" value="<?php echo $data['lastname']?>" required>
		<br>
		<label>Email</label>
		<input type="email" name="email" value="<?php echo $about['email']?>" required>
		</fieldset>
		<fieldset>
		<label>Birthdate</label>
		<input type="date" name="bday" value="<?php echo $about['birthdate']?>" required>
		<br>
		<label>Profession</label>
		<input type="text" name="profession" value="<?php echo $about['profession']?>" placeholder="Enter your profession" required>
		<br>
		</fieldset>
		<fieldset>
		<label>City</label>
		<input type="text" name="city" value="<?php echo $about['city']?>" required>
		<br>
		<label>State</label>
		<input type="text" name="state" value="<?php echo $about['state']?>" required>
		</fieldset>
		<fieldset>
		<legend>Change password</legend>
		<label>Old Password</label>
		<input type="password" name="oldpass" placeholder="Enter your current password..">
		<br>
		<label>New Password</label>
		<input type="password" name="newpass" placeholder="Enter new password...">
		<br>
		<label>Confirm Password</label>
		<input type="password" name="conpass" placeholder="Enter password again...">
		</fieldset>
		<div class="buttons">
		<input type="Submit" name="update" value="Update" style="color: white;background-color: green;border: solid #ccc;line-height: 1.5em;border-radius: 5px;" onclick="return validate()">
		<input type="button" name="cancel" value="Cancel" onclick="can();">
		</div>
	</form>
</div>
</div>
</div>
<?php
if($err)
	{
		?>
		<script type="text/javascript">
			window.alert("<?php echo $err ?>");
		</script>
		<?php
	}
	?>
<script type="text/javascript">

	window.onscroll = function()
	{
    if(window.pageYOffset>=206)
    {
        document.getElementById('menu').classList.remove('menu');
        document.getElementById('menu').classList.add('menustick');
        document.getElementById('postscroll').classList.add('postpart2');
        document.getElementById('postscroll').classList.remove('postpart1');
    }
    else
    {
        document.getElementById('menu').classList.add('menu');
        document.getElementById('menu').classList.remove('menustick');
        document.getElementById('postscroll').classList.add('postpart1');
        document.getElementById('postscroll').classList.remove('postpart2');
        document.getElementById('postscroll').scrollTo(0,0);

    }
	}
	function can()
	{
		location.href="Profile.php";
	}

	function validate()
	{
		var opass = document.forms['aboutform']['oldpass'];
		var npass = document.forms['aboutform']['newpass'];
		var cpass = document.forms['aboutform']['conpass'];
		

		if(opass.value!="")
		{
			if(npass.value=="")
			{
				window.alert('Enter new password and confirm password to Change password');
				npass.focus();
				return false;
			}
			else
			{
				if(npass.value!=cpass.value)
				{
					window.alert("Confirm password doesn't matched to new passowrd");
					cpass.focus();
					return false;
				}
			}
		}
		else
		{
			npass.value="";
			cpass.value="";
		}
		return true;
	}
</script>
</body>
</html>
<?php
include('config.php');
include('authority.php');
if(!empty($_SESSION['userid']))
{
	try{
		$url="feed.php";
		header("Location:$url");
	}
	catch(PDOException $e)
	{
		$_SESSION['userid']=null;
	}
}

$authority = new authClass();

  $errMsgLogin='';

  if(!empty($_POST['Login'])){
    $fuserid=$_POST['username'];
    $fpass=$_POST['password'];

    if((strlen(trim($fuserid))>1)&&(strlen(trim($fpass))>1)){
      $uid = $authority->authLogin($fuserid,$fpass);
      if($uid!=0){

        $_SESSION['userid']=$uid;

        $url = "insertabout.php";
        header("Location:$url");
      }
      else{
        $errMsgLogin="Enter valid details... ";
      }
    }
  } 
?>
<html>
<head>
	<title>Enlink</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="public/css/style.css">
	<link rel="javascript" type="text/script" href="public/js/script.js">
</head>
<body class="log-sign">
	<img src="public/logo.png" class="logo">
	<div class="tab"></div>
	<div class="main">
		<fieldset >
				<form method="post" action="" name="authlogin">
					<h2 class="formtitle">Login</h2>
					<span style="color:white"><?php echo $errMsgLogin?></span>
					<br>
					<input type="text" name="username" placeholder="Username" required>
					<br>
					<input type="password" name="password" placeholder="Password" required>
					<br>
					<input class="button" type="submit" name="Login" value="Login" style="color: white">
					<h3 style="text-align: center;">
  						<a href="signup.php" style="text-decoration: none;color:black;" >Not a Member? Sign up</a>
  					</h3>
				</form>
				</fieldset>
	</div>
</body>
</html>

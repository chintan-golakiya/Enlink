<?php
include('config.php');
include('authority.php');
try{
$_SESSION['userid']=null;

if(empty($_SESSION['userid']))
{
	session_destroy();
	$url="index.php";
	header("Location:$url");
}
else
{
	$url="feed.php";
	header("Location:$url");
}
}
catch(PDOException $e)
{
	echo "WE are sorry but something went wrong";
}
?>
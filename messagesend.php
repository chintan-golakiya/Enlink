<?php
include('config.php');
$id = $_SESSION['userid'];
if(!$id)
{
	$url="index.php";
	header("Location:$url");
}
if(isset($_GET['userid']) && isset($_GET['message']))
{
try
{
	$db = getdb();
	$query=$db->prepare("INSERT into chat(userid,senderid,message) values (:u,:s,:m)");
	$query->bindParam(':u',$_GET['userid']);
	$query->bindParam(':s',$id);
	$query->bindParam(':m',$_GET['message']);
	$query->execute();
}
catch(Exception $e)
{
	echo "error";
}
}
?>
<?php

include('config.php');
$id = $_SESSION['userid'];
if(!$id)
{
	$url="index.php";
	header("Location:$url");
}
$postid = $_GET['i'];
try{
$db = getdb();
$sql = "INSERT into likes(postid,userid) values ('$postid','$id')";
$query = $db->prepare($sql);

$query->execute();

$query = $db->prepare("SELECT count(userid) from likes where postid=:pi");
$query->bindParam(':pi',$postid);
$query->execute();
$ans = $query->fetch();

echo $ans[0];
}
catch(Exception $e)
{

}

?>
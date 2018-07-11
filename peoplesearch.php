<?php
	include('config.php');
include('authority.php');
	$id = $_SESSION['userid'];
$_SESSION['searchby']="firstname";
if($id!=null)
{
	
	}
else
{
	$url="index.php";
	header("Location:$url");
}
$searchby="name";
try
    {
        $db =getdb();
        $smt = $db->prepare("SELECT * FROM user WHERE userid = :x");
        $smt->bindParam(":x",$id);
        $smt->execute();
        $data = $smt->fetch(PDO::FETCH_BOTH);
        // now $data have all details of user
    }
    catch(PDOException $e)
    {
        echo "We are sorry for trouble , please refresh page";
    }

?>
<!DOCTYPE html>
<html>
<head>
	<title>searchlist</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="public/css/profilestyle.css">
    <link rel="javascript" type="text/javascript" href="public/js/script.js">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
        .postpart1
        {
            width:98.6%;
            height:100%;
            padding-right: 17px;
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
        .sa{
		display:inline-block;
		padding:5px 7px;
		background: rgba(0,0,0,0);
		margin:0px 0px;
		font-size: 1.3em;
		border:0px;
		outline: none;
		color:green;

	}
	.ap{
		display:inline-block;
		padding:5px 7px;
		background: rgba(0,0,0,0);
		margin:0px 0px;
		font-size: 1.3em;
		border:0px;
		outline: none;
		color:black;


    </style>
    <script type="text/javascript">
		var a=<?php echo '"'.$_SESSION['searchby'].'"'; ?>;
		function clk(element) {
			console.log(a);
			var cur1=element.id;
				if(cur1!=a)
				{
					var xhr=new XMLHttpRequest();
					var $url='search.php?i='+cur1;
                    xhr.open('GET',$url,true);
                    xhr.onreadystatechange=function()
                    {
                    	if(xhr.readyState==4 && xhr.status==200)
                    	{

                               document.getElementById(a).classList.remove('ap');
                               document.getElementById(a).classList.add('sa');
                               element.classList.remove('sa');
                               element.classList.add('ap');
                               document.getElementById('in').placeholder="Enter your friends "+xhr.responseText;
                               a=xhr.responseText;
                    	}

                    }
                    xhr.send();
				}
				}
	</script>
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
    <div class="banner">
    <ul class="menu" id="menu">
    	<a href="peoplesearch.php"><li class="tab active">Search</li></a>

    <a href="showfriends.php"><li class="tab">Friends</li></a>
    
    <a href="Profile.php"><li class="tab">Profile</li></a>
    <a href="feed.php"><li class="tab">Newsfeed</li></a>
    <a href="Logout.php"><li class="tab">Log out</li></a>
    </ul>
    <img src="public/samplebanner3.jpeg" class="bannerimg">
    </div>

<div class="main_content" id="main">
	<div style="height:80%;width:18%;margin-right: 2%">
		<h2 style="text-align: center;">
			Active Users..!
		</h2>
	</div>
<div style="height:80%;width:60%;margin-left:0px;margin-top: 5%;">
		<div style="background-size:100% 100%;height:50%;width:60%;background-image:url('peoplesearch.jpg');border-radius: 5px;border:3px solid black;background:cover;padding-top:8%;padding-right: 11%;padding-left: 28%;padding-bottom: 8%;">
			<ul style="display: block;margin- bottom:10px;width:80%;position:relative;left:-30px; list-style-type: none;">
					<li style="display:inline-block;"><button class="ap" id="firstname" onclick="clk(this);">
						Name
					</button></li>
					<li style="display:inline-block;"><button class="sa" id="username" onclick="clk(this);">
						Username
					</button></li>
				</ul>
				<form method="post" action="searchlist.php" name="ss">
			<input id="in" style="width:70%;z-index:1;border-radius: 50px;padding-left:10px;border:2px solid black;outline: none;line-height: 2.3em;" name="psearch" placeholder="Enter your friends <?php echo $_SESSION['searchby']; ?>..." type="text"required/>
			<button  style="text-align: center;border-radius: 50px;border:2px solid black;z-index: 3;outline: none;position: relative;left:-15%;line-height: 2.3em;background-color: green;">Search</button>
		</div></div>

<div class="sidebar" style="overflow: hidden;margin-left: 2%">
<div class="postpart2" id="sidescroll">
    <div class="followsuggestion">Suggestion to follow</div>
    <?php

$r=$_SESSION['userid'];
try{    
$db=getdb();
$stts=$db->prepare("SELECT * FROM user WHERE userid!='$r' and userid not in (SELECT userid2 FROM friends WHERE userid1='$r') order by joindate DESC");
$stts->execute();
}
catch(Exception $e)
{
    echo $e;
}
while($row=$stts->fetch(PDO::FETCH_BOTH))
{
    ?>
    <div class="postheader" style="margin:15px 0px">
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

</div>
</body>
</html>
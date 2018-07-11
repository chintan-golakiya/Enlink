<?php 
include('config.php');
$id = $_SESSION['userid'];
if(!isset($_SESSION['ds'])) $_SESSION['ds']=0;
if($id!=null)
{
	
	}
else
{
	$url="index.php";
	header("Location:$url");
}
try{
$db=getdb();
if($_SESSION['ds']==0)
{// following
$sf=$db->prepare("SELECT distinct u.username,u.profilepic,u.coverpic FROM friends as f,user as u WHERE u.userid IN (SELECT userid2 FROM friends WHERE userid1=:u1)");
}
else
{//followers
    $sf=$db->prepare("SELECT distinct u.username,u.profilepic,u.coverpic FROM friends as f,user as u WHERE u.userid IN (SELECT userid1 FROM friends WHERE userid2=:u1)");

}
$sf->BindParam(':u1',$id);
$sf->execute();

}
catch(PDOException $e)
{
	echo "data not fetched..";
}
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
	<title>showfriends<?php echo $_SESSION['ds'];?></title>
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
        .lio{
            color:green;width:38%;text-align: center;background-color:white;border-radius: 30px;border:2px solid green;margin-right: 2%;padding: 1px 1px;
        cursor:pointer;
    line-height: 2.5em;
    font-size: 1.5em;
    font-family: verdana;
    display: inline-block;
        }

        .liochange
        {
            color:white;width:38%;text-align: center;background-color:green;border-radius: 30px;border:2px solid green;margin-right: 2%;padding: 1px 1px;
        cursor:pointer;line-height: 2.5em;
    font-size: 1.5em;
    font-family: verdana;
    display: inline-block;
        }
    </style>
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
    <a href="showfriends.php"><li class="tab active">Friends</li></a>
    <a href="peoplesearch.php"><li class="tab">Search</li></a>
    <a href="Profile.php"><li class="tab">Profile</li></a>
    <a href="feed.php"><li class="tab">Newsfeed</li></a>
    <a href="Logout.php"><li class="tab">Log out</li></a>
    </ul>
    <img src="public/samplebanner3.jpeg" class="bannerimg">
    </div>

<div class="main_content" style="height: 620px" id="main">
	<div style="height:80%;width:18%;margin-right: 2%">
		<h2 style="text-align: center;">
			Active Users..!
		</h2>
	</div>
<div style="overflow:hidden;height: 100%;width:60%">
<div class="postpart1" id="postscroll">
		<div style="width:100%;height:20%;display:block;">
	<h1 style="text-align:center;"><?php echo "Here your frinds " ?></h1>
    <div style="width: 100%;">
    <ul>
        <li class="lio" id="following" onclick="ck(this)">
            Following</li>
            <li class="lio" style="margin-left:12%; " id="followers" onclick="ck(this)">
            Followers</li>
        </ul>
    </div>
</div>
<?php while($sfdata=$sf->fetch(PDO::FETCH_BOTH))
{
	?>
	    <div style="margin:1% 2%;width:45%;display: inline-block;">
		    <div style="margin-top:15px;margin-right: 15px;margin-left:15px;">
                <div style="margin-bottom: 15px;">
	                <img src="public/<?php echo $sfdata['coverpic']; ?>" alt="hh" style="height:120px;width:100%;display: block;border-radius: 5px;">
	            </div>
	            <div style="margin:0px 25px;position: relative;">
		            <img src="public/<?php echo $sfdata['profilepic']; ?>" alt="l" style="height:60px;width:60px;border-radius: 45px;border:5px solid white;position: absolute;top:-55px;">
		            <hr style="margin :0px 0px;color: white;border-style: solid;">
		            <div style="margin:15px 5px;"> 
		                <div style="display: inline-block;"><font style="color:blue;"><?php echo $sfdata['username']; ?></font>
		                </div>
		                <div style="float: right;align-content: right"><font style="color:green;">My friend</font>
		                </div>
	                </div>

	            </div>
                <hr>
            </div>
        </div>
<?php }
?>
</div>
</div>
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
<script type="text/javascript">

    if(<?php echo $_SESSION['ds'];?>==0)
    {
        document.getElementById('following').classList.remove('lio');
        document.getElementById('following').classList.add('liochange');
    }
    else
    {
        document.getElementById('followers').classList.remove('lio');
        document.getElementById('followers').classList.add('liochange');
    }

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
function follow(element)
    {
        console.log('running');
        var cur = element.id;
        if(element.innerHTML=='Follow')
        {
            console.log('running');
            var xfr = new XMLHttpRequest();
            var fid = cur.substring(cur.indexOf('w')+1);
            var $url = 'follow.php?id='+fid;

            xfr.open('GET',$url,true);

            xfr.onreadystatechange = function()
            {
                if(xfr.readyState==4 && xfr.status==200)
                {
                    element.innerHTML = 'Following';
                    element.style.backgroundColor = '#cb6318';
                    element.style.color='white';
                }
            }
            xfr.send();
        }
    }
    function ck(element) {
     var i=element.id;
     console.log("run");
     var xhr=new XMLHttpRequest();
                    var $url='search1.php?i='+i;
                    xhr.open('GET',$url,true);
                    xhr.onreadystatechange=function()
                    {
                        if(xhr.readyState==4 && xhr.status==200)
                        {
                               a=xhr.responseText;
                               console.log(a);
                                location.reload();
                        }
                    }

                    xhr.send();

    }
</script>
</body>
</html>

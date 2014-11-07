<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
	<link href="css/lrm.css" rel="stylesheet" type="text/css">
	
	<title>LiveRamp Music</title>
	
	<script src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/youtube.js"></script>
	<script>
		<?php
			
			if(isset($_GET["hashtag"]))
			{
				echo("var hashtag = '" . $_GET["hashtag"] . "'; ");
				$hashtag = $_GET["hashtag"];
			}
			else
			{
				echo("var hashtag = 'np'; ");
				$hashtag = "np";
			}
		?>
		
	</script>
	<script src="js/lrm.js"></script>
</head>

<body>

<div id="upNext" class="topBar">

	<a href="javascript:emptyIt()" style="float: right;">
		<img src="images/icon_x.png" width='20'>
	</a>

	<a href="javascript:nextSong()" style="float: right;">
	<div id="linker">
	<table>
		<tr>
			<td>
				<div id="leftStuff">
					<img src='images/loading.gif' style='width=50px;' width='50'>
				</div>
			</td>
			<td>
				<div  id="rightStuff">
				
				</div>
			</td>
		</tr>
	</table>
	</div>
	</a>
	
	<div class="fright">
		<p style="color: white;">Coming up next >>&nbsp;&nbsp;</p>
	</div>
	
</div>

<div id="wrapper">

	<div id="container">
		
		<div id="header">
			<img src="images/logo_new.png" width="250">
			<p>Listen to what others are listening to right now.</p>
		</div>
		
		<form action="index.php" method="get">
		<input type="radio" name="hashtag" value="np" id="radio-np">#np &nbsp;&nbsp;</input>
		<input type="radio" name="hashtag" value="nowplaying" id="radio-nowplaying">#nowplaying &nbsp;&nbsp;</input>
		<input type="submit" value="Change" />
		</form>
		
		<script>
			$('#radio-' + hashtag).click();
		</script>
		
		<div id="tweetarea">
			<blockquote class="twitter-tweet" data-cards="hidden"><img src="images/loading.gif" style="width=50px;" width="50"><a href="https://twitter.com/twitterapi/status/<?php echo $twitterId; ?>" data-datetime="2011-11-07T20:21:07+00:00"></a></blockquote>
		</div>

		<div id="player"></div>
		
		<div id="bottomSpacer">&nbsp;</div>
	</div>
</div>

</body>
</html>
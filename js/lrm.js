var xmlhttp;
var lastTweet = "0";
var songs = [];

loadXMLDoc(lastTweet, true);
//loadXMLDoc(lastTweet, false);

function loadXMLDoc(id, firstSong)
{
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			//var response = xmlhttp.responseText;
			var response = eval ("(" + xmlhttp.responseText + ")");
			if(firstSong)
			{
				loadFirstSong(response);
			}
			else
			{
				nextUp(response);
			}
		}
	}
	
	xmlhttp.open("GET","fetchData.php?lastTweet=" + id + "&hashtag=" + hashtag + "&firstVideo=" + firstSong,true);
	xmlhttp.send();
}

function loadFirstSong(response)
{
	newEntry = new Object();
	newEntry.url = response.url;
	newEntry.title = "LiveRamp Music | " + response.title;
	newEntry.tweet = response.twitterId;
	songs.unshift(newEntry);
	
	nextSong();
}

function nextUp(response)
{	
	document.getElementById("leftStuff").innerHTML="<img src='" + response.thumbnail + "' width='50' height='50'>";
	document.getElementById("rightStuff").innerHTML="<p>" + response.title + "</p>";
							
	newEntry = new Object();
	newEntry.url = response.url;
	newEntry.title = "LiveRamp Music | " + response.title;
	newEntry.tweet = response.twitterId;
	songs.unshift(newEntry);
}

function emptyIt()
{

	document.getElementById("leftStuff").innerHTML="<img src='images/loading.gif' style='width=50px;' width='50'>";
	document.getElementById("rightStuff").innerHTML="";
	
	loadXMLDoc(lastTweet, false);
}

function nextSong()
{
	document.title = songs[0].title;
	
	document.getElementById("tweetarea").innerHTML = '<blockquote class="twitter-tweet" data-cards="hidden"><p><img src="images/loading.gif" style="width=50px;" width="50"><a href="https://twitter.com/twitterapi/status/' + songs[0].tweet + '" data-datetime="2011-11-07T20:21:07+00:00"></a></blockquote>';
	twttr.widgets.load();

	
	lastTweet = songs[0].tweet;

	loadVideo(songs[0].url);
	
	
	emptyIt();
}



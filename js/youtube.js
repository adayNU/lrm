// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');
tag.src = '//www.youtube.com/iframe_api';
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;

function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
		height: '320',
		width: '500',
		videoId: '',
		playerVars: { 
			'rel': 0,
			'iv_load_policy': 3 
		}, 
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		}
	});
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
	if(player.getVideoUrl() === "https://www.youtube.com/watch" && (typeof songs === "object"))
	{
		//document.getElementById("header").innerHTML="Used fallback method.";

		loadVideo(songs[0].url);

	}
	event.target.playVideo();
}

var done = false;
function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.ENDED) {
		nextSong();
		emptyIt();
	}
}
function stopVideo() {
	player.stopVideo();
}

function loadVideo(videoID) {
	try{
		player.loadVideoById(videoID);
	}
	catch(err)
	{
		
	}
}
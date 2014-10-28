<?php
error_reporting(0);

$url = "";
$twitterId = "";
$thumbnail = "";

$lastTweet = $_GET["lastTweet"];
$hashtag = $_GET["hashtag"];
$firstVideo = $_GET["firstVideo"];

while($url == "")
{

	require_once('TwitterAPIExchange.php');

	$settings = array(
		'oauth_access_token' => "abcd",
		'oauth_access_token_secret' => "efgh",
		'consumer_key' => "1234",
		'consumer_secret' => "5678"
	);

	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	
	if($firstVideo)
	{
		$getfield = '?q=%23' . $hashtag . '&count=2&result_type=recent&lang=en&since_id='.$lastTweet;
	}
	else
	{
		$getfield = '?q=%23' . $hashtag . '&count=1&result_type=recent&lang=en&since_id='.$lastTweet;
	}
	
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(true);

	

	//$json = file_get_contents("http://search.twitter.com/search.json?q=%23".$hashtag."&src=hash&rpp=1&result_type=recent&lang=en&since_id=".$lastTweet, true);
	$twitter = json_decode($response, true);
	
	//$twitterId = $twitter['max_id_str'];
	if($firstVideo)
	{
		$twitterId = $twitter["statuses"][1]["id_str"];
		$lastTweet = $twitterId;
		$searchString = $twitter["statuses"][1]["text"];
	}
	else
	{
		$twitterId = $twitter["statuses"][0]["id_str"];
		$lastTweet = $twitterId;
		$searchString = $twitter["statuses"][0]["text"];
	}
	

	//echo($searchString."<br>");

	$searchString = " ".$searchString." ";
	$searchString = strtoupper($searchString);
	$searchString = str_replace("#NOWPLAYING", " ", $searchString);
	$searchString = str_replace("#NP ", " ", $searchString);
	$searchString = str_replace(" RT ", " ", $searchString);
	$searchString = preg_replace("/HTTP[^ ]* /", " ", $searchString);
	$searchString = str_replace(" '", " ", $searchString);
	$searchString = str_replace("' ", " ", $searchString);
	$searchString = str_replace('"', " ", $searchString);
	$searchString = preg_replace("/@(.*?): /", " ", $searchString);
	$searchString = preg_replace("/[^A-Za-z0-9 ]/", ' ', $searchString);
	$searchString = trim($searchString);
	$searchString = preg_replace('/\s\s+/', ' ', $searchString);

	$searchString = str_replace(" ","+", $searchString);
	
	//echo($searchString."<br>");
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://gdata.youtube.com/feeds/api/videos?q=".$searchString."&max-results=1&alt=json&category=Music");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$json1 = curl_exec($ch);
	curl_close($ch);

	$youtube = json_decode($json1, true);

	$url = str_replace("https://www.youtube.com/watch?v=", "", $youtube["feed"]["entry"][0]["link"][0]["href"]);
	$url = str_replace("&feature=youtube_gdata", "", $url);
	$thumbnail = $youtube["feed"]["entry"][0]["media\$group"]["media\$thumbnail"][1]["url"];
	//$thumbnail = preg_replace("/\/", "", $thumbnail);
	$title = $youtube["feed"]["entry"][0]["title"]["\$t"];
	
}


$array = array(
    "twitterId" => $twitterId,
    "url" => $url,
	"thumbnail" => $thumbnail,
	"title" => $title,
);

$json = json_encode($array);

echo($json);
?>
<?php
error_reporting(0);

require_once('TwitterAPIExchange.php');

$url = "";
$twitterId = "";
$thumbnail = "";
$searchString = "";

$lastTweet = $_GET["lastTweet"];
$hashtag = $_GET["hashtag"];
$firstVideo = $_GET["firstVideo"];

while($url == "" || is_null($twitterId))
{

	$settings = array(
		'oauth_access_token' => "891657050-gW4PbXZyIjtOClmDPVw09TwZd6G9NMKzJfL9JSAX",
		'oauth_access_token_secret' => "cGxNas0vS4ICt83cNQXvQRPh912KuN25tlrfSb3Tlg",
		'consumer_key' => "BQSa2jmCzqMfX2Fjdcyw",
		'consumer_secret' => "AgqcEQUS0Cpsk8cC1NPJC6b7My34Pnq1iBbJ9lHUPA"
	);

	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	
	if($firstVideo)
	{
		$getfield = '?q=%23' . $hashtag . '+-soundcloud&count=2&result_type=recent&lang=en&since_id='.$lastTweet;
	}
	else
	{
		$getfield = '?q=%23' . $hashtag . '+-soundcloud&count=1&result_type=recent&lang=en&since_id='.$lastTweet;
	}
	
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(true);

	$twitter = json_decode($response, true);
	
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

	$searchString = " ".$searchString." ";
	$searchString = strtoupper($searchString);
	$searchString = str_replace("#NOWPLAYING", " ", $searchString);
	$searchString = str_replace("#NP", " ", $searchString);
	$searchString = str_replace("#SOUNDCLOUD", " ", $searchString);
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
	
	$DEVELOPER_KEY = 'AIzaSyCd2GZ2L4eqJiyTJA99dhoJo8XGfw2PxBU';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=id%2Csnippet&q=".$searchString."&max-results=1&type=video&videoCategoryId=Music&key=".$DEVELOPER_KEY);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$json1 = curl_exec($ch);
	curl_close($ch);

	$youtube = json_decode($json1, true);

	$url = $youtube["items"][0]["id"]["videoId"];
	$thumbnail = $youtube["items"][0]["snippet"]["thumbnails"]["default"]["url"];
	$title = $youtube["items"][0]["snippet"]["title"];
	
}

$array = array(
    "twitterId" => $twitterId,
    "url" => $url,
	"thumbnail" => $thumbnail,
	"title" => $title,
	"searchString" => $searchString
);

$json = json_encode($array);

echo($json);
?>
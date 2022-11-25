<?php
require './vendor/autoload.php';

$photos = [];
$redis = new \Predis\Client();
$cacheEntry = $redis->get('photos');
if($cacheEntry){
echo "Displaying data from Redis Cache <br>";
$photos = \GuzzleHttp\json_decode($cacheEntry);
}
else{
    echo "Displaying data from REST API server <br>";
    $httpClient = new \GuzzleHttp\Client(["base_uri" => "https://jsonplaceholder.typicode.com/","verify"=>false]);
    $response = $httpClient->request("GET","photos");
    $photos = \GuzzleHttp\json_decode($response->getBody());
    $redis->set('photos',json_encode($photos));
    $redis->expire('photos',10);
}
foreach($photos as $photo)
{
    echo "<strong>AlbumID:</strong> $photo->albumId <br>";
    echo "<strong>ID:</strong> $photo->id <br>";
    echo "<strong>AlbumTitle:</strong> $photo->title <br>";
    echo "<strong>AlbumID:</strong> $photo->albumId <br>";
    echo "<strong>AlbumUrl:</strong> $photo->url <br>";
    echo "<strong>ThumbnailUrl:</strong> $photo->thumbnailUrl <br>";
}
?>

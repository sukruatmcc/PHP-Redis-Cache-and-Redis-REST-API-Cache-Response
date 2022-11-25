<?php
require './vendor/autoload.php';

$redis = new Predis\Client();
$cacheEntry =  $redis->get('members');

if($cacheEntry){
    echo "From Redis Cache <br>";
    echo $cacheEntry;
    exit();
}else{
    $conn = new PDO("mysql:host=localhost;dbname=test", "root", "");
    $sql = "SELECT MemberName, MemberLastName FROM members";
    $result = $conn->query($sql);
    echo "From Database <br>";
    $temp = '';
    while($row = $result->fetch()){
        echo $row["MemberName"].'<br>';
        echo $row["MemberLastName"].'<br>';
        $temp.=$row["MemberName"] . '  '.$row["MemberLastName"] . '<br>';
    }
    $redis->set("members",$temp);
    //$redis->expire("members",10);//yeni eklenen veriler sayfa yenilenmeden 10 saniye sonra redis cache edilir.
    exit();
}
?>

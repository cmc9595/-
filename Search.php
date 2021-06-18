<?php
//<meta http-equiv="Content-Type" content="text/html"; charset=utf-8">
//save POST input from QUERY.HTML into DB
//when receive POST request from SHOWRESULT.HTML, get input from DB and do SELECT * from DB and echo array.

$host = 'localhost';
$user = 'root';
$pw = 'root';
$dbname = 'articles';

$conn = new mysqli($host, $user, $pw, $dbname);
if($conn->connect_error){
	die("Connection failed: ".$conn->connect_error);
}

$num = $_POST['num']; //which file POST request came from
$val = $_POST['val'];

//query.html
if($num == "0"){
	date_default_timezone_set('Asia/Seoul');
	$time = date("Y-m-d H:i:s");

	$sql = "INSERT INTO input (input, time) VALUES ('".$val."', '".$time."')";

	if($conn->query($sql) === TRUE){
		echo "input insert success<br>";
	}
	else{
		echo $conn->error."<br>";
	}
}
//ajax autocomplete
if($num == "1"){
	$arr = array();
	$sql = "SELECT * FROM nouns WHERE name LIKE '".$val."%' and CHAR_LENGTH(name)>=2"; // 앞글자로 시작한느단어 찾기(2글자이상)
	$res = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_assoc($res)){
		$arr[] = $row['name'];
	}
	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
}
//showresult.html
if($num == "2"){
	$sql1 = "SELECT * FROM input WHERE time=(SELECT MAX(time) FROM input)";//most recent input
	$res1 = mysqli_query($conn, $sql1);
	$row = mysqli_fetch_assoc($res1);

	$arr = array();
	$sql2 = "SELECT * FROM articles WHERE title LIKE '%".$row['input']."%' and maintext LIKE '%".$row['input']."%' ORDER BY VIEWS DESC"; //search from title, maintext
	$res2 = mysqli_query($conn, $sql2);

	//echo "결과 : ".mysqli_num_rows($res2)." results<br>";
	if(mysqli_num_rows($res2) > 0){
		while($row = mysqli_fetch_assoc($res2)){
			$arr[] = $row;
		}
	}
	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
}
$conn->close();
?>

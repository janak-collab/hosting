<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<br><br>
<html>
	<head>
<link rel="stylesheet" type="text/css" href="report.css" />
	</head>
<?php
$servername = "localhost";
$username = "jvidyart_janak";
$password = "himabim1";
$dbname = "jvidyart_provider";

$conn = new mysqli($servername, $username, $password, $dbname);

$w=$_POST["who"];
$t=$_POST["title"];
$f=$_POST["first"];
$l=$_POST["last"];
$d=$_POST["degree"];

$sql = "INSERT INTO list (who,title,first,last,degree)

VALUES ('$w','$t','$f','$l','$d')";

if ($conn->query($sql) === TRUE) {
    echo "";
} else {
    echo "" . $conn->error;
}

$conn->close();

?>
<br><br>
<form action=index.html>
<button class="box effect1 effect7">RETURN</button>
</form>
</body>
</html>
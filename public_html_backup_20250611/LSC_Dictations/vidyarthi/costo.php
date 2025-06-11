<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<h1>Costochondral Joint Injection</h1>
<br>
<form method=post action=costodictation.php>
<table>
<tr>
<td>Name:</td><td><input class="n" type=text size=16 name=pname required></input></td>
</tr>
</table>
<!--
<table>
<tr><td>Referring:</td><td>
   <SELECT NAME=ref>
      <OPTION VALUE=0>
<?php
$servername = "localhost";
$username = "jvidyart_janak";
$password = "himabim1";
$dbname = "jvidyart_provider";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT who,title,first,last,degree FROM list WHERE who='ref' ORDER BY last";
$result = $conn->query($sql);

if ($result->num_rows > 0){
    // output data of each row
    while($row = $result->fetch_array()) {
    echo "<option>" . $row["first"]. " " . $row["last"]. ", " . $row["degree"]. "";
    }
}
echo "</option>";
?>
<br><br>
<tr><td>Primary:</td><td>
<SELECT name=pri>
<OPTION VALUE=0>
<?php
$sql = "SELECT who,title,first,last,degree FROM list WHERE who='pri' ORDER BY last";
$result = $conn->query($sql);

if ($result->num_rows > 0){
    // output data of each row
    while($row = $result->fetch_array()) {
    echo "<option>" . $row["first"]. " " . $row["last"]. ", " . $row["degree"]. "";
    }
}
echo "</option>";

$conn->close();
?>
</SELECT></td></tr></table>
-->
<br><br>
<input type=checkbox name=date value=tomorrow>Tomorrow's procedure?</input>
<br><br>
<!--
<textarea name=history cols=50 rows=5></textarea>
-->
<br>
<table class=center>
<tr><td width=100>RIGHT</td><td width=100>LEFT</td></tr>
<tr><td><input type=checkbox name=rcosto[] value="T1">T1<br><br></td>
<td><input type=checkbox name=lcosto[] value="T1">T1<br><br></td></tr>
<tr><td><input type=checkbox name=rcosto[] value="T2">T2<br><br></td>
<td><input type=checkbox name=lcosto[] value="T2">T2<br><br></td></tr>
<tr><td><input type=checkbox name=rcosto[] value="T3">T3<br><br></td>
<td><input type=checkbox name=lcosto[] value="T3">T3<br><br></td></tr>
<tr><td><input type=checkbox name=rcosto[] value="T4">T4<br><br></td>
<td><input type=checkbox name=lcosto[] value="T4">T4<br><br></td></tr>
<tr><td><input type=checkbox name=rcosto[] value="T5">T5<br><br></td>
<td><input type=checkbox name=lcosto[] value="T5">T5<br><br></td></tr>
<tr><td><input type=checkbox name=rcosto[] value="T6">T6<br><br></td>
<td><input type=checkbox name=lcosto[] value="T6">T6<br><br></td></tr>
<tr><td><input type=checkbox name=rcosto[] value="T7">T7<br><br></td>
<td><input type=checkbox name=lcosto[] value="T7">T7<br><br></td></tr>
</table>
<button class="box effect1 effect7">DICTATE</button>
</form>
<br>
<form action=https://www.janakvidyarthi.com/private_area/LSC_Dictations/vidyarthi/index.php>
<button type=submit name=location class="box effect1 effect7">RETURN</button><br>
</form>
</body>
</html>
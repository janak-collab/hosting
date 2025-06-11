<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<h1>Sacroiliac Joint</h1>
<br>
<form method=post action=sidictation.php>
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
<br>
<!--
<textarea name=history cols=50 rows=5></textarea>
-->
<br><br>
<input type=radio name=side value=R required>Right</input>
<input type=radio name=side value=L required>Left</input>
<input type=radio name=side value=B required>Bilateral</input>
<br><br>
<button type=submit name=location class="box effect1 effect7" value="sij">SIJ</button><br>
<button type=submit name=location class="box effect1 effect7" value="sijdx">dx</button><br>
</form>
<br>
<form action=https://www.janakvidyarthi.com/private_area/LSC_Dictations/vidyarthi/index.php>
<button type=submit name=location class="box effect1 effect7">RETURN</button><br>
</form>
</body>
</html>
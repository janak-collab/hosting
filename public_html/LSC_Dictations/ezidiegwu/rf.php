<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<h1>Radiofrequency ablation</h1>
<br>
<form method=post action=rfdictation.php>
<table>
<tr>
<td>Name:</td><td><input class="n" type=text size=16 name=pname></input></td>
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
<input type=checkbox name=sedation value=yes>Sedation</input><br><br>
<!--
<br><br>
<textarea name=history cols=50 rows=5></textarea>
-->

<input type=radio name=side value=Right>Right</input>
<input type=radio name=side value=Left>Left</input>
<br><br>
<input type=checkbox name=sensation value=motor>Sensation NOT stimulated?</input>
<br><br>
<table>
<tr>
<td align=right><button type=submit name=location value="L4, L5" class="box effect1 effect7">L4, L5</button></td>
</tr>
<tr>
<td align=right><button type=submit name=location value="knee" class="box effect1 effect7">knee</button></td>
</tr>
</table>
</form>
<br>
<form action=https://www.janakvidyarthi.com/private_area/LSC_Dictations/ezidiegwu/index.php>
<button type=submit name=location class="box effect1 effect7">RETURN</button><br><br><br>
</form>
</body>
</html>
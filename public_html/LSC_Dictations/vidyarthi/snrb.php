<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<h1>Selective Nerve Root Block</h1>
<br>
<form method=post action=snrbdictation.php>
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
<br><br>
<input type=checkbox name=sedation value=yes>Sedation</input>
-->
<br><br>
<input type=radio name=side value=Right required>Right</input>
<input type=radio name=side value=Left required>Left</input>
<input type=radio name=side value=Bilateral required>Bilateral</input>
<br><br>
<!--
<textarea name=history cols=50 rows=5></textarea>
<br><br>
-->
<br>
<table class=center>
<tr><td width=100>LEVEL</td>
<tr><td><input type=checkbox name=location[] value="C3"></input>C3<br><br></td>
<tr><td><input type=checkbox name=location[] value="C4"></input>C4<br><br></td>
<tr><td><input type=checkbox name=location[] value="C5"></input>C5<br><br></td>
<tr><td><input type=checkbox name=location[] value="C6"></input>C6<br><br></td>
<tr><td><input type=checkbox name=location[] value="C7"></input>C7<br><br></td>
<tr><td><input type=checkbox name=location[] value="C8"></input>C8<br><br></td>
<tr><td><br></td></tr>
<tr><td><input type=checkbox name=location[] value="T2"></input>T2<br><br></td>
<tr><td><input type=checkbox name=location[] value="T3"></input>T3<br><br></td>
<tr><td><input type=checkbox name=location[] value="T4"></input>T4<br><br></td>
<tr><td><input type=checkbox name=location[] value="T5"></input>T5<br><br></td>
<tr><td><input type=checkbox name=location[] value="T6"></input>T6<br><br></td>
<tr><td><input type=checkbox name=location[] value="T7"></input>T7<br><br></td>
<tr><td><input type=checkbox name=location[] value="T8"></input>T8<br><br></td>
<tr><td><input type=checkbox name=location[] value="T9"></input>T9<br><br></td>
<tr><td><input type=checkbox name=location[] value="T10"></input>T10<br><br></td>
<tr><td><input type=checkbox name=location[] value="T11"></input>T11<br><br></td>
<tr><td><input type=checkbox name=location[] value="T12"></input>T12<br><br></td>
<tr><td><br></td></tr>
<tr><td><input type=checkbox name=location[] value="L1"></input>L1<br><br></td>
<tr><td><input type=checkbox name=location[] value="L2"></input>L2<br><br></td>
<tr><td><input type=checkbox name=location[] value="L3"></input>L3<br><br></td>
<tr><td><input type=checkbox name=location[] value="L4"></input>L4<br><br></td>
<tr><td><input type=checkbox name=location[] value="L5"></input>L5<br><br></td>
<tr><td><input type=checkbox name=location[] value="S1"></input>S1<br><br></td>
</table>
<button type=submit class="box effect1 effect7">DICTATE</button><br><br><br>
</form>
<form action=https://www.janakvidyarthi.com/private_area/LSC_Dictations/vidyarthi/index.php>
<button type=submit name=location class="box effect1 effect7">RETURN</button>
</form>
</body>
</html>
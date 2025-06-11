<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<h1>Facets</h1>
<br>
<form method=post action=facetsdictation.php>
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
<input type=checkbox name=sedation value=yes>Sedation</input>
-->
<br>
<input type=radio name=side value=Right required>Right</input>
<input type=radio name=side value=Left required>Left</input>
<input type=radio name=side value=Bilateral required>Bilateral</input>
<!--
<br><br>
<textarea name=history cols=50 rows=5></textarea>
-->
<br><br><br>
<button type=submit name=location1 class="box effect1 effect7" value="C2-3, C3-4, C4-5">C2-3, C3-4, C4-5</button><br>
<button type=submit name=location1 class="box effect1 effect7" value="C3-4, C4-5, C5-6">C3-4, C4-5, C5-6</button><br>
<button type=submit name=location1 class="box effect1 effect7" value="C4-5, C5-6, C6-7">C4-5, C5-6, C6-7</button><br>
<button type=submit name=location1 class="box effect1 effect7" value="C5-6, C6-7, C7-T1">C5-6, C6-7, C7-T1</button><br>
<br>
Thoracic:<br>
<select class="box effect7 effect1" name=location2 onChange="this.form.submit();">
  <option></option>
  <option value="T1-2, T2-3, T3-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T1-2, T2-3, T3-4</option>
  <option value="T2-3, T3-4, T4-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T2-3, T3-4, T4-5</option>
  <option value="T3-4, T4-5, T5-6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T3-4, T4-5, T5-6</option>
  <option value="T4-5, T5-6, T6-7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T4-5, T5-6, T6-7</option>
  <option value="T5-6, T6-7, T7-8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T5-6, T6-7, T7-8</option>
  <option value="T6-7, T7-8, T8-9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T6-7, T7-8, T8-9</option>
  <option value="T7-8, T8-9, T9-10">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T7-8, T8-9, T9-10</option>
  <option value="T8-9, T9-10, T10-11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T8-9, T9-10, T10-11</option>
  <option value="T9-10, T10-11, T11-12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T9-10, T10-11, T11-12</option>
  <option value="T10-11, T11-12, T12-L1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T10-11, T11-12, T12-L1</option>
</select>
<br><br>
<button type=submit name=location3 class="box effect1 effect7" value="L1-2, L2-3, L3-4">L1-2, L2-3, L3-4</button><br>
<button type=submit name=location3 class="box effect1 effect7" value="L2-3, L3-4, L4-5">L2-3, L3-4, L4-5</button><br>
<button type=submit name=location3 class="box effect1 effect7" value="L3-4, L4-5, L5-S1">L3-4, L4-5, L5-S1</button><br>
<!-- HIDDEN -->
<button type=submit name=location3 class="box effect1 effect7" value="L3-4, L4-5">L3-4, L4-5</button><br>
<!-- HIDDEN -->
<button type=submit name=location3 class="box effect1 effect7" value="L4-5, L5-S1">L4-5, L5-S1</button><br>
<!--<button type=submit name=location3 class="box effect1 effect7" value="L5-S1">L5-S1</button><br>-->
</form>
<br>
<form action=https://www.janakvidyarthi.com/private_area/LSC_Dictations/ezidiegwu/index.php>
<button type=submit name=location class="box effect1 effect7">RETURN</button><br>
</form>
</body>
</html>
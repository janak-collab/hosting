<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<h1>Medial branch block</h1>
<br>
<form method=post action=mbbdictation.php>
<table>
<tr>
<td>Name:</td><td><input class="n" type=text size=16 name=pname required></input></td>
</tr>
</table>

<br><br>
<input type=checkbox name=date value=tomorrow>Tomorrow's procedure?</input>
<br><br><br>
<input type=radio name=side value=Right required>Right</input>
<input type=radio name=side value=Left required>Left</input>
<input type=radio name=side value=Bilateral required>Bilateral</input>
<br><br>
<table>
<tr>
<td align=right><button type=submit name=location value="cervicalmbb" class="box effect1 effect7">cervical</button></td>
</tr>
<tr><td align=center>
<br>
Thoracic:<br>
<select class="box effect7 effect1" name=location2 onChange="this.form.submit();">
  <option></option>
  <option value="T1, T2, T3, T4">T1, T2, T3, T4 on the T2, T3, T4, T5 transverse process</option>
  <option value="T8, T9, T10, T11">T8, T9, T10, T11 on the T9, T10, T11, T12 transverse process</option>
</select>
<br><br><br>
</td></tr>
<tr>
<td align=center>Lumbar:<br><button type=submit name=location value="lumbarmbb" class="box effect1 effect7">L2, L3, L4, L5</button></td>
</tr>
</table>
</form>
<br><br>
<form action=https://www.janakvidyarthi.com/private_area/LSC_Dictations/ezidiegwu/index.php>
<button type=submit class="box effect1 effect7">RETURN</button><br>
</form>
</body>
</html>
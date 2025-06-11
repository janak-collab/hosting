<html>
	<head>
<link rel="stylesheet" type="text/css" href="index.css" />
	</head>
<center>
<br>
<h1>Send letters to</h1>
<br>
<form method=post action=postprov.php>
<table>
<tr>
<td align=center>Title</td><td align=center>First name</td><td align=center>Last name</td><td align=center>Degree</td></tr>
<tr><td><br></td></tr>
<tr>
<td><input type=radio name=title value=Dr.>Dr<br><input type=radio name=title value=Mr.>Mr<br><input type=radio name=title value=Ms.>Ms</td><td><input type=text name=first></td><td><input type=text name=last></td><td><input type=radio name=degree value=M.D.>MD<br><input type=radio name=degree value=D.O.>DO<br><input type=radio name=degree value=PA-C>PA-C<br><input type=radio name=degree value=CRNP>CRNP<br><input type=radio name=degree value=NP-C>NP-C<br></tr><tr><td><br></td></tr>
<tr><td colspan=4 align=center><input type=radio name=who value=ref>Referring provider</td></tr><tr><td colspan=4 align=center><input type=radio name=who value=pri>Primary provider</td></tr>
</table>
<br>
<button class="box effect1 effect7">SUBMIT</button>
</form>
<form action=index.html>
<button class="box effect1 effect7">Return</button>
</form>
</body>
</html>
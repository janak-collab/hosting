<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$rcosto=$_POST["rcosto"];
$lcosto=$_POST["lcosto"];

if ($rcosto=="" AND $lcosto=="")
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=costo.php\">";
}
elseif ($rcosto!="" OR $lcosto!="")
{
echo "<body onload='myFunction()'>
<script>
function myFunction() {
    window.print();
}
</script>
";
}
?>
<center>
<table>
<tr>
<td width=450>&nbsp;</td>
<td class=border align=center>
<?php
echo "$name";
?>
</td>
</tr>
</table>
<?php
$servername = "localhost";
$username = "jvidyart_janak";
$password = "himabim1";
$dbname = "jvidyart_dictation";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "DROP TABLE IF EXISTS `procedure`";

if ($conn->query($sql) === TRUE) {
    echo "";
} else {
    echo "" . $conn->error;
}

$sql = "CREATE TABLE `procedure` (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	name varchar(50),
	rcosto varchar(50),
	lcosto varchar(50),
	reg_date TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "";
} else {
    echo "" . $conn->error;
}

$sql = "INSERT INTO `procedure` (name,rcosto,lcosto)

VALUES ('$name','$rcosto','$lcosto')";

if ($conn->query($sql) === TRUE) {
    echo "";
} else {
    echo "" . $conn->error;
}

$conn->close();

?>
<center>
<h1>Procedure Dictation</h1>
<table>
<tr>
<td width=250><b>Attending Physician:<br><br></b></td><td width=450>Daniel Ezidiegwu, D.O.<br><br></td>
</tr>
<tr>
<td><b>Pre-Operative Diagnosis:<br><br></b></td><td>1. Chondrocostal junction syndrome<br>2. Chest pain, unspecified<br><br></td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:<br><br><br></b></td><td>1. Chondrocostal junction syndrome<br>2. Chest pain, unspecified<br><br></td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
if (!empty($rcosto) AND empty($lcosto))
{
echo "Right ".implode(", ",$rcosto)." ";
}
elseif (empty($rcosto) AND !empty($lcosto))
{
echo "Left ".implode(", ",$lcosto)." ";
}
elseif (!empty($rcosto) AND !empty($lcosto))
{
echo "Right ".implode(', ',$rcosto)." and left ".implode(', ',$lcosto)." ";
}
?> costochondral joint injection under fluoroscopy
<br><br>
</td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>None
<br><br></td>
</tr>
<tr>
<td><b>Indications for procedure:</b></td><td></td>
</tr>
<tr>
<td colspan=2>A pleasant patient presents with chest pain pain clinically secondary to costochondritis. The patient has failed more conservative measures and is ongoing diagnostic and potentially therapeutic costochondral joint injection<?php
if (count($rcosto)+count($lcosto)>1)
{
echo "s";
}
?>.<br><br></td>
</tr>
<tr>
<td><b>Consent:</b></td><td></td>
</tr>
<tr>
<td colspan=2>The patient was seen in the pre-procedure area. The procedure was confirmed and described to the patient. Risks, benefits, and alternatives were discussed. All questions were answered. Informed consent was signed.<br><br>
</td>
</tr>
<tr>
<td><b>Procedure in detail:</b></td><td></td>
</tr>
<tr>
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a supine position on the fluoroscopy table. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to guide a 25-gauge spinal needle towards the 
<?php
if (!empty($rcosto))
{
echo "right ";
echo current($rcosto);
}
else
{
echo "left ";
echo current($lcosto);
}
if (!empty($csnrb) AND empty($tsnrb) AND empty($lsnrb))
{
echo start($csnrb);
}
?> costochondral joint. Os was contacted. After negative aspiration, 1mL of a solution containing 1 mL of 10mg/mL of triamcinolone and 
<?php
if (count($rcosto)+count($lcosto)==1)
{
echo "1";
}
else
{
echo count($rcosto)+count($lcosto)-1;
}
?>
mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact.
<?php
if (count($rcosto)+count($lcosto)>2)
{
echo "The procedure was repeated with the technique above at the remaining levels.";
}
elseif (count($rcosto)+count($lcosto)==2)
{
echo "The procedure was repeated with the technique above at the remaining level.";
}
?>
<br><br>The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
</tr>
<tr>
<td><b>Follow-up:</b></td>
<tr>
<tr>
<td colspan=2>The patient will follow up in the clinic in 2 weeks in order to access the efficacy of the procedure.</td>
</tr>
<tr>
<td><br><br><br><br><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>Daniel Ezidiegwu, D.O.<br>
<?php
$tomorrow=$_POST[date];
if ($tomorrow=="tomorrow")
{
$tomorrow2 = new DateTime($tomorrow);
echo $tomorrow2->format('m/d/y');
}
else
{
echo date("m/d/y");
}
echo "<br><br>";

$r=$_POST[ref];
if ($r>"0")
  {
  echo "<tr><td colspan=2>Referring Physician or provider: $r</td></tr>";
  }
else
  {
  echo "";
  }
$p=$_POST[pri];
if ($p>"0")
  {
  echo "<tr><td colspan=2>Primary care physician or provider: $p</td></tr>";
  }
else
  {
  echo "";
  }
?>
</table>
<?php
$conn = mysqli_connect($servername, $username, $password, $dbname);
$sql = "DROP TABLE `procedure`";
if ($conn->query($sql) === TRUE) {
    echo "";
} else {
    echo "" . $conn->error;
}
$conn->close();
?>
</html>
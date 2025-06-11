<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
?>

<body onload='myFunction()'>
<script>
function myFunction() {
    window.print();
}
</script>
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
<h1>Procedure Dictation</h1>
<table>
<tr>
<td width=250><b>Attending Physician:<br><br></b></td><td width=450>Janak Vidyarthi, M.D.<br><br></td>
</tr>
<tr>
<td><b>Pre-Operative Diagnosis:<br><br></b></td><td>Coccydynia<br><br></td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:<br><br></b></td><td>Coccydynia<br><br></td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
$l=$_POST["location"];
echo "Sacrococcygeal/Intercoccygeal joint injection at ";

if ($l=="1")
{
echo "1 joint";
}
elseif ($l=="2")
{
echo "2 joints";
}
elseif ($l=="3")
{
echo "3 joints";
}
?><br><br></td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>None
<br><br></td>
</tr>
<tr>
<td><b>Indications for procedure:</b></td><td></td>
</tr>
<tr>
<td colspan=2>A pleasant patient presents with tailbone pain clinically secondary to coccydynia who has failed has failed more conservative measures. 
<?php
$history=$_POST["history"];
echo $history;
?>
 The patient is undergoing diagnostic and potentially therapeutic coccyx joint injection.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a prone position on the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>The painful intercoccygeal joint was identified and marked. Fluoroscopy was used in AP, lateral, and oblique view to guide a 25-gauge spinal needle to the identified joint of interest. Os was contacted. With negative aspirations, needle tip location was confirmed with Omnipaque 240mL/mL, less than 0.5mL was used. After negative aspiration, 1mL of a solution containing 1mL of 40mg/mL of triamcinolone and 
<?php
if ($l=="1" OR $l=="2")
{
echo "1";
}
elseif ($l=="3")
{
echo "2";
}
?>
mL of 0.25% bupivacaine was incrementally injected. The needle was withdrawn intact. 
<?php
if ($l!="1")
{
echo "The procedure was repeated at the remaining levels. ";
}
?>
The patient's skin was then cleaned.<br><br>The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
</tr>
<tr>
<td><b>Follow-up:</b></td>
<tr>
<tr>
<td colspan=2>The patient will follow up in the clinic in 2 weeks in order to access the efficacy of the procedure.</td>
</tr>
<tr>
<td><br><br><br><br><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>Janak Vidyarthi, M.D.<br>
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
</html>
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
<td><b>Pre-Operative Diagnosis:<br><br><br></b></td><td>
<?php
$l=$_POST["location"];
if ($l=="left")
{
echo "1. Left acromioclavicular joint osteoarthritis<br>2. Left acromioclavicular joint pain";
}
elseif ($l=="right")
{
echo "1. Right acromioclavicular joint osteoarthritis<br>2. Right acromioclavicular joint pain";
}
elseif ($l=="bilateral")
{
echo "1. Bilateral acromioclavicular joint osteoarthritis<br>2. Bilateral acromioclavicular joint pain";
}
?>
<br><br></td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br><br></td><td>
<?php
if ($l=="left")
{
echo "1. Left acromioclavicular joint osteoarthritis<br>2. Left acromioclavicular joint pain";
}
elseif ($l=="right")
{
echo "1. Right acromioclavicular joint osteoarthritis<br>2. Right acromioclavicular joint pain";
}
elseif ($l=="bilateral")
{
echo "1. Bilateral acromioclavicular joint osteoarthritis<br>2. Bilateral acromioclavicular joint pain";
}
?><br><br></td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
echo ucfirst($l);
?> acromioclavicular joint injection under fluoroscopy<br><br></td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>None<br><br></td>
</tr>
<tr>
<td><b>Indications for procedure:</b></td><td></td>
</tr>
<tr>
<td colspan=2>A pleasant patient presents with chronic 
<?php
echo strtok($l, " ");
?> shoulder pain clinically secondary to acromioclavicular joint osteoarthritis who has failed more conservative measures. The patient is undergoing diagnostic and potentially therapeutic acromioclavicular joint injection.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a supine position on the fluoroscopy table. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to guide a 22-gauge spinal needle to the inferior pole of the 
<?php
if ($l!="bilateral")
{
echo $l;
}
else
{
echo "right";
}
?> acromioclavicular joint using a cephalad to caudal approach starting laterally. With negative aspirations, needle tip location was confirmed with Omnipaque 240mg/mL, less than 0.5mL was used. Then, after negative aspiration, 1mL of a solution containing 0.5mL of 40mg/mL of triamcinolone and 0.5mL of 0.25% bupivacaine was incrementally injected. The needle was withdrawn intact. 
<?php
if ($l=="bilateral")
{
echo " The procedure was repeated with the techniques above on the contralateral side. ";
}
?>
The patient's skin was then cleaned.
<br><br>The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
</tr>
<tr>
<td colspan=2><b>Follow up:</b><br>The patient will follow up in the clinic in 2 weeks in order to access the efficacy of the procedure.</td>
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
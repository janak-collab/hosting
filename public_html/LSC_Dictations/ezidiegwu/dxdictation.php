<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$dob=$_POST['dob'];
$l=$_POST["location"];
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
<td width=250><b>Attending physician:<br><br></b></td><td>Daniel Ezidiegwu, D.O.<br><br></td>
</tr>
<tr>
<td><b>Pre-operative diagnosis:<br><br><br></b></td><td>
<?php
if ($l=="right")
{
echo "1. Pain in right knee<br>2. Osteoarthritis of right knee";
}
elseif ($l=="left")
{
echo "1. Pain in left knee<br>2. Osteoarthritis of left knee";
}
?>
<br><br>
</td>
</tr>
</tr>
<tr>
<td><b>Post-operative diagnosis:</b><br><br><br></td><td>
<?php
if ($l=="right")
{
echo "1. Pain in right knee<br>2. Osteoarthritis of right knee";
}
elseif ($l=="left")
{
echo "1. Pain in left knee<br>2. Osteoarthritis of left knee";
}
?>
<br><br></td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br><br></td><td>
<?php
if ($l=="right")
{
echo "1. Right superior lateral genicular nerve block<br>2. Right superior medial genicular nerve block<br>3. Right inferior medial genicular nerve block<br><br>";
}
elseif ($l=="left")
{
echo "1. Left superior lateral genicular nerve block<br>2. Left superior medial genicular nerve block<br>3. Left inferior medial genicular nerve block<br><br>";
}
?>
</td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>
<?php
$s=$_POST["sedation"];
if ($s=="yes")
  {
  echo "Conscious sedation";
  }
else
  {
  echo "None";
  }
?>
<br><br></td>
</tr>
<tr>
<td><b>Indications for procedure:</b></td><td></td>
</tr>
<tr>
<td colspan=2>A pleasant patient presents with chronic 
<?php
echo $l;
?> knee pain clinically secondary to knee osteoarthritis who has failed more conservative measures. The patient is undergoing diagnostic blocks for consideration for radiofrequency ablation for the treatment of severe debilitating pain.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a supine position on the fluoroscopy table with a pillow under the
<?php
echo $l;
?>
 knee to facilitate access. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to guide a 22-gauge spinal needle to the 
<?php
echo $l;
?>
 femoral lateral epicondyle in a para-sagittal orientation. Spinal needles were placed using the techniques above at the remaining sites with the needle tips at the femoral medial epicondyle and tibial medial epicondyle. After negative aspiration, 2mL of 0.25% bupivacaine was incrementally injected.
<br><br>The patient's skin was then cleaned. The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
</tr>
<tr>
<td colspan=2><b>Follow up:</b><br>The patient will follow up in the clinic in 2 weeks in order to access the efficacy of the procedure.</td>
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
</html>
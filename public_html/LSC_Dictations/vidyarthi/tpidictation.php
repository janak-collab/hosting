<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$location=$_POST['location'];
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
<h1>Procedure Dictation</h1>
<table>
<tr>
<td width=250><b>Attending Physician:<br><br></b></td><td width=450>Janak Vidyarthi, M.D.<br><br></td>
</tr>
<tr>
<td><b>Pre-Operative Diagnosis:<br><br><br></b></td><td>
1. Muscle spasms<br>
2. Myalgias<br><br>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br><br></td><td>
1. Muscle spasms<br>
2. Myalgias<br><br>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>Trigger point injection under fluoroscopy at 
<?php
echo $location;
?>
<br><br></td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>None
<br><br></td>
</tr>
<tr>
<td><b>Indications for procedure:</b></td><td></td>
</tr>
<tr>
<td colspan=2>A pleasant patient presents with pain clinically secondary to muscle spasms and myofascial pain syndrome who has failed more conservative measures. The patient is undergoing diagnostic and potentially therapeutic trigger point injection. Fluoroscopy is necessary for safety, accuracy, and precision due to elevated risks of pneumothorax and death due to location.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a prone position on the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis. Monitors were applied and the patient was monitored throughout the procedure. Each of the trigger point to be injected was identified and marked. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to guide a 25-gauge spinal needle to the<br><br>trigger point. After negative aspiration, 1mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact.<br><br>The procedure was repeated at the previously marked trigger points. The patient's skin was then cleaned.<br><br>The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met. The patient is to go to the ER immediately if symptoms such as lightheadedness, shortness of breath, chest pain, altered mental status, or palpitations develop.<br><br></td>
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
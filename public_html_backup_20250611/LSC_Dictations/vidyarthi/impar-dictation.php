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
<td><b>Pre-Operative Diagnosis:<br><br></b></td><td>Pelvic and perineal pain<br><br></td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br></td><td>Pelvic and perineal pain<br><br></td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>Ganglion impar block under fluoroscopy<br><br></td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>None<br><br></td>
</tr>
<tr>
<td><b>Indications for procedure:</b></td><td></td>
</tr>
<tr>
<td colspan=2>A pleasant patient presents with chronic pelvic and perineal pain causing significant pain and morbidity. The patient is undergoing diagnostic and potentially therapeutic ganglion impar block having failed more conservative measures.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a prone position on the fluoroscopy table with a pillow under the abdomen/pelvis to facilitate positioning. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to visualize the sacrococcygeal junction. A 27-gauge 1.25" needle was used to anesthetize the skin to os using 2mL of 1% lidocaine. A 22-gauge spinal needle was then advanced to the target site through the sacrococcygeal joint. Care was taken not to advance the needle much beyond the anterior border of the sacrum or coccyx in lateral view. Once the needle tip was just past the anterior border, aspirations were confirmed to be negative. The needle tip position was confirmed with live fluoroscopy using Omnipaque 240mg/mL, 1mL used. Then, after negative aspiration, a test dose of 1mL of 1% lidocaine was slowly and incrementally injected. The patient was assessed for peri-oral numbness, metallic taste in the mouth, or tinnitus. With the above symptoms being negative after 2 minutes, 4mL of 0.25% bupivacaine was slowly and incrementally injected with frequent assessments for peri-oral numbness, metallic taste in the mouth, or tinnitus. The patient denied the symptoms throughout the procedure.<br><br>The needle was removed intact. The patient's skin was then cleaned. The patient tolerated the procedure well without immediate complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br>
<b>Follow up:</b><br>The patient will follow up in the clinic in 2 weeks in order to access the efficacy of the procedure.</td>
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
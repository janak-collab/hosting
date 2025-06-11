<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$dob=$_POST['dob'];
$p=$_POST["side"];
$l=$_POST["location"];
$l2=$_POST["location2"];
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
<td width=250><b>Attending Physician:<br><br></b></td><td width=450>Daniel Ezidiegwu, D.O.<br><br></td>
</tr>
<tr>
<td><b>Pre-Operative Diagnosis:<br><br><br></b></td><td>
<?php
$l=$_POST["location"];
if ($l=="cervicalmbb")
{
echo "1. Cervical spondylosis<br>2. Cervicalgia<br><br></td></tr>";
}
if ($l=="lumbarmbb")
{
echo "1. Lumbar spondylosis<br>2. Lumbago<br><br></td></tr><tr>";
}
if ($l2=="T1, T2, T3, T4" OR $l2=="T8, T9, T10, T11")
{
echo "1. Thoracic spondylosis<br>2. Thoracic pain<br><br></td></tr>";
}
?>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br><br></td><td>
<?php
$l=$_POST["location"];
if ($l=="cervicalmbb")
{
echo "1. Cervical spondylosis<br>2. Cervicalgia<br><br></td></tr>";
}
if ($l=="lumbarmbb")
{
echo "1. Lumbar spondylosis<br>2. Lumbago<br><br></td></tr>";
}
if ($l2=="T1, T2, T3, T4" OR $l2=="T8, T9, T10, T11")
{
echo "1. Thoracic spondylosis<br>2. Thoracic pain<br><br></td></tr>";
}
?>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
echo $p;
echo "&nbsp;";
if ($l=="cervicalmbb")
{ echo "C4, C5, C6, C7 medial branch block under fluoroscopy";
}
if ($l=="lumbarmbb")
{ echo "L2, L3, L4 medial branch and L5 dorsal ramus block under fluoroscopy";
}
if ($l2=="T1, T2, T3, T4")
{ echo "T1, T2, T3, T4 medial branch block under fluoroscopy";
}
if ($l2=="T8, T9, T10, T11")
{ echo "T8, T9, T10, T11 medial branch block under fluoroscopy";
}
?>
<br><br></td>
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
if ($l=="cervicalmbb")
{
echo "neck pain clinically secondary to cervical spondylosis and cervical facet-mediated disorder";
}
elseif ($l=="lumbarmbb")
{
echo "back pain clinically secondary to lumbar spondylosis and lumbar facet-mediated disorder";
}
elseif ($l2=="T1, T2, T3, T4" OR $l2=="T8, T9, T10, T11")
{
echo "mid back pain clinically secondary to thoracic spondylosis and thoracic facet-mediated disorder";
}
?>
 who has failed more conservative measures. The patient is undergoing diagnostic medial branch block in anticipation for radiofrequency ablation.<br><br>
</td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a 
<?php
if ($l=="cervicalmbb")
{
echo "prone position on the fluoroscopy table with a cervical positioning system.";
}
elseif ($l=="lumbarmbb" OR $l2=="T1, T2, T3, T4" OR $l2=="T8, T9, T10, T11")
{
echo "prone position on the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis.";
}
?>
 Monitors were applied and the patient was monitored throughout the procedure.
<?php
if ($l=="cervicalmbb")
  {
  echo "The neck was positioned with a slight flexed posture and the upper extremities were carefully placed by the patient's side and a slight pull was utilized to lower the shoulders.";
  }
echo " The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>";
if ($l=='cervicalmbb')
{
echo "Using a caudal-lateral approach, fluoroscopy was used in AP, lateral, and oblique view to guide a 25-gauge spinal needle to contact the lateral border of the ";
if ($p=="Right" OR $p=="Bilateral")
{
echo "right ";
}
else
{
echo "left ";
}
echo "C4 articular pillar. The needle was then positioned while still remaining in close contact with the bone and along an orientation parallel to the C4 medial branch nerve. After negative aspirations 0.5mL of 1% lidocaine was incrementally injected. The needle was withdrawn intact. The procedure was repeated for the remaining medial branches using the techniques above.";
}
if ($l=='lumbarmbb')
{
echo "Fluoroscopy was used in AP, lateral, and oblique view to guide a 22-gauge spinal needle to the ";
if ($p=="Right" OR $p=="Bilateral")
{
echo "right ";
}
else
{
echo "left ";
}
echo "L2 medial branch at the junction of the transverse process and the superior articular process of the L3 vertebrae. After negative aspirations, 1mL of 0.25% bupivacaine was injected. The needle was withdrawn intact. The procedure was repeated for the remaining medial branches using the techniques above. The target sight for the L5-S1 joint was the L4 medial branch at the junction of the transverse process and the superior articular process of the L5 vertebrae and the L5 dorsal ramus at the groove formed between the base of the S1 superior articulating process and the sacral ala.
";
}
if ($l2=='T1, T2, T3, T4' OR $l2=='T8, T9, T10, T11')
{
echo "Fluoroscopy was used in AP, lateral, and oblique view to guide a 22-gauge spinal needle to the ";
if ($p=="Right" OR $p=="Bilateral")
{
echo "right ";
}
else
{
echo "left ";
}
if ($l2=='T1, T2, T3, T4')
{
    echo "T4";
}
elseif ($l2=='T8, T9, T10, T11')
{
    echo "T11";
}
echo " medial branch at the junction of the transverse process and the superior articular process of the ";
if ($l2=='T1, T2, T3, T4')
{
    echo "T5";
}
elseif ($l2=='T8, T9, T10, T11')
{
    echo "T12";
}
echo " vertebrae. After negative aspirations, 1mL of 0.25% bupivacaine was injected. The needle was withdrawn intact. The procedure was repeated for the remaining medial branches using the techniques above.";
}
?> <br><br>The patient's skin was then cleaned. The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
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
</html>
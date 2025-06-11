<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$p=$_POST["side"];
$l1=$_POST["location1"];
$l2=$_POST["location2"];
$l3=$_POST["location3"];
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
if ($l1=="C2-3, C3-4, C4-5" OR $l1=="C3-4, C4-5, C5-6" OR $l1=="C4-5, C5-6, C6-7" OR $l1=="C5-6, C6-7, C7-T1")
{
echo "1. Cervical spondylosis<br>2. Cervicalgia<br><br>";
}
elseif ($l2=="T1-2, T2-3, T3-4" OR $l2=="T2-3, T3-4, T4-5" OR $l2=="T3-4, T4-5, T5-6" OR $l2=="T4-5, T5-6, T6-7" OR $l2=="T5-6, T6-7, T7-8" OR $l2=="T6-7, T7-8, T8-9" OR $l2=="T7-8, T8-9, T9-10" OR $l2=="T8-9, T9-10, T10-11" OR $l2=="T9-10, T10-11, T11-12" OR $l2=="T10-11, T11-12, T12-L1")
{
echo "1. Thoracic spondylosis<br>2. Thoracic pain<br><br>";
}
elseif ($l3=="L1-2, L2-3, L3-4" OR $l3=="L2-3, L3-4, L4-5" OR $l3=="L3-4, L4-5, L5-S1" OR $l3=="L3-4, L4-5" OR $l3=="L4-5, L5-S1" OR $l3=="L5-S1")
{
echo "1. Lumbar spondylosis<br>2. Lumbago<br><br>";
}
?>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br><br></td><td>
<?php
if ($l1=="C2-3, C3-4, C4-5" OR $l1=="C3-4, C4-5, C5-6" OR $l1=="C4-5, C5-6, C6-7" OR $l1=="C5-6, C6-7, C7-T1")
{
echo "1. Cervical spondylosis<br>2. Cervicalgia<br><br>";
}
elseif ($l2=="T1-2, T2-3, T3-4" OR $l2=="T2-3, T3-4, T4-5" OR $l2=="T3-4, T4-5, T5-6" OR $l2=="T4-5, T5-6, T6-7" OR $l2=="T5-6, T6-7, T7-8" OR $l2=="T6-7, T7-8, T8-9" OR $l2=="T7-8, T8-9, T9-10" OR $l2=="T8-9, T9-10, T10-11" OR $l2=="T9-10, T10-11, T11-12" OR $l2=="T10-11, T11-12, T12-L1")
{
echo "1. Thoracic spondylosis<br>2. Thoracic pain<br><br>";
}
elseif ($l3=="L1-2, L2-3, L3-4" OR $l3=="L2-3, L3-4, L4-5" OR $l3=="L3-4, L4-5, L5-S1" OR $l3=="L3-4, L4-5" OR $l3=="L4-5, L5-S1" OR $l3=="L5-S1")
{
echo "1. Lumbar spondylosis<br>2. Lumbago<br><br>";
}
?>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
echo $p;
echo "&nbsp;";
echo "$l1$l2$l3";
echo " facet injection under fluoroscopy";
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
if ($l1=="C2-3, C3-4, C4-5" OR $l1=="C3-4, C4-5, C5-6" OR $l1=="C4-5, C5-6, C6-7" OR $l1=="C5-6, C6-7, C7-T1")
{
echo "neck pain clinically secondary to cervical spondylosis and cervical ";
}
elseif ($l2=="T1-2, T2-3, T3-4" OR $l2=="T2-3, T3-4, T4-5" OR $l2=="T3-4, T4-5, T5-6" OR $l2=="T4-5, T5-6, T6-7" OR $l2=="T5-6, T6-7, T7-8" OR $l2=="T6-7, T7-8, T8-9" OR $l2=="T7-8, T8-9, T9-10" OR $l2=="T8-9, T9-10, T10-11" OR $l2=="T9-10, T10-11, T11-12" OR $l2=="T10-11, T11-12, T12-L1")
{
echo "back pain clinically secondary to thoracic spondylosis and thoracic";
}
elseif ($l3=="L1-2, L2-3, L3-4" OR $l3=="L2-3, L3-4, L4-5" OR $l3=="L3-4, L4-5, L5-S1" OR $l3=="L3-4, L4-5" OR $l3=="L4-5, L5-S1" OR $l3=="L5-S1")
{
echo "back pain clinically secondary to lumbar spondylosis and lumbar ";
}
?>
facet-mediated disorder who has failed more conservative measures. 
<?php
$history=$_POST["history"];
echo $history;
?>
 The patient is undergoing diagnostic and potentially therapeutic facet injections.<br><br>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a prone position on 
<?php
if ($l1=="C2-3, C3-4, C4-5" OR $l1=="C3-4, C4-5, C5-6" OR $l1=="C4-5, C5-6, C6-7" OR $l1=="C5-6, C6-7, C7-T1")
{
echo "a cervical position system so the neck has a slight flexed posture and the upper extremities were carefully placed by the patient's side and a gentle pull was utilized to lower the shoulders.";
}
elseif ($l2=="T1-2, T2-3, T3-4" OR $l2=="T2-3, T3-4, T4-5" OR $l2=="T3-4, T4-5, T5-6" OR $l2=="T4-5, T5-6, T6-7" OR $l2=="T5-6, T6-7, T7-8" OR $l2=="T6-7, T7-8, T8-9" OR $l2=="T7-8, T8-9, T9-10" OR $l2=="T8-9, T9-10, T10-11" OR $l2=="T9-10, T10-11, T11-12" OR $l2=="T10-11, T11-12, T12-L1" OR $l3=="L1-2, L2-3, L3-4" OR $l3=="L2-3, L3-4, L4-5" OR $l3=="L3-4, L4-5, L5-S1" OR $l3=="L3-4, L4-5" OR $l3=="L4-5, L5-S1" OR $l3=="L5-S1")
{
echo "the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis.";
}
?> Monitors were applied and the patient was monitored throughout the procedure.  The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to guide a 
<?php
if ($l1=="C2-3, C3-4, C4-5" OR $l1=="C3-4, C4-5, C5-6" OR $l1=="C4-5, C5-6, C6-7" OR $l1=="C5-6, C6-7, C7-T1")
{
echo "25-gauge spinal needle to the inferior pole of the ";
}
else
{
echo "22-gauge spinal needle to the inferior pole of the ";
}

if ($p=="Left")
{
echo "left ";
}
else if ($p=="Right" or $p=="Bilateral")
{
echo "right ";
}

if ($l1=="C5-6, C6-7, C7-T1") {echo "C7-T1";}
elseif ($l1=="C4-5, C5-6, C6-7") {echo "C6-7";}
elseif ($l1=="C3-4, C4-5, C5-6") {echo "C5-6";}
elseif ($l1=="C2-3, C3-4, C4-5") {echo "C4-5";}
elseif ($l2=="T1-2, T2-3, T3-4") {echo "T3-4";}
elseif ($l2=="T2-3, T3-4, T4-5") {echo "T4-5";}
elseif ($l2=="T3-4, T4-5, T5-6") {echo "T5-6";}
elseif ($l2=="T4-5, T5-6, T6-7") {echo "T6-7";}
elseif ($l2=="T5-6, T6-7, T7-8") {echo "T7-8";}
elseif ($l2=="T6-7, T7-8, T8-9") {echo "T8-9";}
elseif ($l2=="T7-8, T8-9, T9-10") {echo "T9-10";}
elseif ($l2=="T8-9, T9-10, T10-11") {echo "T10-11";}
elseif ($l2=="T9-10, T10-11, T11-12") {echo "T11-12";}
elseif ($l2=="T10-11, T11-12, T12-L1") {echo "T12-L1";}
elseif ($l3=="L1-2, L2-3, L3-4") {echo "L3-4";}
elseif ($l3=="L2-3, L3-4, L4-5" OR $l3=="L3-4, L4-5") {echo "L4-5";}
elseif ($l3=="L3-4, L4-5, L5-S1" OR $l3=="L4-5, L5-S1" OR $l3=="L5-S1") {echo "L5-S1";}

?>
 facet joint. After negative aspiration, 1ml of a solution containing 
<?php
if ($l3=="L5-S1" && $p!=="Bilateral")
{
echo "1mL of 40mg/mL of triamcinolone and 1mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact.";
}
elseif ($l3=="L5-S1" && $p=="Bilateral")
{
echo "1mL of 40mg/mL of triamcinolone and 1mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact. The procedure was repeated with the technique above on the contralateral side.";
}
elseif (($l3=="L3-4, L4-5" OR $l3=="L4-5, L5-S1") && $p!=="Bilateral")
{
echo "1mL of 40mg/mL of triamcinolone and 1mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact. The procedure was repeated with the technique above at the remaining level.";
}
elseif (($l3=="L3-4, L4-5" OR $l3=="L4-5, L5-S1") && $p=="Bilateral")
{
echo "2mL of 40mg/mL of triamcinolone and 2mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact. The procedure was repeated with the technique above at the remaining levels.";
}
elseif (($l2=="T1-2, T2-3, T3-4" OR $l2=="T2-3, T3-4, T4-5" OR $l2=="T3-4, T4-5, T5-6" OR $l2=="T4-5, T5-6, T6-7" OR $l2=="T5-6, T6-7, T7-8" OR $l2=="T6-7, T7-8, T8-9" OR $l2=="T7-8, T8-9, T9-10" OR $l2=="T8-9, T9-10, T10-11" OR $l2=="T9-10, T10-11, T11-12" OR $l2=="T10-11, T11-12, T12-L1" OR $l3=="L1-2, L2-3, L3-4" OR $l3=="L2-3, L3-4, L4-5" OR $l3=="L3-4, L4-5, L5-S1") AND ($p!=="Bilateral"))
{
echo "1mL of 40mg/mL of triamcinolone and 2mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact. The procedure was repeated with the techniques above at the remaining levels.";
}
elseif (($l2=="T1-2, T2-3, T3-4" OR $l2=="T2-3, T3-4, T4-5" OR $l2=="T3-4, T4-5, T5-6" OR $l2=="T4-5, T5-6, T6-7" OR $l2=="T5-6, T6-7, T7-8" OR $l2=="T6-7, T7-8, T8-9" OR $l2=="T7-8, T8-9, T9-10" OR $l2=="T8-9, T9-10, T10-11" OR $l2=="T9-10, T10-11, T11-12" OR $l2=="T10-11, T11-12, T12-L1" OR $l3=="L1-2, L2-3, L3-4" OR $l3=="L2-3, L3-4, L4-5" OR $l3=="L3-4, L4-5, L5-S1") AND $p=="Bilateral")
{
echo "2mL of 40mg/mL of triamcinolone and 4mL of 0.25% bupivacaine was incrementally injected. The needle was removed intact. The procedure was repeated with the technique above at the remaining levels.";
}
elseif (($l1=="C5-6, C6-7, C7-T1" OR $l1=="C4-5, C5-6, C6-7" OR $l1=="C3-4, C4-5, C5-6" OR $l1=="C2-3, C3-4, C4-5") AND ($p!=="Bilateral"))
{
echo "1mL of 10mg/mL of dexamethasone and 2mL of 1% lidocaine was incrementally injected. The needle was removed intact. The procedure was repeated with the techniques above above at the remaining levels.";
}
elseif (($l1=="C5-6, C6-7, C7-T1" OR $l1=="C4-5, C5-6, C6-7" OR $l1=="C3-4, C4-5, C5-6" OR $l1=="C2-3, C3-4, C4-5") AND $p=="Bilateral")
{
echo "1mL of 10mg/mL of dexamethasone and 5mL of 1% lidocaine was incrementally incrementally injected. The needle was removed intact. The procedure was repeated with the technique above at the remaining levels.";
}
?> The patient's skin was then cleaned.<br><br>The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
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
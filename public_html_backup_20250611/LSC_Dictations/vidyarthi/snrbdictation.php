<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$p=$_POST["side"];
$l=$_POST["location"];

if ($l=="")
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=snrb.php\">";
}
elseif ($l!="")
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
<h1>Procedure Dictation</h1>
<table>
<tr>
<td width=250><b>Attending Physician:<br><br></b></td><td width=450>Janak Vidyarthi, M.D.<br><br></td>
</tr>
<tr>
<td><b>Pre-Operative Diagnosis:<br><br><br></b></td><td>
<?php
if (in_array("C3", $l) OR in_array("C4", $l) OR (in_array("C5", $l)) OR (in_array("C6", $l)) OR (in_array("C7", $l)) OR (in_array("C8", $l)))
{
  echo "1. Cervical disk displacement<br>2. Cervical radiculitis";
}
elseif (in_array("L1", $l) OR (in_array("L2", $l)) OR (in_array("L3", $l)) OR (in_array("L4", $l)) OR (in_array("L5", $l)) OR (in_array("S1", $l)))
{
  echo "1. Lumbar disk displacement<br>2. Lumbar radiculitis";
}
elseif (in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l))
{
  echo "1. Thoracic disk displacement<br>2. Thoracic radiculitis";
}
?>
<br><br>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br><br></td><td>
<?php
if (in_array("C3", $l) OR in_array("C4", $l) OR (in_array("C5", $l)) OR (in_array("C6", $l)) OR (in_array("C7", $l)) OR (in_array("C8", $l)))
{
  echo "1. Cervical disk displacement<br>2. Cervical radiculitis";
}
elseif (in_array("L1", $l) OR (in_array("L2", $l)) OR (in_array("L3", $l)) OR (in_array("L4", $l))OR (in_array("L5", $l))OR (in_array("S1", $l)))
{
  echo "1. Lumbar disk displacement<br>2. Lumbar radiculitis";
}
elseif (in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l))
{
  echo "1. Thoracic disk displacement<br>2. Thoracic radiculitis";
}
?>
<br><br>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br>
<?php
if ($p=="Bilateral" AND count($l)>1 OR count($l)>2)
{ echo "<br></td><td>"; }
else
{ echo "</td><td>"; }

echo $p;
echo "&nbsp;";
echo "".implode(", ",$l)." selective nerve root block under fluoroscopy";
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
<td colspan=2>A pleasant patient presents with 
<?php
if (in_array("C4", $l) OR (in_array("C5", $l)) OR (in_array("C6", $l)) OR (in_array("C7", $l)) OR (in_array("C8", $l)))
{
  echo "cervical ";
}
elseif (in_array("L1", $l) OR (in_array("L2", $l)) OR (in_array("L3", $l)) OR (in_array("L4", $l))OR (in_array("L5", $l))OR (in_array("S1", $l)))
{
  echo "lumbar ";
}
elseif (in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l))
{
  echo "thoracic ";
}
?>
degenerative disk disease and radicular symptoms affecting the 
<?php
if ($p=="Right")
{
  echo "right ";
}
elseif ($p=="Left")
{
  echo "left ";
}
elseif ($p=="Bilateral")
{
  echo "bilateral ";
}
if (in_array("C4", $l) OR (in_array("C5", $l)) OR (in_array("C6", $l)) OR (in_array("C7", $l) OR (in_array("C8", $l))))
{
  echo "upper ";
}
elseif (in_array("L1", $l) OR (in_array("L2", $l)) OR (in_array("L3", $l)) OR (in_array("L4", $l))OR (in_array("L5", $l))OR (in_array("S1", $l)))
{
  echo "lower ";
}
if ($p=="Bilateral" AND !(in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l)))
{
  echo "extremities.";
}
elseif ($p!="Bilateral" AND !(in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l)))
{
  echo "extremity.";
}
if ($p=="Bilateral" AND (in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l)))
{
  echo "flanks/thorax.";
}
elseif ($p!="Bilateral" AND (in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l)))
{
  echo "flank/thorax.";
}

$history=$_POST["history"];
echo "&nbsp;";
echo $history;
?> The patient has failed more conservative measures and is ongoing diagnostic and potentially therapeutic selective nerve root block.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a prone position 
<?php
if (in_array("C4", $l) OR (in_array("C5", $l)) OR (in_array("C6", $l)) OR (in_array("C7", $l)) OR (in_array("C8", $l)))
{
echo "a cervical position system so the neck has a slight flexed posture and the upper extremities were carefully placed by the patient's side and a gentle pull was utilized to lower the shoulders.";
}
elseif (in_array("L1", $l) OR (in_array("L2", $l)) OR (in_array("L3", $l)) OR (in_array("L4", $l)) OR (in_array("L5", $l)) OR (in_array("S1", $l)) OR (in_array("T3", $l)) OR (in_array("T4", $l)) OR (in_array("T5", $l)) OR (in_array("T6", $l)) OR (in_array("T7", $l)) OR (in_array("T8", $l)) OR (in_array("T9", $l)) OR (in_array("T10", $l)) OR (in_array("T11", $l)) OR (in_array("T12", $l)))
{
echo "the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis.";
}
?> Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to guide a 
<?php
if (in_array("C4", $l) OR (in_array("C5", $l)) OR (in_array("C6", $l)) OR (in_array("C7", $l)) OR (in_array("C8", $l)))
{
echo "25";
}
else
{
echo "22";
}
?>-gauge spinal needle towards the 
<?php
if ($p=="Bilateral" or $p=="Right")
{
echo "right ";
}
else
{
echo "left ";
}
if (in_array("C4", $l) OR (in_array("C5", $l)) OR (in_array("C6", $l)) OR (in_array("C7", $l)) OR (in_array("C8", $l)))
{
echo reset($l);
}
elseif (in_array("T3", $l) OR in_array("T4", $l) OR in_array("T5", $l) OR in_array("T6", $l) OR in_array("T7", $l) OR in_array("T8", $l) OR in_array("T9", $l) OR in_array("T10", $l) OR in_array("T11", $l) OR in_array("T12", $l))
{
echo end($l);
}
else
{
echo $first_value = reset($l);
}
?>
 nerve root and its corresponding foramen. After negative aspiration, needle tip location was confirmed with Omnipaque 240mg/Ml, less than 0.5mL used. No intravascular uptake was seen. Then, 1ml of a solution containing 1mL of 10mg/mL of dexamethasone and 
<?php
if ($p=="Bilateral")
{
echo count($l)*2-1;
}
elseif (count($l)-1==0)
{
echo 1;
}
elseif (count($l)-1)
{
echo count($l)-1;
}
?>
mL of 1% lidocaine was incrementally injected. The needle was removed intact.
<?php
if (count($l)==1 AND $p!="Bilateral")
{
echo "";
}
elseif (count($l)==2 AND $p!="Bilateral")
{
echo "The procedure was repeated with the technique above at the remaining nerve root.";
}
elseif (count($l)==1 AND $p=="Bilateral")
{
echo "The procedure was repeated with the technique above at the remaining nerve root.";
}
elseif (count($l)>1 AND $p=="Bilateral")
{
echo "The procedure was repeated with the technique above at the remaining nerve roots.";
}
elseif (count($l)>2 AND $p!="Bilateral")
{
echo "The procedure was repeated with the technique above at the remaining nerve roots.";
}
$rp=$_POST["rpara"];
$lp=$_POST["lpara"];
if (empty($rp) AND empty($lp))
{
echo " There were no paresthesias on needle placement or injection at any level. The patient's skin was then cleaned.";
}
elseif (!empty($rp) AND empty($lp))
{
echo " Throughout the entire procedure, paresthesias were only elicited on needle placement at right ";
echo "".implode(", ",$rp)." only. This resolved after repositioning of the needle tip. There were no paresthesias on injection. At the end of the procedure, the patient's skin was then cleaned.";
}
elseif (empty($rp) AND !empty($lp))
{
echo " Throughout the entire procedure, paresthesias were only elicited on needle placement at left ";
echo "".implode(", ",$lp)." only. This resolved after repositioning of the needle tip. There were no paresthesias on injection. At the end of the procedure, the patient's skin was then cleaned.";
}
elseif (!empty($rp) AND !empty($lp))
{
echo " Throughout the entire procedure, paresthesias were only elicited on needle placement at right ";
echo "".implode(", ",$rp)." and left ".implode(", ",$lp)." only. This resolved after repositioning of the needle tip. There were no paresthesias on injection. At the end of the procedure, the patient's skin was then cleaned.";
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
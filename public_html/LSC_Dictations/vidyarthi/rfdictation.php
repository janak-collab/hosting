<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$name=$_POST['pname'];
$dob=$_POST['dob'];
$p=$_POST["side"];
$l=$_POST["location"];
$sensation=$_POST["sensation"];

if ($name=="" OR $p=="" OR $l=="")
{
echo "<meta http-equiv=\"refresh\" content=\"0;URL=rf.php\">";
}
elseif ($name!="" AND $p!="" AND $l!="")
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
<td width=250><b>Attending physician:<br><br></b></td><td>Janak Vidyarthi, M.D.<br><br></td>
</tr>
<tr>
<td><b>Pre-operative diagnosis:<br><br><br></b></td><td>
<?php
if ($l=="L4, L5")
{
echo "1. Lumbago<br>2. Lumbar spondylosis";
}
elseif ($l=="knee")
{
echo "1. Pain in ";
echo lcfirst($p);
echo " knee<br>2. Osteoarthritis of ";
echo lcfirst($p);
echo " knee";
}
?>
<br><br>
</td>
</tr>
</tr>
<tr>
<td><b>Post-operative diagnosis:</b><br><br><br></td><td>
<?php
if ($l=="L4, L5")
{
echo "1. Lumbago<br>2. Lumbar spondylosis";
}
elseif ($l=="knee")
{
echo "1. Pain in ";
echo lcfirst($p);
echo " knee<br>2. Osteoarthritis of ";
echo lcfirst($p);
echo "knee";
}
?>
<br><br></td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br><br>
<?php
if ($l=="knee")
{
echo "<br>";
}
?>
</td><td>
<?php
if ($l=="L4, L5")
{
echo "$p L4 medial branch and L5 dorsal ramus radiofrequency ablation<br><br>";
}
elseif ($l=="knee")
{
echo "1. $p superior lateral genicular nerve radiofrequency ablation<br>2. $p superior medial genicular nerve radiofrequency ablation<br>3. $p inferior medial genicular nerve radiofrequency ablation<br><br>";
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
if ($l=="L4, L5")
  {
  echo "low back pain";
  }
elseif ($l=="knee")
  {
  echo "knee pain";
  }
echo " clinically secondary to ";
if ($l=="L4, L5")
  {
  echo "lumbar spondylosis and lumbar facet-mediated disorder";
  }
elseif ($l=="knee")
  {
  echo "knee osteoarthritis";
  }
?>
 who has failed more conservative measures. The patient has undergone multiple diagnostic blocks with greater than 50% improvement in pain and functional status and is now undergoing radiofrequency ablation for the treatment of severe debilitating pain.<br><br></td>
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
if ($l=="L4, L5")
  {
  echo "prone position on the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis.";
  }
elseif ($l=="knee")
  {
  echo "supine position on the fluoroscopy table with a pillow under the ";
  echo lcfirst($p);
  echo " knee to facilitate access.";
  }

echo " Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>";

if ($l=="L4, L5")
  {
  echo "Using a caudal-lateral approach, fluoroscopy was used in AP, lateral, and oblique view to guide a 22-gauge 10mm curved tip radiofrequency cannula to the posterior junction of the transverse process and the superior articular process of the ";
echo lcfirst($p);
echo " L5 vertebral body. An RF cannula was placed using the techniques above with the needle tip position for the L5 dorsal ramus being placed at the notch between the superior articular process and the sacral ala.<br><br>";
  }
elseif ($l=="knee")
  {
  echo "Fluoroscopy was used in AP, lateral, and oblique view to guide a 22-gauge 10mm curved tip radiofrequency cannula to the ";
echo lcfirst($p);
echo " femoral lateral epicondyle in a para-sagittal orientation. RF cannulas were placed using the techniques above at the remaining sites with the needle tips at the femoral medial epicondyle and tibial medial epicondyle.<br><br>";
  }

if ($sensation!="motor")
{
echo "Sensory stimulation at 50Hz and 1v and m";
}
elseif ($sensation=="motor")
{
echo "M";
}

echo "otor stimulation at 2Hz and 2.5v was then performed. ";

if ($l=="L4, L5")
  {
  echo "No nerve root recruitment was elicited. ";
  }
elseif ($l=="knee")
  {
  echo "Muscle fasciculations were not elicited. ";
  }

echo " Next, the RF probes were removed with care taken not to disturb the RF cannulas. ";

if ($l=="CERVICAL LEVELS")
{ echo "0.25 mL";
}
elseif ($l=="L4, L5" OR $l=="knee")
{ echo "0.5mL";
}

echo " of 1% lidocaine was injected after negative aspirations to each site and the RF probes were replaced in the cannulas, again with care taken not to disturb the positioning. Fluoroscopic imaging prior and post lidocaine injection confirmed secured needle tip positions. Lesions were created at 80 degrees for 90 seconds. After the lesions were completed, ";

if ($l=="CERVICAL LEVELS")
{ echo "0.25mL of a solution containing 0.125mL of 1% lidocaine and 0.125";
}
elseif ($l=="L4, L5" OR $l=="knee")
{ echo "0.5mL of a solution containing 0.25mL of 1% lidocaine and 0.25";
}

echo "mL of 40mg/mL of triamcinolone was incrementally injected to each site. The cannulas were removed intact. ";
if ($l=="L4, L5")
{
echo "Throughout stimulation and lesioning, the patient denied sensations radiating to any extremity. Recruitment of motor nerve roots ";
}
elseif ($l=="knee")
{
echo "Throughout stimulation and lesioning, the patient felt only sensations around where the pre-burn paresthesia was felt. Recruitment of motor nerves ";
}
echo "were not seen.";
?>
<br><br>The patient's skin was then cleaned. The patient tolerated the procedure well without complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
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
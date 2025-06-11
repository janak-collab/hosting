<html>
	<head>
<link rel="stylesheet" type="text/css" href="lscdictation.css" />
	</head>
<?php
$name=$_POST['pname'];
$side=$_POST['side'];
$location=$_POST['location'];
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
<td><b>Pre-Operative Diagnosis:<br><br><br></b></td><td>1. Sacroiliitis<br>2. Sacroiliac joint disorder<br><br>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:<br><br><br></b></td><td>1. Sacroiliitis<br>2. Sacroiliac joint disorder<br><br>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
if ($side=="R" AND $location=="sij")
  {
  echo "Right sacroiliac joint injection under fluoroscopy";
  }
elseif ($side=="L" AND $location=="sij")
  {
  echo "Left sacroiliac joint injection under fluoroscopy";
  }
elseif ($side=="B" AND $location=="sij")
  {
  echo "Bilateral sacroiliac joint injection under fluoroscopy";
  }
if ($side=="R" AND $location=="sijdx")
  {
  echo "Right sacroiliac diagnostic block under fluoroscopy<br>";
  }
elseif ($side=="L" AND $location=="sijdx")
  {
  echo "Left sacroiliac diagnostic block under fluoroscopy";
  }
elseif ($side=="B" AND $location=="sijdx")
  {
  echo "Bilateral sacroiliac diagnostic block under fluoroscopy";
  }
?>
<br><br></td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>None.
<br><br></td>
</tr>
<tr>
<td><b>Indications for procedure:</b></td><td></td>
</tr>
<tr>
<td colspan=2>A pleasant patient presents with chronic low back and buttock pain secondary to sacroiliac joint disorder who has failed more conservative measures. 
<?php
$history=$_POST["history"];
echo $history;
?>
 The patient is undergoing 
<?php
if ($location=="sij")
{
    echo "diagnostic and potentially therapeutic sacroiliac joint injection.";
}
elseif ($location=="sijdx")
{
    echo "diagnostic sacroiliac joint injection in anticipation of radiofrequency ablation.";
}
?>
<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a prone position on the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to guide a 22-gauge spinal needle to the 
<?php
if ($location=="sij")
{
    echo "inferior pole of the ";
    if ($side=="L")
    {
        echo "left ";
    }
    elseif ($side=="R" OR $side=="B")
    {
        echo "right ";
    }
    echo "sacroiliac joint. With negative aspirations, needle tip location was confirmed with Omnipaque 240mg/mL, less than 0.5mL was used. Then, after negative aspiration, 1mL of a solution containing 1mL of 40mg/mL of triamcinolone and 1mL of 0.25% bupivacaine was incrementally injected. The needle was withdrawn intact.";
    if ($side=="B")
    {
        echo " The procedure was repeated with the technique above on the contralateral side.";
    }
}
elseif ($location=="sijdx")
{
    echo "notch between the superior articular process and the sacral ala on the ";
    if ($side=="L")
    {
        echo "left ";
    }
    elseif ($side=="R" OR $side=="B")
    {
        echo "right ";
    }
    echo "side to target the L5 dorsal ramus. After negative aspirations, 0.5mL of 0.25% bupivacaine was incrementally injected. The needle was withdrawn intact. Similarly, a 22-gauge spinal needle was then placed just lateral to the foramen in the ";
    if ($side=="L")
    {
        echo "9";
    }
    elseif ($side=="R" OR $side=="B")
    {
        echo "3 ";
    }
    echo " o'clock position at the corresponding S1, S2, and S3 foramen to target the lateral branches with 0.5mL of 0.25% bupivacaine being injected at each target site after negative aspiration. The needle was withdrawn intact at each level.";
    if ($side=="B")
    {
        echo " The procedure was repeated with the techniques above on the contralateral side using the lateral 9 o'clock position to target the S1, S2, S3 lateral branches.";
    }
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
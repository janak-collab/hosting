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
<td><b>Attending Physician:<br><br></b></td><td>Janak Vidyarthi, M.D.<br><br></td>
</tr>
<tr>
<td><b>Pre-Operative Diagnosis:<br><br></b></td><td>
<?php
$l1=$_POST["location1"];
$l2=$_POST["location2"];
$l3=$_POST["location3"];

if ($l1!="")
  {
  echo "Cervical";
  }
elseif ($l2!="")
  {
  echo "Thoracic";
  }
elseif ($l3!="")
  {
  echo "Lumbar";
  }
?> disk displacement<br><br>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br></td><td>
<?php
if ($l1!="")
  {
  echo "Cervical";
  }
elseif ($l2!="")
  {
  echo "Thoracic";
  }
elseif ($l3!="")
  {
  echo "Lumbar";
  }
?> disk displacement<br><br>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
echo $l1;
echo $l2;
echo $l3;

if ($l1!="")
  {
  echo " cervical";
  }
elseif ($l2!="")
  {
  echo " thoracic";
  }
elseif ($l3!="")
  {
  echo " lumbar";
  }
?> epidural steroid injection under fluoroscopy<br><br></td>
</tr>
<tr>
<td><b>Anesthesia:</b><br><br></td><td>
<?php
$s=$_POST["sedation"];
if ($s=="yes")
  {
  echo "Conscious sedation and local anesthetics";
  }
else
  {
  echo "Local anesthetics";
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
if ($l1!="")
  {
  echo "cervical";
  }
elseif ($l2!="")
  {
  echo "thoracic";
  }
elseif ($l3!="")
  {
  echo "lumbar";
  }
?>
 degenerative disk disease and complaints of 
<?php
if ($l1!="")
  {
  echo "upper extremity";
  }
elseif ($l2!="")
  {
  echo "chest/flank";
  }
elseif ($l3!="")
  {
  echo "lower extremity";
  }
?> radicular symptoms. 
<?php
$history=$_POST["history"];
echo $history;
?>
 The patient is undergoing a diagnostic and potentially therapeutic epidural steroid injection having failed more conservative measures.<br><br></td>
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
if ($l1=="C7-T1")
{
echo "a cervical position system so the neck has a slight flexed posture and the upper extremities were carefully placed by the patient's side and a gentle pull was utilized to lower the shoulders.";
}
elseif ($l2!="" OR $l3!="")
{
echo "the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis.";
}
?> Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>
<?php
if ($l3=="Caudal")
  {
  echo 'Fluoroscopy was used in a lateral position to visualize the maximum approach into the epidural space. A 27-gauge 1.25" needle was used to anesthetize the skin using 2.5mL of 1% lidocaine. An 18-gauge epidural needle was advanced through the sacral hiatus. ';
  }
else
  {
  echo "Fluoroscopy was used in AP, lateral, and oblique view to visualize the maximum approach into the $l1$l2$l3";
  echo ' epidural space. A 27-gauge 1.25" needle was used to anesthetize the skin using 2.5mL of 1% lidocaine. ';
  }
if ($l3!="Caudal")
  {
echo "Loss of resistance technique was used to access the epidural space using an 18-gauge epidural needle.";
  }
echo " After negative aspiration for heme or CSF, needle tip position was confirmed  with Omnipaque 240mg/mL, less than 0.5mL was used. Then, after negative aspiration for heme or CSF, a solution containing ";

if ($l1=="C7-T1")
  {
echo " 0.5mL of preservative saline ";
  }
else
  {
echo " 3mL of preservative saline, 1mL of 1% lidocaine ";
  }
?>
 and 1mL of 40mg/mL of triamcinolone was incrementally injected with intermittent aspiration. The needle was removed intact. The patient's skin was then cleaned. The patient tolerated the procedure well without immediate complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
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
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
<td><b>Pre-Operative Diagnosis:<br><br></b></td><td>
<?php
$l=$_POST["location"];
if ($l=="leftcrps")
  {
  echo "CRPS of left";
  }
elseif ($l=="rightcrps")
  {
  echo "CRPS of right";
  }
elseif ($l=="leftperiph")
  {
  echo "Polyneuropathy of left, neuralgia and neuritis";
  }
elseif ($l=="rightperiph")
  {
  echo "Polyneuropathy of right, neuralgia and neuritis";
  }
?> lower extremity<br><br>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br></td><td>
<?php
if ($l=="leftcrps")
  {
  echo "CRPS of left";
  }
elseif ($l=="rightcrps")
  {
  echo "CRPS of right";
  }
elseif ($l=="leftperiph")
  {
  echo "Polyneuropathy of left, neuralgia and neuritis";
  }
elseif ($l=="rightperiph")
  {
  echo "Polyneuropathy of right, neuralgia and neuritis";
  }
?> lower extremity<br><br>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
if ($l=="leftcrps" OR $l=="leftperiph")
  {
  echo "Left";
  }
elseif ($l=="rightcrps" OR $l=="rightperiph")
  {
  echo "Right";
  }
?> lumbar sympathetic block under fluoroscopy<br><br></td>
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
if ($l=="leftcrps")
  {
  echo "CRPS of left";
  }
elseif ($l=="rightcrps")
  {
  echo "CRPS of right";
  }
elseif ($l=="leftperiph")
  {
  echo "neuralgia, neuritis, and polyneuropathy of left";
  }
elseif ($l=="rightperiph")
  {
  echo "neuralgia, neuritis, and polyneuropathy of right";
  }
?> lower extremity causing significant pain and morbidity. 
<?php
$history=$_POST["history"];
echo $history;
?>
 The patient is undergoing a diagnostic and potentially therapeutic lumbar sympathetic block having failed more conservative measures.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a prone position on the fluoroscopy table with a pillow under the abdomen to facilitate reduction of lordosis. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to visualize the L2 vertebral body. The inferior endplate of L2 was "squared". An ipsilateral oblique view was obtained such that the 
<?php
if ($l=="leftcrps" OR $l=="leftperiph")
  {
  echo "left";
  }
elseif ($l=="rightcrps" OR $l=="rightperiph")
  {
  echo "right";
  }
?> transverse process of the L2 vertebral was just buried within the lateral edge of the vertebral body on fluoroscopy. A target site caudal to the transverse process to aim towards the waist of the L2 vertebral body was identified.

2ml of 1% lidocaine was injected as local anesthetics. A 22-gauge spinal needle with a curved-tip was advanced to the target site. Once os was contacted, aspirations were confirmed to be negative. Then 1mL of 1% lidocaine was injected for comfort. The needle was walked off the vertebral body in an anterior-lateral position. In lateral view, the needle tip was confirmed to be within the anterior 1/3rd of the vertebral body.<br><br>After negative aspiration for heme or CSF, needle tip position was confirmed with live fluoroscopy using Omnipaque 240mg/mL, approximately 1.5mL used. Then, after negative aspiration for heme or CSF, a test dose of 5mL of 1% lidocaine was slowly and incrementally injected. The patient was frequently assessed for peri-oral numbness, metallic taste in the mouth, or tinnitus.<br><br>With the above symptoms being negative after 2 minutes, 10mL of 0.25% bupivacaine was slowly and incrementally injected with frequent assessments for peri-oral numbness, metallic taste in the mouth, or tinnitus. The patient denied the symptoms throughout the procedure.<br><br>The needle was removed intact. The patient's skin was then cleaned. The patient tolerated the procedure well without immediate complications and was taken to the recovery room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
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
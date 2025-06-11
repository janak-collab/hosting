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
if ($l=="leftCRPS")
  {
  echo "CRPS of left";
  }
elseif ($l=="rightCRPS")
  {
  echo "CRPS of right";
  }
elseif ($l=="leftperiph")
  {
  echo "Polyneuropathy of left";
  }
elseif ($l=="rightperiph")
  {
  echo "Polyneuropathy of right";
  }
?> upper extremity<br><br>
</td>
</tr>
<tr>
<td><b>Post-Operative Diagnosis:</b><br><br></td><td>
<?php
if ($l=="leftCRPS")
  {
  echo "CRPS of left";
  }
elseif ($l=="rightCRPS")
  {
  echo "CRPS of right";
  }
elseif ($l=="leftperiph")
  {
  echo "Polyneuropathy of left";
  }
elseif ($l=="rightperiph")
  {
  echo "Polyneuropathy of right";
  }
?> upper extremity<br><br>
</td>
</tr>
<tr>
<td><b>Procedure performed:</b><br><br></td><td>
<?php
if ($l=="leftCRPS" OR $l=="leftperiph")
  {
  echo "Left";
  }
elseif ($l=="rightCRPS" OR $l=="rightperiph")
  {
  echo "Right";
  }
?> stellate ganglion block under fluoroscopy<br><br></td>
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
if ($l=="leftCRPS")
  {
  echo "CRPS of left";
  }
elseif ($l=="rightCRPS")
  {
  echo "CRPS of right";
  }
elseif ($l=="leftperiph")
  {
  echo "polyneuropathy of left";
  }
elseif ($l=="rightperiph")
  {
  echo "polyneuropathy of right";
  }
?>
 upper extremity causing significant pain and morbidity. 
<?php
$history=$_POST["history"];
echo $history;
?>
 The patient is undergoing a diagnostic and potentially therapeutic stellate ganglion block having failed more conservative measures.<br><br></td>
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
<td colspan=2>The patient was taken to the fluoroscopy suite and placed in a supine position with the neck slightly extended and the head rotated slightly to the 
<?php
if ($l=="leftCRPS" OR $l=="leftperiph")
  {
  echo "right";
  }
if ($l=="rightCRPS" OR $l=="rightperiph")
  {
  echo "left";
  }
?>. Monitors were applied and the patient was monitored throughout the procedure. The skin at the area of interest was prepped in
usual sterile fashion. Sterile technique was maintained for the entire procedure.<br><br>Fluoroscopy was used in AP, lateral, and oblique view to visualize the C6-7 disc. An ipsilateral oblique rotation was utilized to allow
adequate visualization of the neural foramina. A 25-gauge 3.5" curved-tip needle was slowly advanced until it contacted os at the base of the C7 uncinate process between the junction between the uncinate process and the vertebral body. After negative aspiration for heme or CSF, needle tip position was confirmed with Omnipaque 240mg/mL, approximately 1.5mL was used. Then, after negative aspiration for heme or CSF, a test dose of 0.5mL of 1% lidocaine was injected. The patient denied ringing in the ears, metallic taste in the mouth, or numbness in the lips. After negative aspiration for heme or CSF, 5mL of 1% lidocaine was incrementally injected. The needle was removed intact. The patient maintained meaningful verbal responses throughout. The patient's skin was then cleaned. The patient tolerated the procedure well without immediate complications and was taken to the recovery
room for further observation and planned discharged when appropriate criteria is met.<br><br></td>
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
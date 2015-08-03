<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>
<div id="result"></div>
<script language="javascript" type="application/javascript">
if(typeof(Storage)!=="undefined") {
  localStorage.<?=$cookie_name?>="<?=$cookie_data?>";
 // document.getElementById("result").innerHTML="Last name: " + localStorage.<?=$cookie_name?>;
 window.location='<?=$url?>';
}
</script>
</body>
</html>

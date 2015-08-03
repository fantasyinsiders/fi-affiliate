<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Example IFramed Code</title>
<script language="javascript" type="text/javascript">
function calcHeight($h)
{
  //find the height of the internal page
  var the_height=
    document.getElementById($h).contentWindow.
      document.body.scrollHeight;

  //change the height of the iframe
  document.getElementById($h).height=
      the_height;
}

</script>
<style>
	body { font-family:Tahoma, Geneva, sans-serif; }
	.container { width:980px; margin: auto; padding: 1em; border: 1px solid #ccc; box-shadow: 0px 2px 3px rgb(204, 204, 204);}
	
</style>

</head>

<body>



<div class="container">

	<h1>Header</h1>
		
    <iframe frameborder="0" src ="/jam2/" id="forum_frame" onLoad="calcHeight('forum_frame');" scrolling="NO" width="980" height="10" marginwidth="970"> </iframe>
	
    <h2>Footer</h2>

</div>        

</body>
</html>
<?php header("HTTP/1.1 404 Not Found"); ?>
<html>
<head>
<title>404 Page Not Found</title>
<style type="text/css">

#content  {
text-transform: capitalize; 
margin: 6em; 
border: #FFCC00 1px dotted; 
padding: 3em; 
font-size: 14px; 
font-weight:bold; 
color: #C60000; 
background-color: #FFDFDF; 
font-family: tahoma, arial;
}

h1 {
font-weight:		normal;
font-size:			14px;
font-weight:bold; 
color:				#C60000;
margin: 			0 0 4px 0;
}

a {
	color: #C60000;
}

</style>
</head>
<body>
	<div id="content">
    	
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
    <div style="clear:both"></div>
</body>
</html>
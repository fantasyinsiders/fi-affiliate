<html>
<head>
<title><?php echo $heading; ?></title>
<style type="text/css">

#content  {
	text-transform: capitalize; 
	margin: 6em; 
	border: #FFCC00 1px dotted; 
	padding: 3em; 
	font-size: 18px;  
	color: #fff; 
	background-color: #C00; 
	font-family: tahoma, arial;

	border-radius:5px;
	border:1px solid #ddd;
	-moz-box-shadow: 0px 4px 6px #666;
	-webkit-box-shadow: 0px 4px 6px #666;
	box-shadow: 0px 4px 6px #ccc;

}

h1 {
  font-weight:		normal;
  font-size:			22px;
  font-weight:bold; 
  color:				#fff;
  margin: 			0 0 4px 0;
}

a {
	color: #fff;
}

</style>
</head>
<body>
	<div id="content">	 
        <h1><?php echo $heading; ?></h1>
            <?php echo $message; ?>		
      		<a href="javascript:history.go(-1)">Go Back</a>
    </div>
    <div style="clear:both"></div>
</body>
</html>
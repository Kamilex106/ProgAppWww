<?php
 error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

 if($_GET['idp'] == '') $strona = 'html/glowna.html';
 if($_GET['idp'] == '2') $strona = 'html/2.html';
 if($_GET['idp'] == '3') $strona = 'html/3.html';
 if($_GET['idp'] == '4') $strona = 'html/4.html';
 if($_GET['idp'] == '5') $strona = 'html/5.html';
 if($_GET['idp'] == '6') $strona = 'html/6.html';
 if($_GET['idp'] == '7') $strona = 'html/7.html';
 if($_GET['idp'] == '8') $strona = 'html/8.html';
 if($_GET['idp'] == 'filmy') $strona = 'html/filmy.html';


?>


<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta name="Author" content="Kamil Leleniewski">
	<meta name="description" content="Przegląd wybranych zagadnień związanych z komputerami">
	<title>Komputer moją pasją</title>
	<link rel="stylesheet" href="css/style.css">
	<script src="js/timedate.js"></script>

	<?php

	?>
	<script src="js/kolorujtlo.js"></script>
	<script src="js/zmianaslajdu.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
		function myFunction() {
  		var x = document.getElementById("myTopnav");
  		if (x.className === "topnav") {x.className += " responsive";} 
		else {x.className = "topnav";}}
	</script>

</head>


<body onload="startclock();zmienslajd()">

	<div class="container">
		<header>
			<div class="row1">
				<div id="logo">
					<h1>Komputer moją pasją </h1>
				</div>
				
				<div class="info">
					<div style="text-align: center;" id="data"></div>
					<div style="text-align: center;" id="zegarek"></div>
					v.1.4
				</div>
			</div>
			
			
			<div class="topnav" id="myTopnav">
				<a href="index.php?idp=" class="active">Główna</a>
				<a href="index.php?idp=2">Historia komputerów</a>
				<a href="index.php?idp=3">Systemy operacyjne</a>
				<a href="index.php?idp=4">Języki programowania</a>
				<a href="index.php?idp=5">Historia Internetu</a>
				<a href="index.php?idp=6">Kontakt</a>
				<a href="index.php?idp=7">JavaScript</a>
				<a href="index.php?idp=8">JQuery</a>
				<a href="index.php?idp=filmy">Filmy</a>
				<a href="javascript:void(0);" class="icon" onclick="myFunction()">
				  <i class="fa fa-bars"></i>
				</a>
			  </div>
			
		</header>

		<?php
			if (file_exists($strona) == false)
				{
					echo "The file not $file exists";
				}
			else include($strona);
		?>


		<footer>
			<div class="bottom">
				<i> Komputer moją pasją &copy; Kamil Leleniewski </i>
			</div>
		</footer>
	</div>


	<script src="js/powiekszenie.js"></script>

	<?php
 		$nr_indeksu = '169327';
 		$nrGrupy = 'ISI2';
 		echo 'Autor: Kamil Leleniewski '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
		
	?>

</body>
</html>
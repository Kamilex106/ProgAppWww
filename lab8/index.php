<?php
session_start();
if (!isset($_SESSION["is_logged"])) {
    $_SESSION["is_logged"] = 0; 
}
 error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

 if($_GET['idp'] == '') $strona = '1';
 if($_GET['idp'] == 'historia_komputerow') $strona = '2';
 if($_GET['idp'] == 'systemy') $strona = '3';
 if($_GET['idp'] == 'jezyki') $strona = '4';
 if($_GET['idp'] == 'historia_internetu') $strona = '5';
 if($_GET['idp'] == 'kontakt') $strona = '6';
 if($_GET['idp'] == 'js') $strona = '7';
 if($_GET['idp'] == 'jq') $strona = '8';
 if($_GET['idp'] == 'filmy') $strona = '9';
 if($_GET['idp'] == 'admin') $strona = '10';
 

include('cfg.php'); 
include('showpage.php');
include('./admin/admin.php');
include('contact.php');




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
					v.1.7
				</div>
			</div>
			
			
			<div class="topnav" id="myTopnav">
				<a href="index.php?idp=" class="active">Główna</a>
				<a href="index.php?idp=historia_komputerow">Historia komputerów</a>
				<a href="index.php?idp=systemy">Systemy operacyjne</a>
				<a href="index.php?idp=jezyki">Języki programowania</a>
				<a href="index.php?idp=historia_internetu">Historia Internetu</a>
				<a href="index.php?idp=kontakt">Kontakt</a>
				<a href="index.php?idp=js">JavaScript</a>
				<a href="index.php?idp=jq">JQuery</a>
				<a href="index.php?idp=filmy">Filmy</a>
				<a href="index.php?idp=admin">Panel administratora</a>
				<a href="javascript:void(0);" class="icon" onclick="myFunction()">
				  <i class="fa fa-bars"></i>
				</a>
			  </div>
			
		</header>

		<?php
			
			if($_GET['idp'] == 'admin')
			{
				echo(FormularzLogowania());
				PrzetwarzanieFormularza();
				if ($_SESSION["is_logged"] == 1)
				{
					include('./html/admin.html');
					if($_GET['action'] == 'list')
					{
						ListaPodstron();
					}
					if($_GET['action'] == 'add')
					{
						echo(DodajNowaPodstrone());
					}

				}
				
				PrzetwarzajEdycje();
				PrzetwarzajDodanie();

			}
			else{
			echo(PokazPodstrone($strona));
			}

			if($_GET['idp'] == 'kontakt')
			{
				echo(PokazKontakt());
			}
		

			 

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
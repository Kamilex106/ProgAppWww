<?php
session_start(); // Rozpoczęcie sesji

// Sprawdzenie, czy zmienna sesyjna "is_logged" jest ustawiona; jeśli nie, przypisanie wartości 0 (niezalogowany)
if (!isset($_SESSION["is_logged"])) {
    $_SESSION["is_logged"] = 0; 
}

// Ustawienie raportowania błędów 
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); 

// Pobranie wartości z $_GET 
$idp = isset($_GET['idp']) ? htmlspecialchars($_GET['idp'], ENT_QUOTES, 'UTF-8') : '';

// Przypisanie wartości do zmiennej `$strona` na podstawie wartości `$idp`
if ($idp == '') $strona = '1';
elseif ($idp == 'historia_komputerow') $strona = '2';
elseif ($idp == 'systemy') $strona = '3';
elseif ($idp == 'jezyki') $strona = '4';
elseif ($idp == 'historia_internetu') $strona = '5';
elseif ($idp == 'kontakt') $strona = '6';
elseif ($idp == 'js') $strona = '7';
elseif ($idp == 'jq') $strona = '8';
elseif ($idp == 'filmy') $strona = '9';
elseif ($idp == 'admin') $strona = '10';
elseif ($idp == 'kontakt_php') $strona = '11';

// Dołączanie plików konfiguracji i obsługi stron
include('cfg.php'); 
include('showpage.php');
include('./admin/admin.php');
include('contact2.php'); //contact - wersja standarowa, contact2 - wersja korzystająca z PHPmailer
include('sklep.php');
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
    <script src="js/kolorujtlo.js"></script>
    <script src="js/zmianaslajdu.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }
    </script>
</head>

<!-- Automatyczne uruchomienie zegara i slajdera po załadowaniu strony --> 
<body onload="startclock();zmienslajd()">
    <div class="container">
        <header>
            <div class="row1">
                <div id="logo">
                    <h1>Komputer moją pasją</h1>
                </div>
                
                <div class="info">
                    <div style="text-align: center;" id="data"></div>
                    <div style="text-align: center;" id="zegarek"></div>
                    v.1.8
                </div>
            </div>
            
            <!-- Menu nawigacyjne -->
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
                <a href="index.php?idp=kontakt_php">Kontakt PHP</a>
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </header>

        <?php
        $zarzadzaj = new ZarzadzajKategoriami($link);
        // Obsługa logiki dla panelu administratora
        if ($idp == 'admin') {
            echo FormularzLogowania(); // Wyświetlenie formularza logowania
            PrzetwarzanieFormularza(); // Obsługa przesłanych danych logowania

            // Jeśli użytkownik jest zalogowany
            if ($_SESSION["is_logged"] == 1) {
                echo PokazPodstrone($strona); // Wyświetlenie wybranej podstrony
                if (isset($_GET['action']) && $_GET['action'] == 'list') {
                    ListaPodstron(); // Wyświetlenie listy podstron
                }
                if (isset($_GET['action']) && $_GET['action'] == 'add') {
                    echo DodajNowaPodstrone(); // Formularz do dodania nowej podstrony
                }
                if (isset($_GET['action']) && $_GET['action'] == 'category_list') {
                    $zarzadzaj->PokazKategorie(); // Wyświetlenie listy podstron
                }
                if (isset($_GET['action']) && $_GET['action'] == 'category_add') {
                    $zarzadzaj->DodajKategorie(); // Formularz do dodania nowej podstrony
                }
            }
            // Przetwarzanie edycji i dodawania podstron
            PrzetwarzajEdycje();
            PrzetwarzajDodanie();
            
            $zarzadzaj->PrzetwarzajEdycjeKategorii();
        } else {
            // Wyświetlenie wybranej podstrony, jeśli nie jest to panel administratora
            echo PokazPodstrone($strona);
        }


		// Obsługa logiki dla strony "Kontakt PHP"
		if($_GET['idp'] == 'kontakt_php')
		{
	 	// Formularz przypomnienia hasła
		echo('<h2 class="heading">Przypomnij haslo:</h2>');
		echo('<form method="post" name="PasswordForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="text" name="email" id="email" class="formField" placeholder="Wpisz adres email"> 
		<br>
		<input type="submit" name="password_submit" class="remind_password" value="Przypomnij haslo">
	   </form>');
		if (isset($_POST['password_submit'])) {
			$email = htmlspecialchars($_POST['email']);
			PrzypomnijHaslo($email); // Funkcja do przypominania hasła
		}
		if (isset($_POST['contact_submit']))
		{
			$email = htmlspecialchars($_POST['email']);
			WyslijMailaKontakt($email); // Funkcja wysyłająca wiadomość kontaktową
		}
		else
		{
			echo(PokazKontakt()); // Wyświetlenie standardowego formularza kontaktowego
		}
		}

        ?>

        <footer>
            <div class="bottom">
                <i>Komputer moją pasją &copy; Kamil Leleniewski</i>
            </div>
        </footer>
    </div>

    <script src="js/powiekszenie.js"></script>

    <?php
    $nr_indeksu = '169327';
    $nrGrupy = 'ISI2';
    echo 'Autor: Kamil Leleniewski '.htmlspecialchars($nr_indeksu, ENT_QUOTES, 'UTF-8').' grupa '.htmlspecialchars($nrGrupy, ENT_QUOTES, 'UTF-8').'<br /><br />';
    ?>
</body>
</html>

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
elseif ($idp == 'sklep') $strona = '12';



// Dołączanie plików konfiguracji i obsługi stron
include('cfg.php'); 
include('showpage.php');
include('./admin/admin.php');
include('contact2.php'); //contact - wersja standarowa, contact2 - wersja korzystająca z PHPmailer
include('sklep_admin.php');
include('sklep_klient.php');
include('koszyk.php');


?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    <meta name="Author" content="Kamil Leleniewski">
    <meta name="description" content="Przegląd wybranych zagadnień związanych z komputerami">
    <title>Komputer moją pasją</title>
    <?php
    // Ładowanie odpowiedniego arkusza stylów w zależności od wartości $idp
    if (($idp != 'js'))
    echo('<link rel="stylesheet" href="css/style.css">');
    else echo('<link rel="stylesheet" href="css/stylejs.css">')
    ?>
    
    <script src="js/timedate.js"></script>
    <script src="js/kolorujtlo.js"></script>
    <script src="js/zmianaslajdu.js"></script>
    <script src="js/formularze.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        // Funkcja do obsługi responsywnego menu nawigacyjnego
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
<body onload="startclock();zmienslajd();changeBackground('#555555')">
    <div class="container">
        <header>
            <div class="row1">
                <div id="logo">
                    <h1>ㅤㅤㅤㅤㅤㅤKomputer moją pasją</h1>
                </div>
                
                <div class="info">
                    <div style="text-align: center;" id="data"></div>
                    <div style="text-align: center;" id="zegarek"></div>
                    <div style="text-align: center;" id="version">v.1.8</div>
                </div>
            </div>
            
            <!-- Menu nawigacyjne -->
            <div class="topnav" id="myTopnav">
                <a href="index.php?idp=" class="active">Główna</a>
                <a href="index.php?idp=historia_komputerow">Historia</a>
                <a href="index.php?idp=systemy">Systemy operacyjne</a>
                <a href="index.php?idp=jezyki">Języki programowania</a>
                <a href="index.php?idp=historia_internetu">Historia Internetu</a>
                <a href="index.php?idp=kontakt">Kontakt</a>
                <a href="index.php?idp=js">JavaScript</a>
                <a href="index.php?idp=jq">JQuery</a>
                <a href="index.php?idp=filmy">Filmy</a>
                <a href="index.php?idp=admin">Admin</a>
                <a href="index.php?idp=kontakt_php">Kontakt PHP</a>
                <a href="index.php?idp=sklep">Sklep</a>
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </header>

        <?php
        $zarzadzaj = new ZarzadzajKategoriami($link);
        $zarzadzaj2 = new ZarzadzajProduktami($link);
        $sklep = new Sklep($link);
        $kontakt = new Kontakt();
        $admin = new Admin($link);

        // Obsługa logiki dla panelu administratora
        if ($idp == 'admin') {
            echo $admin->FormularzLogowania(); // Wyświetlenie formularza logowania
            $admin->PrzetwarzanieFormularza(); // Obsługa przesłanych danych logowania

            // Jeśli użytkownik jest zalogowany
            if ($_SESSION["is_logged"] == 1) {
                echo PokazPodstrone($strona); // Wyświetlenie wybranej podstrony
                if (isset($_GET['action']) && $_GET['action'] == 'list') {
                    $admin->ListaPodstron(); // Wyświetlenie listy podstron
                }
                elseif (isset($_GET['action']) && $_GET['action'] == 'add') {
                    echo $admin->FormularzPodstrony(); // Formularz do dodania nowej podstrony
                }
                if (isset($_GET['action']) && strpos($_GET['action'], 'edit') === 0) {
                    // Obsługa edycji podstrony
                    echo $admin->FormularzPodstrony($_GET['action']); // Wyświetlenie formularza do edycji podstrony
                }
                elseif (isset($_GET['action']) && $_GET['action'] == 'category_list') {
                    $zarzadzaj->PokazKategorie(); // Wyświetlenie listy kategorii
                }
                elseif (isset($_GET['action']) && $_GET['action'] == 'category_add') {
                    $zarzadzaj->FormularzKategorie(); // Formularz do dodania nowej kategorii
                }
                elseif (isset($_GET['action']) && $_GET['action'] == 'product_list') {
                    $zarzadzaj2->PokazProdukty(); // Wyświetlenie listy produktów
                }
                elseif (isset($_GET['action']) && $_GET['action'] == 'product_add') {
                    $zarzadzaj2->FormularzProdukty(); // Formularz do dodania nowego produktu
                }
            }
            // Przetwarzanie edycji i dodawania podstron
            $admin->PrzetwarzajPodstrone();

            // Przetwarzanie edycji kategorii i produktów
            $zarzadzaj->PrzetwarzajKategorie();
            $zarzadzaj2->PrzetwarzajProdukty();
        } 


        elseif ($idp == 'sklep') {
            $koszyk = new Koszyk($link);  // Inicjalizacja obiektu Koszyk
            $sklep-> pokazPrzyciskiLogowaniaRejestracji(); // Wyświetlenie przycisków logowania i rejestracji dla klienta sklepu
            if (isset($_POST['koszyk'])) {
                $koszyk->pokazKoszyk($klient_id); // Wyświetlenie zawartości koszyka
            }

            elseif (isset($_POST['usun_produkt'])) {
                $koszyk_id = (int)$_POST['koszyk_id'];
                $koszyk->usunZKoszyka($koszyk_id); // Usunięcie produktu z koszyka
                $koszyk->pokazKoszyk($klient_id); // Ponowne wyświetlenie koszyka po usunięciu
                echo "Produkt usunięty z koszyka!";
            }

            elseif (isset($_POST['zmien_ilosc'])) {
                $koszyk_id = (int)$_POST['koszyk_id'];
                $nowa_ilosc = (int)$_POST['nowa_ilosc'];
                $koszyk->zmienIlosc($koszyk_id, $nowa_ilosc); // Zmiana ilości produktu w koszyku
                $koszyk->pokazKoszyk($klient_id); // Ponowne wyświetlenie koszyka po zmianie ilości
                echo "Ilość produktu zmieniona!";
            }

            elseif (isset($_GET['kategoria'])) {
                $kategoria_id = intval($_GET['kategoria']);
            
                // Wyświetl podkategorie dla wybranej kategorii
                echo '<h2>Podkategorie:</h2>';
                $sklep->PokazKategorie($kategoria_id);
            
                // Wyświetl produkty z tej kategorii
                echo '<h2>Produkty:</h2>';
                echo '<div class="produkty-container">';
                $sklep->PokazProduktyPoKategori($kategoria_id);
                echo '</div>';
            } else {
                // Wyświetl główne kategorie, jeśli nie wybrano żadnej
                echo '<h2>Kategorie główne:</h2>';
                $sklep->PokazKategorie();
            }
            $klient_id = $_SESSION['klient_id'];

        }

        else {
            // Wyświetlenie wybranej podstrony, jeśli nie jest to panel administratora lub sklep
            echo PokazPodstrone($strona);
        }

        // Obsługa dodawania produktu do koszyka
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['produkt_id'])) {
                $produkt_id = (int)$_POST['produkt_id'];
                if ($klient_id != null) {
                    
                    $koszyk->dodajDoKoszyka($klient_id, $produkt_id); // Dodanie produktu do koszyka dla zalogowanego klienta
                    echo "Produkt dodany do koszyka!";
                }
                else {
                    echo "Nie zalogowano";
                }

            }
        }

		// Obsługa logiki dla strony "Kontakt PHP"
		if($_GET['idp'] == 'kontakt_php')
		{
	 	// Formularz przypomnienia hasła
		echo($kontakt->PokazPrzypomnienieHasla()); // Wyświetlenie formularza przypomnienia hasła
        echo($kontakt->PokazKontakt()); // Wyświetlenie standardowego formularza kontaktowego
		if (isset($_POST['password_submit'])) {
			$email = htmlspecialchars($_POST['email']);
			$kontakt->PrzypomnijHaslo($email); // Funkcja do przypominania hasła
		}
		elseif (isset($_POST['contact_submit']))
		{
			$email = htmlspecialchars($_POST['email']);
			$kontakt->WyslijMailaKontakt($email); // Funkcja wysyłająca wiadomość kontaktową
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

<script>
/**
 * Funkcja dostosowująca pozycję stopki w zależności od wysokości zawartości strony.
 * Ta funkcja zapewnia, że stopka zawsze znajduje się na dole okna przeglądarki,
 * nawet jeśli zawartość strony nie wypełnia całego ekranu. Jeśli zawartość
 * jest wyższa niż okno przeglądarki, stopka pozostaje pod zawartością.
 */
window.addEventListener('load', adjustFooter);
window.addEventListener('resize', adjustFooter);

function adjustFooter() {
    const container = document.querySelector('.container');
    const footer = document.querySelector('.bottom');
    // Sprawdzenie, czy zarówno kontener, jak i stopka zostały znalezione na stronie.
    if (container && footer) {
        const contentHeight = container.offsetHeight; // Pobranie aktualnej wysokości kontenera, czyli wysokości całej zawartości strony.
        const windowHeight = window.innerHeight; // Pobranie aktualnej wysokości okna przeglądarki.

        // Porównanie wysokości zawartości z wysokością okna przeglądarki.
        if (contentHeight < windowHeight) {
            // Jeśli wysokość zawartości jest mniejsza niż wysokość okna,
            // ustaw margines górny stopki tak, aby wypełnić pozostałą przestrzeń.
            // Dzięki temu stopka zostanie "przypięta" do dołu okna.
            footer.style.marginTop = `${windowHeight - contentHeight}px`;
        } 
        else {
            // Jeśli wysokość zawartości jest większa lub równa wysokości okna,
            // resetowanie marginesu górnego stopki do zera.
            // W takim przypadku stopka będzie naturalnie umieszczona pod zawartością.
            footer.style.marginTop = '0';
        }
    }
}
</script>
</body>

</html>

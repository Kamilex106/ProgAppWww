<?php
include('cfg.php');  // Dołączamy plik konfiguracji, aby uzyskać dostęp do połączenia z bazą danych

class Sklep
{
    private $link;

    // Konstruktor, który będzie korzystał z połączenia z pliku cfg.php
    public function __construct($link)
    {
        // Połączenie z bazą danych (przekazane z pliku cfg.php)
        $this->link = $link;

        // Sprawdzenie połączenia
        if (mysqli_connect_errno()) {
            die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
        }
    }

    // Funkcja do wyświetlania kategorii w strukturze drzewa
    public function PokazKategorie($parent_id = 0, $level = 0)
    {
        // Przygotowanie zapytania SQL
        $query = "SELECT * FROM `kategorie` WHERE `matka` = $parent_id ORDER BY `id`";
        $result = mysqli_query($this->link, $query);

        // Sprawdzanie, czy zapytanie zwróciło wyniki
        if ($result && mysqli_num_rows($result) > 0) {
            // Iteracja przez wyniki zapytania
            while ($row = mysqli_fetch_assoc($result)) {
                $indentation = str_repeat('&nbsp;&nbsp;&nbsp;', $level);

                // Generowanie linku do kategorii
                echo $indentation . '<a href="index.php?idp=admin&action=shop&kategoria=' . $row['id'] . '">' . htmlspecialchars($row['nazwa']) . '</a><br>';

                // Rekursywne wywołanie dla dzieci bieżącej kategorii
                $this->PokazKategorie($row['id'], $level + 1);
            }
        }
    }

    // Funkcja do wyświetlania produktów po kliknięciu na kategorię
    public function PokazProduktyPoKategori($kategoria_id)
    {
        // Przygotowanie zapytania SQL do pobrania produktów z danej kategorii
        $query = "SELECT * FROM `produkty` WHERE `kategoria` = $kategoria_id ORDER BY `id` DESC";
        $result = mysqli_query($this->link, $query);

        // Sprawdzanie, czy są produkty w danej kategorii
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo 'ID: ' . $row['id'] . ' | Tytuł: ' . htmlspecialchars($row['tytul']) . '<br>';
                echo 'Opis: ' . htmlspecialchars($row['opis']) . '<br>';
                echo 'Cena: ' . $row['cena_netto'] . ' PLN<br>';
                echo '<img src="' . htmlspecialchars($row['zdjecie']) . '" alt="Zdjęcie produktu" style="max-width: 200px;"><br>';
                echo '<br>';
            }
        } else {
            echo 'Brak produktów w tej kategorii.';
        }
    }
}
?>

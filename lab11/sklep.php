<?php

// Klasa do zarządzania kategoriami w sklepie
class ZarzadzajKategoriami
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link; // Przechowywanie połączenia z bazą danych
    }

    // Funkcja dodająca nową kategorię
    public function DodajKategorie()
    {
        // Formularz HTML do dodawania kategorii
        $wynik = '
        <div class="dodawanie">
         <form method="post" name="AddCategoryForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <br>
            <input type="text" name="kategoria_nazwa" id="kategoria_nazwa" placeholder="Nazwa kategorii" required> 
            <br>
            <input type="text" name="matka" id="matka" placeholder="ID nadrzędnej kategorii (0 dla głównej)" required> 
            <br>
            <input type="submit" name="add_category_submit" class="add_category" value="Dodaj">
         </form>
        </div>
        ';

        echo $wynik;

        // Obsługa formularza
        if (isset($_POST['add_category_submit'])) {
            $kategoria_nazwa = htmlspecialchars(trim($_POST['kategoria_nazwa']));
            $matka = (int) $_POST['matka'];

            // Przygotowanie zapytania SQL
            $stmt = $this->link->prepare("INSERT INTO `kategorie` (`matka`, `nazwa`) VALUES (?, ?)");
            $stmt->bind_param("is", $matka, $kategoria_nazwa);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Funkcja usuwająca kategorię
    public function UsunKategorie($key)
    {
        $category_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID kategorii

        // Przygotowanie zapytania SQL
        $stmt = $this->link->prepare("DELETE FROM `kategorie` WHERE `id` = ? LIMIT 1");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $stmt->close();
    }

    // Funkcja edytująca kategorię
    public function EdytujKategorie($key)
    {
        $category_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID kategorii

        // Formularz HTML do edycji kategorii
        $wynik = '
        <div class="edytowanie">
         <form method="post" name="EditCategoryForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <br>
            <input type="text" name="kategoria_nazwa" id="kategoria_nazwa" placeholder="Nazwa kategorii" required> 
            <br>
            <input type="text" name="matka" id="matka" placeholder="ID nadrzędnej kategorii" required> 
            <br>
            <input type="hidden" name="category_id" value="' . $category_id . '">
            <input type="submit" name="edit_category_submit" class="edit_category" value="Edytuj">
         </form>
        </div>
        ';

        echo $wynik;
    }

    // Przetwarzanie edycji kategorii
    public function PrzetwarzajEdycjeKategorii()
    {
        if (isset($_POST['edit_category_submit'])) {
            $kategoria_nazwa = htmlspecialchars(trim($_POST['kategoria_nazwa']));
            $matka = (int) $_POST['matka'];
            $category_id = (int) $_POST['category_id'];

            // Przygotowanie zapytania SQL
            $stmt = $this->link->prepare("UPDATE `kategorie` SET `matka` = ?, `nazwa` = ? WHERE `id` = ? LIMIT 1");
            $stmt->bind_param("isi", $matka, $kategoria_nazwa, $category_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Wyświetlanie kategorii w strukturze drzewa
    public function PokazKategorie($parent_id = 0, $level = 0)
    {
        // Przygotowanie zapytania SQL
        $stmt = $this->link->prepare("SELECT * FROM `kategorie` WHERE `matka` = ? ORDER BY `id`");
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Iteracja przez wyniki zapytania
        while ($row = $result->fetch_assoc()) {
            $indentation = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
            echo($indentation . 'id:' . $row['id'] . ' ' . htmlspecialchars($row['nazwa']) . '
                <form method="post" style="display:inline;">
                    <input type="submit" name="category_delete' . $row['id'] . '" value="Usuń">
                    <input type="submit" name="category_edit' . $row['id'] . '" value="Edytuj">
                </form><br>');

            // Rekursywne wywołanie dla dzieci bieżącej kategorii
            $this->PokazKategorie($row['id'], $level + 1);
        }

        $stmt->close();

        // Obsługa akcji edycji i usuwania (tylko w pierwszym wywołaniu)
        if ($parent_id === 0 && !isset($_POST['edit_category_submit'])) {
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'category_delete') === 0) {
                    $this->UsunKategorie($key);
                }
                if (strpos($key, 'category_edit') === 0) {
                    $this->EdytujKategorie($key);
                }
            }
        }
    }
}

// Klasa do zarządzania produktami w sklepie
class ZarzadzajProduktami
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link; // Przechowywanie połączenia z bazą danych
    }

    // Funkcja dodająca nowy produkt
    public function DodajProdukty()
    {
        $wynik = '
        <div class="dodawanie">
         <form method="post" name="AddProductForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <br>
            <input type="text" name="tytul" placeholder="Tytuł produktu" required>
            <br>
            <textarea name="opis" placeholder="Opis produktu" required></textarea>
            <br>
            <input type="date" name="data_utworzenia" required 
                   onfocus="this.type=\'date\'" 
                   onblur="if(!this.value) this.type=\'text\'; this.placeholder=\'Data utworzenia\'" 
                   placeholder="Data utworzenia">
            <br>
            <input type="date" name="data_modyfikacji" required 
                   onfocus="this.type=\'date\'" 
                   onblur="if(!this.value) this.type=\'text\'; this.placeholder=\'Data modyfikacji\'" 
                   placeholder="Data modyfikacji">
            <br>
            <input type="date" name="data_wygasniecia" required 
                   onfocus="this.type=\'date\'" 
                   onblur="if(!this.value) this.type=\'text\'; this.placeholder=\'Data wygaśnięcia\'" 
                   placeholder="Data wygaśnięcia">
            <br>
            <input type="number" name="cena_netto" placeholder="Cena netto" required>
            <br>
            <input type="number" name="podatek_vat" placeholder="Podatek VAT" required>
            <br>
            <input type="number" name="ilosc" placeholder="Ilość w magazynie" required>
            <br>
            <input type="text" name="status" placeholder="Status dostępności" required>
            <br>
            <select name="kategoria">
                <!-- Generowanie opcji kategorii -->
                ' . $this->PobierzKategorie() . '
            </select>
            <br>
            <input type="text" name="gabaryt" placeholder="Gabaryt produktu" required>
            <br>
            <input type="file" name="zdjecie" placeholder="Zdjęcie produktu" required>
            <br>
            <input type="submit" name="add_product_submit" value="Dodaj produkt">
         </form>
        </div>';
    
    echo $wynik;
    

        if (isset($_POST['add_product_submit'])) {
            $this->PrzetwarzajDodawanieProduktow();
        }
    }

    // Obsługa danych z formularza dodawania produktu
    private function PrzetwarzajDodawanieProduktow()
    {
        $tytul = htmlspecialchars(trim($_POST['tytul']));
        $opis = htmlspecialchars(trim($_POST['opis']));
        $data_utworzenia = $_POST['data_utworzenia'];
        $data_modyfikacji = $_POST['data_modyfikacji'];
        $data_wygasniecia = $_POST['data_wygasniecia'];
        $cena_netto = (float) $_POST['cena_netto'];
        $podatek_vat = (int) $_POST['podatek_vat'];
        $ilosc = (int) $_POST['ilosc'];
        $status = htmlspecialchars(trim($_POST['status']));
        $kategoria = (int) $_POST['kategoria'];
        $gabaryt = htmlspecialchars(trim($_POST['gabaryt']));

        // Przesyłanie i walidacja zdjęcia
        $zdjecie = $_FILES['zdjecie'];
        $zdjecie_sciezka = 'uploads/' . basename($zdjecie['name']);
        move_uploaded_file($zdjecie['tmp_name'], $zdjecie_sciezka);

        // Zapytanie SQL do dodania produktu
        $stmt = $this->link->prepare("INSERT INTO produkty (tytul, opis, data_utworzenia, data_modyfikacji, data_wygasniecia, cena_netto, podatek_vat, ilosc, status, kategoria, gabaryt, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssiiissss", $tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie_sciezka);
        $stmt->execute();
        $stmt->close();
    }

    // Funkcja usuwająca produkt
    public function UsunProdukty($key)
    {
        $produkt_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID produktu

        // Przygotowanie zapytania SQL
        $stmt = $this->link->prepare("DELETE FROM `produkty` WHERE `id` = ? LIMIT 1");
        $stmt->bind_param("i", $produkt_id);
        $stmt->execute();
        $stmt->close();
    }

// Funkcja edytująca produkt
public function EdytujProdukty($key)
{
    $produkt_id = (int) preg_replace('/\D/', '', $key);
    $stmt = $this->link->prepare("SELECT * FROM produkty WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $produkt_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produkt = $result->fetch_assoc();
    $stmt->close();

    $wynik = '
    <div class="edytowanie">
     <form method="post" name="EditProductForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
    <br>
        <input type="hidden" name="product_id" value="' . $produkt_id . '">
        <input type="text" name="tytul" placeholder="Tytuł produktu" value="' . htmlspecialchars($produkt['tytul'], ENT_QUOTES) . '" required>
        <br>
        <textarea name="opis" placeholder="Opis produktu" required>' . htmlspecialchars($produkt['opis'], ENT_QUOTES) . '</textarea>
        <br>
        <input type="date" name="data_utworzenia" value="' . $produkt['data_utworzenia'] . '" required>
        <br>
        <input type="date" name="data_modyfikacji" value="' . $produkt['data_modyfikacji'] . '" required>
        <br>
        <input type="date" name="data_wygasniecia" value="' . $produkt['data_wygasniecia'] . '" required>
        <br>
        <input type="number" name="cena_netto" value="' . $produkt['cena_netto'] . '" required>
        <br>
        <input type="number" name="podatek_vat" value="' . $produkt['podatek_vat'] . '" required>
        <br>
        <input type="number" name="ilosc" value="' . $produkt['ilosc'] . '" required>
        <br>
        <input type="text" name="status" value="' . htmlspecialchars($produkt['status'], ENT_QUOTES) . '" required>
        <br>
        <select name="kategoria">' . $this->PobierzKategorie($produkt['kategoria']) . '</select>
        <br>
        <input type="text" name="gabaryt" value="' . htmlspecialchars($produkt['gabaryt'], ENT_QUOTES) . '" required>
        <br>
        <input type="file" name="zdjecie">
        <br>';

    if (!empty($produkt['zdjecie'])) {
        $wynik .= '<p>Aktualne zdjęcie: <img src="' . $produkt['zdjecie'] . '" alt="Zdjęcie produktu" style="max-width: 200px;"></p>';
    }

    $wynik .= '
        <input type="submit" name="edit_product_submit" value="Zapisz zmiany">
     </form>
    </div>';

    echo $wynik;

    if (isset($_POST['edit_product_submit'])) {
        $this->PrzetwarzajEdycjeProduktow();
    }
}

    // Przetwarzanie edycji produktu
    public function PrzetwarzajEdycjeProduktow()
    {
        $produkt_id = (int) $_POST['product_id'];
        $tytul = htmlspecialchars(trim($_POST['tytul']));
        $opis = htmlspecialchars(trim($_POST['opis']));
        $data_utworzenia = $_POST['data_utworzenia'];
        $data_modyfikacji = $_POST['data_modyfikacji'];
        $data_wygasniecia = $_POST['data_wygasniecia'];
        $cena_netto = (float) $_POST['cena_netto'];
        $podatek_vat = (int) $_POST['podatek_vat'];
        $ilosc = (int) $_POST['ilosc'];
        $status = htmlspecialchars(trim($_POST['status']));
        $kategoria = (int) $_POST['kategoria'];
        $gabaryt = htmlspecialchars(trim($_POST['gabaryt']));

        $zdjecie_sciezka = '';
        if (!empty($_FILES['zdjecie']['name'])) {
            $zdjecie = $_FILES['zdjecie'];
            $zdjecie_sciezka = 'uploads/' . basename($zdjecie['name']);
            move_uploaded_file($zdjecie['tmp_name'], $zdjecie_sciezka);
        }

        $stmt = $this->link->prepare("UPDATE produkty SET tytul = ?, opis = ?, data_utworzenia = ?, data_modyfikacji = ?, data_wygasniecia = ?, cena_netto = ?, podatek_vat = ?, ilosc = ?, status = ?, kategoria = ?, gabaryt = ?, zdjecie = ? WHERE id = ?");
        $stmt->bind_param("sssssiiissssi", $tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie_sciezka, $produkt_id);
        $stmt->execute();
        $stmt->close();
    }

    // Funkcja wyświetlająca produkty
    public function PokazProdukty()
    {
        $stmt = $this->link->prepare("SELECT * FROM `produkty` ORDER BY `id` DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        // Iteracja po wynikach
        while ($row = $result->fetch_assoc()) {

            echo 'ID: ' . $row['id'] . ' | Tytuł: ' . htmlspecialchars($row['tytul']) . '<br>';
            echo sprawdzDostepnosc($row);
            echo '<form method="post" style="display:inline;">
                    <input type="submit" name="product_delete' . $row['id'] . '" value="Usuń">
                    <input type="submit" name="product_edit' . $row['id'] . '" value="Edytuj">
                </form><br>';
        }
        

        $stmt->close();

        // Obsługa akcji usuwania/edycji
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'product_delete') === 0) {
                $this->UsunProdukty($key);
            }
            if (strpos($key, 'product_edit') === 0) {
                $this->EdytujProdukty($key);
            }
        }
    }

    public function PokazProdukty_uzytkownik()
    {
        $stmt = $this->link->prepare("SELECT * FROM `produkty` ORDER BY `id` DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        // Iteracja po wynikach
        while ($row = $result->fetch_assoc()) {

            echo 'ID: ' . $row['id'] . ' | Tytuł: ' . htmlspecialchars($row['tytul']) . '<br>';
            echo sprawdzDostepnosc($row);
        }
        

        $stmt->close();

    }

    // Pobieranie kategorii do opcji select
    private function PobierzKategorie($wybranaKategoria = null)
    {
        $options = '';
        $stmt = $this->link->prepare("SELECT * FROM kategorie ORDER BY nazwa ASC");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $selected = ($wybranaKategoria == $row['id']) ? ' selected' : '';
            $options .= '<option value="' . $row['id'] . '"' . $selected . '>' . htmlspecialchars($row['nazwa'], ENT_QUOTES) . '</option>';
        }

        $stmt->close();
        return $options;
    }
}


function sprawdzDostepnosc($produkt) {
    $status = $produkt['status']; // np. "dostępny", "niedostępny"
    $ilosc = $produkt['ilosc']; // np. 10
    $data_wygasniecia = $produkt['data_wygasniecia']; // np. "2024-12-31"

    // Pobierz aktualną datę
    $dzisiaj = date('Y-m-d');

    // Warunki dostępności
    if ($status !== 'dostępny') {
        return 'Produkt niedostępny';
    }

    if ($ilosc <= 0) {
        return 'Brak na magazynie';
    }

    if (!empty($data_wygasniecia) && $data_wygasniecia < $dzisiaj) {
        return 'Produkt wygasł';
    }

    return 'Produkt dostępny';
}










?>
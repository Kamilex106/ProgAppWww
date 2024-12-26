<?php

// Klasa do zarządzania kategoriami w sklepie
class ZarzadzajKategoriami
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link; // Przechowywanie połączenia z bazą danych
    }

    // Funkcja dodająca lub edytująca kategorię
    public function FormularzKategorie($category_id = null)
    {
        $is_edit = !empty($category_id);  // Określa, czy formularz jest w trybie edycji na podstawie istnienia $category_id
        $category_data = ['nazwa' => '', 'matka' => '']; // Inicjalizacja tablicy z danymi kategorii (domyślnie puste)

        // Jeśli jesteśmy w trybie edycji
        if ($is_edit) {
            // Pobranie aktualnych danych kategorii do edycji
            $stmt = $this->link->prepare("SELECT * FROM kategorie WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($category = $result->fetch_assoc()) {
                // Wypełnienie tablicy danymi kategorii, zabezpieczając przed atakami XSS
                $category_data['nazwa'] = htmlspecialchars($category['nazwa'], ENT_QUOTES);
                $category_data['matka'] = htmlspecialchars($category['matka'], ENT_QUOTES);
            }
            $stmt->close();
        }

        // Formularz HTML do dodawania/edycji kategorii
        $wynik = '
        <div class="' . ($is_edit ? 'edytowanie' : 'dodawanie') . '">
         <form method="post" name="' . ($is_edit ? 'EditCategoryForm' : 'AddCategoryForm') . '" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <br>
            <input type="text" name="kategoria_nazwa" id="kategoria_nazwa" placeholder="Nazwa kategorii" value="' . $category_data['nazwa'] . '" required>
            <br>
            <input type="text" name="matka" id="matka" placeholder="ID nadrzędnej kategorii" value="' . $category_data['matka'] . '" required>
            <br>';
        if ($is_edit) {
            $wynik .= '<input type="hidden" name="category_id" value="' . $category_id . '">';
        }
        $wynik .= '<input type="submit" name="' . ($is_edit ? 'edit_category_submit' : 'add_category_submit') . '" class="' . ($is_edit ? 'edit_category' : 'add_category') . '" value="' . ($is_edit ? 'Edytuj' : 'Dodaj') . '">
         </form>
        </div>
        ';

        echo $wynik;
    }

    // Przetwarzanie dodawania lub edycji kategorii
    public function PrzetwarzajKategorie()
    {
        if (isset($_POST['add_category_submit'])) {
            $kategoria_nazwa = htmlspecialchars(trim($_POST['kategoria_nazwa']));
            $matka = (int) $_POST['matka'];

            // Przygotowanie zapytania SQL do dodania
            $stmt = $this->link->prepare("INSERT INTO `kategorie` (`matka`, `nazwa`) VALUES (?, ?)");
            $stmt->bind_param("is", $matka, $kategoria_nazwa);
            $stmt->execute();
            $stmt->close();
        } elseif (isset($_POST['edit_category_submit'])) {
            $kategoria_nazwa = htmlspecialchars(trim($_POST['kategoria_nazwa']));
            $matka = (int) $_POST['matka'];
            $category_id = (int) $_POST['category_id'];

            // Przygotowanie zapytania SQL do edycji
            $stmt = $this->link->prepare("UPDATE `kategorie` SET `matka` = ?, `nazwa` = ? WHERE `id` = ? LIMIT 1");
            $stmt->bind_param("isi", $matka, $kategoria_nazwa, $category_id);
            $stmt->execute();
            $stmt->close();
            echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
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
        echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
    }

    // Wyświetlanie kategorii w strukturze drzewa
    public function PokazKategorie($parent_id = 0, $level = 0)
    {
        // Przygotowanie zapytania SQL do pobrania kategorii nadrzędnych i podrzędnych
        $stmt = $this->link->prepare("SELECT * FROM `kategorie` WHERE `matka` = ? ORDER BY `id`");
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Iteracja przez wyniki zapytania
        while ($row = $result->fetch_assoc()) {
            $indentation = str_repeat('   ', $level); // Dodanie wcięcia dla wizualizacji hierarchii
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
        if ($parent_id === 0 && !isset($_POST['edit_category_submit']) && !isset($_POST['add_category_submit'])) {
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'category_delete') === 0) {
                    $this->UsunKategorie($key);
                }
                if (strpos($key, 'category_edit') === 0) {
                    $this->FormularzKategorie(preg_replace('/\D/', '', $key));
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

    // Funkcja dodająca lub edytująca produkt
    public function FormularzProdukty($produkt_id = null)
    {
        // Określa, czy formularz jest w trybie edycji
        $is_edit = !empty($produkt_id);
        // Inicjalizacja tablicy z danymi produktu (domyślne wartości)
        $produkt = [
            'tytul' => '', 'opis' => '', 'data_utworzenia' => '', 'data_modyfikacji' => '',
            'data_wygasniecia' => '', 'cena_netto' => '', 'podatek_vat' => '', 'ilosc' => '',
            'status' => '', 'kategoria' => '', 'gabaryt' => '', 'zdjecie' => ''
        ];

        // Jeśli jesteśmy w trybie edycji
        if ($is_edit) {
            $stmt = $this->link->prepare("SELECT * FROM produkty WHERE id = ? LIMIT 1");
            $stmt->bind_param("i", $produkt_id);
            $stmt->execute();
            $result = $stmt->get_result();
            // Jeśli produkt o podanym ID istnieje
            if ($p = $result->fetch_assoc()) {
                // Wypełnienie tablicy danymi produktu, zabezpieczając przed atakami XSS
                foreach ($produkt as $key => $value) {
                    $produkt[$key] = htmlspecialchars($p[$key], ENT_QUOTES);
                }
            }
            $stmt->close();
        }

        $wynik = '
        <div class="' . ($is_edit ? 'edytowanie' : 'dodawanie') . '">
            <form method="post" name="' . ($is_edit ? 'EditProductForm' : 'AddProductForm') . '" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
            <br>';
        // Dodanie ukrytego pola z ID produktu w trybie edycji
        if ($is_edit) {
            $wynik .= '<input type="hidden" name="product_id" value="' . $produkt_id . '">';
        }
        $wynik .= '
            <input type="text" name="tytul" placeholder="Tytuł produktu" value="' . $produkt['tytul'] . '" required>
            <br>
            <textarea name="opis" placeholder="Opis produktu" required>' . $produkt['opis'] . '</textarea>
            <br>
            Data utworzenia:
            <input type="date" name="data_utworzenia" value="' . $produkt['data_utworzenia'] . '" required placeholder="Data utworzenia">
            <br>
            Data modyfikacji:
            <input type="date" name="data_modyfikacji" value="' . $produkt['data_modyfikacji'] . '" required placeholder="Data modyfikacji">
            <br>
            Data wygaśnięcia:
            <input type="date" name="data_wygasniecia" value="' . $produkt['data_wygasniecia'] . '" required placeholder="Data wygaśnięcia">
            <br>
            <input type="number" name="cena_netto" placeholder="Cena netto" value="' . $produkt['cena_netto'] . '" required>
            <br>
            <input type="number" name="podatek_vat" placeholder="Podatek VAT" value="' . $produkt['podatek_vat'] . '" required>
            <br>
            <input type="number" name="ilosc" placeholder="Ilość w magazynie" value="' . $produkt['ilosc'] . '" required>
            <br>
            <input type="text" name="status" placeholder="Status dostępności" value="' . $produkt['status'] . '" required>
            <br>
            <select name="kategoria">
                <!-- Generowanie opcji kategorii -->
                ' . $this->PobierzKategorie($produkt['kategoria']) . '
            </select>
            <br>
            <input type="text" name="gabaryt" placeholder="Gabaryt produktu" value="' . $produkt['gabaryt'] . '" required>
            <br>
            <input type="file" name="zdjecie" placeholder="Zdjęcie produktu">
            <br>';

        // Wyświetlenie aktualnego zdjęcia produktu, jeśli istnieje i jesteśmy w trybie edycji
        if ($is_edit && !empty($produkt['zdjecie'])) {
            $wynik .= '<p>Aktualne zdjęcie: <img src="' . $produkt['zdjecie'] . '" alt="Zdjęcie produktu" style="max-width: 200px;"></p>';
        }

        // Dodanie przycisku submit z odpowiednią nazwą
        $wynik .= '
            <input type="submit" name="' . ($is_edit ? 'edit_product_submit' : 'add_product_submit') . '" value="' . ($is_edit ? 'Zapisz zmiany' : 'Dodaj produkt') . '">
         </form>
        </div>';

        echo $wynik;
    }

    // Obsługa danych z formularza dodawania/edycji produktu
    public function PrzetwarzajProdukty()
    {
        // Sprawdzenie, czy formularz został przesłany (zarówno dla dodawania, jak i edycji)
        if (isset($_POST['add_product_submit']) || isset($_POST['edit_product_submit'])) {
            // Pobranie i oczyszczenie danych z formularza
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

            // Inicjalizacja ścieżki do zdjęcia
            $zdjecie_sciezka = '';
            // Sprawdzenie, czy zostało przesłane nowe zdjęcie
            if (!empty($_FILES['zdjecie']['name'])) {
                $zdjecie = $_FILES['zdjecie']; // Pobranie informacji o przesłanym pliku
                $zdjecie_sciezka = 'uploads/' . basename($zdjecie['name']); // Utworzenie ścieżki docelowej dla zdjęcia
                move_uploaded_file($zdjecie['tmp_name'], $zdjecie_sciezka); // Przeniesienie pliku z folderu tymczasowego do docelowego
            }

            // Obsługa dodawania nowego produktu
            if (isset($_POST['add_product_submit'])) {
                // Przygotowanie zapytania SQL do dodania nowego produktu
                $stmt = $this->link->prepare("INSERT INTO produkty (tytul, opis, data_utworzenia, data_modyfikacji, data_wygasniecia, cena_netto, podatek_vat, ilosc, status, kategoria, gabaryt, zdjecie) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                // Powiązanie parametrów z wartościami
                $stmt->bind_param("sssssiiissss", $tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie_sciezka); 
            } 
            // Obsługa edycji istniejącego produktu
            elseif (isset($_POST['edit_product_submit'])) {
                // Pobranie ID edytowanego produktu
                $produkt_id = (int) $_POST['product_id'];
                // Jeśli nie przesłano nowego zdjęcia, zachowaj stare
                $zdjecie_sql = !empty($zdjecie_sciezka) ? ' zdjecie = ?,' : '';
                $param_types = "sssssiiisss"; // Domyślne typy bez zdjęcia
                $params = [$tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt];

                // Jeśli przesłano nowe zdjęcie, dodaj je do parametrów i typów
                if (!empty($zdjecie_sciezka)) {
                    array_push($params, $zdjecie_sciezka);
                    $param_types .= "s"; // Dodaj typ dla zdjęcia (string)
                }
                array_push($params, $produkt_id);
                $param_types .= "i"; // Dodaj typ dla ID produktu

                // Usuń przecinek z końca $zdjecie_sql jeśli jest
                $zdjecie_sql = rtrim($zdjecie_sql, ',');

                $stmt = $this->link->prepare("UPDATE produkty SET tytul = ?, opis = ?, data_utworzenia = ?, data_modyfikacji = ?, data_wygasniecia = ?, cena_netto = ?, podatek_vat = ?, ilosc = ?, status = ?, kategoria = ?, gabaryt = ?" . ($zdjecie_sql ? ', zdjecie = ?' : '') . " WHERE id = ?");
                $stmt->bind_param($param_types, ...$params);
            }
            $stmt->execute();
            $stmt->close();
            echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
        }
    }

    // Funkcja usuwająca produkt z bazy danych
    public function UsunProdukty($key)
    {
        $produkt_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID produktu

        // Przygotowanie zapytania SQL
        $stmt = $this->link->prepare("DELETE FROM `produkty` WHERE `id` = ? LIMIT 1");
        $stmt->bind_param("i", $produkt_id);
        $stmt->execute();
        $stmt->close();
        echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
    }

    // Funkcja wyświetlająca produkty
    public function PokazProdukty()
    {
        $stmt = $this->link->prepare("SELECT * FROM `produkty` ORDER BY `id` DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        // Iteracja po wynikach i wyświetlanie informacji o każdym produkcie
        while ($row = $result->fetch_assoc()) {

            echo 'ID: ' . $row['id'] . ' | Tytuł: ' . htmlspecialchars($row['tytul']) . '<br>';
            echo sprawdzDostepnosc($row); // Wywołanie funkcji sprawdzającej dostępność produktu
            // Formularz z przyciskami do usuwania i edycji produktu
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
                $this->FormularzProdukty(preg_replace('/\D/', '', $key));
            }
        }
    }

    // // Pobieranie kategorii z bazy danych w celu wyświetlenia ich w opcji select
    private function PobierzKategorie($wybranaKategoria = null)
    {
        $options = '';
        $stmt = $this->link->prepare("SELECT * FROM kategorie ORDER BY nazwa ASC");
        $stmt->execute();
        $result = $stmt->get_result();

        // Iteracja po wynikach i tworzenie opcji dla select
        while ($row = $result->fetch_assoc()) {
            $selected = ($wybranaKategoria == $row['id']) ? ' selected' : ''; // Sprawdzenie, czy kategoria ma być domyślnie wybrana
            $options .= '<option value="' . $row['id'] . '"' . $selected . '>' . htmlspecialchars($row['nazwa'], ENT_QUOTES) . '</option>';
        }

        $stmt->close();
        return $options;
    }
}

// Funkcja sprawdzająca dostępność produktu na podstawie jego statusu, ilości i daty wygaśnięcia
function sprawdzDostepnosc($produkt) {
    $status = $produkt['status']; // np. "dostępny", "niedostępny"
    $ilosc = $produkt['ilosc']; // np. 10
    $data_wygasniecia = $produkt['data_wygasniecia']; // np. "2024-12-31"

    // Pobierz aktualną datę
    $dzisiaj = date('Y-m-d');

    // Warunki dostępności
    if ($status !== 'dostępny') {
        return 'Produkt niedostępny'; // Jeśli status jest inny niż 'dostępny'
    }

    if ($ilosc <= 0) {
        return 'Brak na magazynie'; // Jeśli ilość produktu jest mniejsza lub równa 0
    }

    if (!empty($data_wygasniecia) && $data_wygasniecia < $dzisiaj) {
        return 'Produkt wygasł'; // Jeśli data wygaśnięcia jest wcześniejsza niż dzisiejsza data
    }

    return 'Produkt dostępny'; // Jeśli wszystkie warunki dostępności są spełnione
}

?>
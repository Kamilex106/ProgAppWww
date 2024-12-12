<?php


class ZarzadzajKategoriami
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link; // Przechowywanie połączenia z bazą danych
    }

    public function DodajKategorie()
    {
        $wynik = '
        <div class="dodawanie">
         <form method="post" name="AddCategoryForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <br>
            <input type="text" name="kategoria_nazwa" id="kategoria_nazwa" placeholder="Nazwa kategorii" required> 
            <br>
            <input type="text" name="matka" id="matka" placeholder="Podkategoria" required> 
            <br>
            <input type="submit" name="add_category_submit" class="add_category" value="Dodaj">
         </form>
        </div>
        ';

        echo $wynik;

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

    public function UsunKategorie($key)
    {
        $category_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID kategorii

        // Przygotowanie zapytania SQL
        $stmt = $this->link->prepare("DELETE FROM `kategorie` WHERE `id` = ? LIMIT 1");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $stmt->close();
    }

    public function EdytujKategorie($key)
    {
        $category_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID kategorii

        $wynik = '
        <div class="edytowanie">
         <form method="post" name="EditCategoryForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <br>
            <input type="text" name="kategoria_nazwa" id="kategoria_nazwa" placeholder="Nazwa kategorii" required> 
            <br>
            <input type="text" name="matka" id="matka" placeholder="Podkategoria" required> 
            <br>
            <input type="hidden" name="category_id" value="' . $category_id . '">
            <input type="submit" name="edit_category_submit" class="edit_category" value="Edytuj">
         </form>
        </div>
        ';

        echo $wynik;
    }

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
                    <input type="submit" name="category_delete' . $row['id'] . '" value="Usun">
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


class ZarzadzajProduktami
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link; // Przechowywanie połączenia z bazą danych
    }

    public function DodajProdukty()
    {
        $wynik = '
        <div class="dodawanie">
         <form method="post" name="AddProductForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
        <br>
            <input type="text" name="produkt_nazwa" id="produkt_nazwa" placeholder="Nazwa kategorii" required> 
            <br>
            <input type="text" name="produkt_opis" id="produkt_opis" placeholder="Opis kategorii" required> 
            <br>
            <input type="date" name="data_utworzenia" id="data_utworzenie" placeholder="Data utworzenia" required> 
            <br> 
            <input type="date" name="data_modyfikacji" id="data_modyfikacji" placeholder="Data modyfikacji" required> 
            <br> 
            <input type="date" name="data_wygasniecia" id="data_wygasniecia" placeholder="Data wygaśnięcia" required> 
            <br> 
            <input type="int" name="cena_netto" id="cena_netto" placeholder="Cena netto" required> 
            <br> 
            <input type="int" name="podatek_vat" id="podatek_vat" placeholder="Podatek" required> 
            <br> 
            <input type="int" name="ilosc" id="ilosc" placeholder="Ilość" required> 
            <br> 
            <input type="text" name="status" id="status" placeholder="Status" required> 
            <br> 
            <input type="text" name="kategoria" id="kategoria" placeholder="Kategoria" required> 
            <br> 
            <input type="int" name="gabaryt" id="gabaryt" placeholder="Gabaryt" required> 
            <br>
            <input type="blob" name="zdjecie" id="zdjecie" placeholder="Zdjęcie" required> 
            <br>
            <input type="submit" name="add_product_submit" class="add_product" value="Dodaj">
         </form>
        </div>
        ';

        echo $wynik;

        if (isset($_POST['add_product_submit'])) {
            $produkt_nazwa = htmlspecialchars(trim($_POST['produkt_nazwa']));
            $kategoria_id = //

            // Przygotowanie zapytania SQL
            $stmt = $this->link->prepare("INSERT INTO `produkty` ('tytul','opis','data_utworzenia','data_modyfikacji','data_wygasniecia','cena_netto',podatek_vat','ilosc','status','kategoria','gabaryt','zdjecie') VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("is", $tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function UsunProdukty($key)
    {
        $product_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID produktu

        // Przygotowanie zapytania SQL
        $stmt = $this->link->prepare("DELETE FROM `produkty` WHERE `id` = ? LIMIT 1");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->close();
    }

    public function EdytujProdukty($key)
    {
        $product_id = (int) preg_replace('/\D/', '', $key); // Wyodrębnienie ID produktu

        $wynik = '
        <div class="edytowanie">
         <form method="post" name="EditProductForm" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
            <br>
            <input type="text" name="produkt_nazwa" id="produkt_nazwa" placeholder="Nazwa kategorii" required> 
            <br>
            <input type="text" name="produkt_opis" id="produkt_opis" placeholder="Opis kategorii" required> 
            <br>
            <input type="date" name="data_utworzenia" id="data_utworzenie" placeholder="Data utworzenia" required> 
            <br> 
            <input type="date" name="data_modyfikacji" id="data_modyfikacji" placeholder="Data modyfikacji" required> 
            <br> 
            <input type="date" name="data_wygasniecia" id="data_wygasniecia" placeholder="Data wygaśnięcia" required> 
            <br> 
            <input type="int" name="cena_netto" id="cena_netto" placeholder="Cena netto" required> 
            <br> 
            <input type="int" name="podatek_vat" id="podatek_vat" placeholder="Podatek" required> 
            <br> 
            <input type="int" name="ilosc" id="ilosc" placeholder="Ilość" required> 
            <br> 
            <input type="text" name="status" id="status" placeholder="Status" required> 
            <br> 
            <input type="text" name="kategoria" id="kategoria" placeholder="Kategoria" required> 
            <br> 
            <input type="int" name="gabaryt" id="gabaryt" placeholder="Gabaryt" required> 
            <br>
            <input type="blob" name="zdjecie" id="zdjecie" placeholder="Zdjęcie" required> 
            <br>
            <input type="submit" name="edit_product_submit" class="edit_product" value="Edytuj">
         </form>
        </div>
        ';

        echo $wynik;
    }

    public function PrzetwarzajEdycjeKategorii()
    {
        if (isset($_POST['edit_product_submit'])) {
            $produkt_nazwa = htmlspecialchars(trim($_POST['produkt_nazwa']));
            //$matka = (int) $_POST['matka'];
            $product_id = (int) $_POST['product_id'];

            // Przygotowanie zapytania SQL
            $stmt = $this->link->prepare("UPDATE `produkty` SET `tytul` = ?, `opis` = ?, `data_utworzenia` = ?, `data_modyfikacji` = ?, , `data_wygasniecia` = ?, `cena_netto` = ? , `podatek_vat` = ? , `ilosc` = ? , `status` = ? , `kategoria` = ? , `gabaryt` = ?, `zdjecie` = ? WHERE `id` = ? LIMIT 1");
            $stmt->bind_param("isi", $tytul, $opis, $data_utworzenia, $data_modyfikacji, $data_wygasniecia, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt, $zdjecie);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function PokazProdukty($kategoria)
    {
        // Przygotowanie zapytania SQL
        $stmt = $this->link->prepare("SELECT * FROM `produkty` WHERE `kategoria` = ? ORDER BY `id`");
        $stmt->bind_param("i", $kategoria);
        $stmt->execute();
        $result = $stmt->get_result();

        // Iteracja przez wyniki zapytania
        while ($row = $result->fetch_assoc()) {
            $indentation = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
            echo($indentation . 'id:' . $row['id'] . ' ' . htmlspecialchars($row['nazwa']) . '
                <form method="post" style="display:inline;">
                    <input type="submit" name="product_delete' . $row['id'] . '" value="Usun">
                    <input type="submit" name="product_edit' . $row['id'] . '" value="Edytuj">
                </form><br>');

        }

        $stmt->close();

        // Obsługa akcji edycji i usuwania
        if ($parent_id === 0 && !isset($_POST['edit_product_submit'])) {
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'product_delete') === 0) {
                    $this->UsunProdukty($key);
                }
                if (strpos($key, 'product_edit') === 0) {
                    $this->EdytujProdukty($key);
                }
            }
        }
    }
}



?>




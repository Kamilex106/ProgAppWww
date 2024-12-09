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

?>

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
         <form method="post" name="AddCategoryForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
        <br>
                        <input type="text" name="kategoria_nazwa" id="kategoria_nazwa"  placeholder="Nazwa kategorii"> 
                        <br>
                        <input type="text" name="matka" id="matka"  placeholder="Podkategoria"> 
                        <br>
            <input type="submit" name="add_category_submit" class="add_category" value="Dodaj" /></td></tr>
         </form>
        </div>
        ';

        echo($wynik);

        if (isset($_POST['add_category_submit'])) {
            $kategoria_nazwa = $_POST['kategoria_nazwa'];
            $matka = $_POST['matka'];

            // Zapytanie SQL do dodania nowej kategorii
            $query = "INSERT INTO `kategorie` (`matka`, `nazwa`) 
            VALUES ('$matka', '$kategoria_nazwa')";
            mysqli_query($this->link, $query);
        }
    }

    public function UsunKategorie($key)
    {
        $category_id = preg_replace('/\D/', '', $key);  // Wyodrębnienie ID kategorii
        $id = (int)$category_id;

        // Zapytanie SQL do usunięcia kategorii
        $query = "DELETE FROM `kategorie` WHERE `kategorie`.`id` = $id LIMIT 1";
        mysqli_query($this->link, $query);
    }

    public function EdytujKategorie($key)
    {
        $category_id = preg_replace('/\D/', '', $key);  // Wyodrębnienie ID kategorii

        $wynik = '
        <div class="edytowanie">
         <form method="post" name="EditCategoryForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
        <br>
                        <input type="text" name="kategoria_nazwa" id="kategoria_nazwa"  placeholder="Nazwa kategorii"> 
                        <br>
                        <input type="text" name="matka" id="matka"  placeholder="Podkategoria"> 
                        <br>
                        <input type="hidden" name="category_id" value="' . $category_id . '"/>
                        <input type="submit" name="edit_category_submit" class="edit_category" value="Edytuj">
         </form>
        </div>
        ';

        echo($wynik);
    }

    public function PrzetwarzajEdycjeKategorii()
    {
        if (isset($_POST['edit_category_submit'])) {
            $kategoria_nazwa = htmlspecialchars($_POST['kategoria_nazwa']);
            $matka = htmlspecialchars($_POST['matka']);
            $category_id = (int)$_POST['category_id'];

            // Zapytanie SQL do edycji kategorii
            $query = "UPDATE `kategorie` SET `matka` = '$matka', `nazwa` = '$kategoria_nazwa' WHERE `kategorie`.`id` = $category_id LIMIT 1";
            mysqli_query($this->link, $query);
        }
    }

    public function PokazKategorie($parent_id = 0, $level = 0)
    {
        // Pobranie kategorii dla danego rodzica
        $query = "SELECT * FROM kategorie WHERE matka = $parent_id ORDER BY id";
        $result = mysqli_query($this->link, $query);

        // Iteracja przez wyniki zapytania
        while ($row = mysqli_fetch_assoc($result)) {
            // Dodanie wcięcia w zależności od poziomu hierarchii
            $indentation = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
            echo($indentation . 'id:' . $row['id'] . ' ' . $row['nazwa'] . '
                <form method="post" style="display:inline;">
                    <input type="submit" name="category_delete' . $row['id'] . '" value="Usun"/>
                    <input type="submit" name="category_edit' . $row['id'] . '" value="Edytuj"/>
                </form><br>');

            // Rekursywne wywołanie dla dzieci bieżącej kategorii
            $this->PokazKategorie($row['id'], $level + 1);
        }

        // Obsługa akcji edycji i usuwania (tylko w pierwszym wywołaniu)
        if ($parent_id === 0 && !isset($_POST['edit_category_submit'])) {
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'category_delete') === 0) {
                    $this->UsunKategorie($key); // Usuwanie kategorii
                }
                if (strpos($key, 'category_edit') === 0) {
                    $this->EdytujKategorie($key); // Wyświetlenie formularza edycji kategorii
                }
            }
        }
    }
}

?>

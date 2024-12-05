<?php

function ZarzadzajKategoriami(metoda)

if (metoda==dodaj)
{

    $wynik = '
    <div class="dodawanie">
     <form method="post" name="AddCategoryForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
    <br>
					<input type="text" name="title" id="nazwa"  placeholder="Nazwa kategorii"> 
					<br>
          Czy główna? <input type="checkbox" name="matka" id="matka"  
					<br>
        <input type="submit" name="add_category_submit" class="add_category" value="Dodaj" /></td></tr>
     </form>
    </div>
    ';

    echo($wynik);

    if (isset($_POST['add_category_submit']))
    {
        $kategoria_nazwa = htmlspecialchars($_POST['kategoria_nazwa']);
        $czy_glowna = htmlspecialchars($_POST['czy_glowna']);
        if ($czy_glowna == ' ')
        {
            $matka=0;
        }
        else {
            $matka=1;
        }

        global $link; // Połączenie z bazą danych
    
        // Zapytanie SQL do dodania nowej podstrony
        $query = "INSERT INTO `kategorie` (`matka`, `nazwa`) 
        VALUES ('$matka', '$kategoria_nazwa')";
        $result = mysqli_query($link, $query);
    }

}



if (metoda==usun)
{

    global $link;
    // Zapytanie SQL do usunięcia kategorii
    $query="DELETE FROM `kategorie`  WHERE `kategorie`.`nazwa` = $nazwa_kategorii LIMIT 1";
    $result = mysqli_query($link,$query);

}

if (metoda==edytuj)
{

    $wynik = '
    <div class="edytowanie">
     <form method="post" name="EditCategoryForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
    <br>
					<input type="text" name="title" id="nazwa"  placeholder="Nazwa kategorii"> 
					<br>
          Czy główna? <input type="checkbox" name="matka" id="matka"  
					<br>
        <input type="submit" name="add_category_submit" class="add_category" value="Dodaj" /></td></tr>
     </form>
    </div>
    ';

    echo($wynik);

    if (isset($_POST['edit_category_submit']))
    {
        $kategoria_nazwa = htmlspecialchars($_POST['kategoria_nazwa']);
        $czy_glowna = htmlspecialchars($_POST['czy_glowna']);
        if ($czy_glowna == ' ')
        {
            $matka=0;
        }
        else {
            $matka=1;
        }

        global $link; // Połączenie z bazą danych
    
        // Zapytanie SQL do edycji kategorii
        $query="UPDATE `kategorie` SET `matka` = '$matka', `nazwa` = '$kategoria_nazwa' WHERE `kategorie`.`id` = $category_id LIMIT 1";
        $result = mysqli_query($link,$query);
    }

}

if (metoda==pokaz)
{

    global $link; // Połączenie z bazą danych
    $query="SELECT * FROM kategorie ORDER BY id LIMIT 100"; // Pobranie listy kategorii
    $result = mysqli_query($link,$query);
    
    // Iteracja przez wyniki zapytania
    while($row = mysqli_fetch_array( $result)) 
    {
       if $row['id'] == 0 {
            echo($row['id'].' '.$row['matka'].' '. $row['nazwa']);
       }
       else {
        
       }
    }

}



?>
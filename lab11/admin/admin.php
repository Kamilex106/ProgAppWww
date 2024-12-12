<?php
session_start(); // Rozpoczęcie sesji
include("cfg.php");

/*
Panel administracyjny aplikacji CMS.
Funkcjonalności:
 - Logowanie administratora
 - Zarządzanie podstronami (dodawanie, edytowanie, usuwanie)
 - Obsługa formularzy (walidacja i przetwarzanie)
*/

// Zwraca HTML dla formularza logowania
function FormularzLogowania()
{
  if ($_SESSION["is_logged"]==0) // Jeśli użytkownik jest niezalogowany
  {
    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Panel CMS:</h1>
      <div class="logowanie">
       <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
        <table class="logowanie">
         <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
         <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
         <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
        </table>
       </form>
      </div>
    </div>
    ';

    return $wynik;
  }


}

// Przetwarza dane z formularza logowania.
function PrzetwarzanieFormularza()
{
    global $login;
    global $pass;

    // Sprawdzanie, czy formularz został przesłany
    if (isset($_POST['x1_submit'])) {
        // Pobranie i oczyszczenie danych z formularza
        $log = isset($_POST['login_email']) ? trim(htmlspecialchars($_POST['login_email'])) : '';
        $password = isset($_POST['login_pass']) ? trim(htmlspecialchars($_POST['login_pass'])) : '';

        // Sprawdzanie poprawności danych logowania
        if ($log==$login && $password==$pass) {
            echo 'Zalogowano poprawnie';
            $_SESSION["is_logged"]=1; // Oznaczenie użytkownika jako zalogowanego
        } else {
          echo '<p style="color:red;">Wszystkie pola muszą być wypełnione.</p>';
          echo(FormularzLogowania()); // Ponowne wyświetlenie formularza
        }
    }
}

// Wyświetla listę podstron z opcjami edycji i usuwania.
function ListaPodstron()
{
  global $link; // Połączenie z bazą danych
  $query="SELECT * FROM page_list ORDER BY id LIMIT 100"; // Pobranie listy podstron
  $result = mysqli_query($link,$query);

  // Iteracja przez wyniki zapytania
  while($row = mysqli_fetch_array( $result)) 
  {
    // Wyświetlanie podstrony z opcjami edycji i usuwania
    echo htmlspecialchars($row['id']) . ' ' . htmlspecialchars($row['page_title']) . 
    '  <form method="post">
        <input type="submit" name="delete' . htmlspecialchars($row['id']) . '" value="Usuń"/>
        <input type="submit" name="edit' . htmlspecialchars($row['id']) . '" value="Edytuj"/>
    </form>';
}

  // Obsługa akcji edycji i usuwania
  if (isset($_POST['edit_submit']) == false) {
    foreach ($_POST as $key => $value) {
      if (strpos($key, needle: 'delete') === 0) {
          UsunPodstrone($key); // Usuwanie podstrony
      }
      if (strpos($key, needle: 'edit') === 0) {
        echo(EdytujPodstrone($key));  // Wyświetlenie formularza edycji
    }
  }
  }

}

// Funkcja zwraca formularz do edycji podstrony
function EdytujPodstrone($key)
{
  $page_id = preg_replace('/\D/', '', $key);  // Wyodrębnienie ID podstrony

  // Zwraca HTML dla formularza edycji
  $wynik = '

    <div class="edytowanie">
     <form method="post" name="EditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
<br>
					<input type="text" name="title" id="title"  placeholder="Tytuł"> 
					<br>
					<textarea style= "width: 1200px; height: 200px " id="content" name="content" placeholder="Treść strony"></textarea>
					<br>
          Czy aktywna? <input type="checkbox" name="check" id="check"  
					<br>
          <input type="hidden" name="page_id" value="' . htmlspecialchars($page_id) . '"/>
       <tr><td>&nbsp;</td><td><input type="submit" name="edit_submit" class="edytowanie" value="Wyslij" /></td></tr>
     </form>
    </div>
  ';
  return $wynik;

}

// Funkcja przetwarza dane z formularza edycji podstrony.
function PrzetwarzajEdycje()
{
  if (isset($_POST['edit_submit'])) {
    // Pobranie danych z formularza
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $check = $_POST['check'];
    $page_id = (int)$_POST['page_id'];
    if ($check == '') {
      $status=0;
    }
    else {
      $status= 1;
    }

    global $link; // Połączenie z bazą danych

    // Zapytanie SQL do aktualizacji podstrony
    $query = "UPDATE `page_list` SET `page_title` = ?, `page_content` = ?, `status` = ? WHERE `id` = ? LIMIT 1";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ssii", $title, $content, $status, $page_id);
    mysqli_stmt_execute($stmt);

  }
}

// Funkcja zwraca formularz do dodania nowej podstrony
function DodajNowaPodstrone() 
{
  $wynik = '

  <div class="dodawanie">
   <form method="post" name="EditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
        <input type="text" name="title" id="title"  placeholder="Tytuł"> 
        <br>
        <textarea style= "width: 1200px; height: 200px " id="content" name="content" placeholder="Treść strony"></textarea>
        <br>
        Czy aktywna? <input type="checkbox" name="check" id="check"  
        <br>
        <input type="text" name="alias" id="alias"  placeholder="Alias"> 
        <br>
     <tr><td>&nbsp;</td><td><input type="submit" name="add_submit" class="dodawanie" value="Wyslij" /></td></tr>
   </form>
  </div>
';
return $wynik;
}

// Funkcja przetwarza dane z formularza dodania nowej podstrony.
function PrzetwarzajDodanie()
{
  if (isset($_POST['add_submit'])) {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $check = $_POST['check'];
    $alias = htmlspecialchars($_POST['alias']);
    if ($check == '') {
      $status=0;
    }
    else {
      $status= 1;
    }

    global $link; // Połączenie z bazą danych

    // Zapytanie SQL do dodania nowej podstrony
    $query = "INSERT INTO `page_list` (`page_title`, `page_content`, `status`, `alias`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ssis", $title, $content, $status, $alias);
    mysqli_stmt_execute($stmt);

  }
}

// Funkcja usuwa podstronę z bazy danych na podstawie jej ID.
function UsunPodstrone($key)
{
    $page_id = preg_replace('/\D/', '', $key);  // Wyodrębnienie ID podstrony
    global $link;

    // Zapytanie SQL do usunięcia podstrony
    $query = "DELETE FROM `page_list` WHERE `id` = ? LIMIT 1";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $page_id);
    mysqli_stmt_execute($stmt);

}



?>





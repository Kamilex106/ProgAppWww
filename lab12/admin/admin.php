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

class Admin
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    // Funckcja zwracająca formularz logowania
    public function FormularzLogowania()
    {
      if ($_SESSION["is_logged"]==0) // Jeśli użytkownik jest niezalogowany
      {
        $wynik = '
        <div class="logowanie">
         <h1 class="heading">Panel CMS:</h1>
          <div class="logowanie">
           <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
            <table class="logowanie">
             <tr><td class="log4_t">Login</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
             <tr><td class="log4_t">Haslo</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
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
    public function PrzetwarzanieFormularza()
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
                echo '<script> setTimeout(function(){window.location.href = window.location.href}, 1500) </script>'; // Odświeżenie strony po 1.5 sekundy
            } 
            else {
                echo '<p style="color:red;">Wszystkie pola muszą być poprawnie wypełnione.</p>';
            }
        }
        // Obsługa wylogowania
        if  (isset($_GET['action']) && $_GET['action'] == 'logout') {
            session_unset(); // Usunięcie wszystkich zmiennych sesyjnych
            session_destroy(); // Zniszczenie sesji
            echo '<script>window.location.href = "index.php?idp=admin";</script>'; // Przekierowanie na stronę admin
            exit;
        }
    }
    
    // Wyświetla listę podstron z opcjami edycji i usuwania.
    public function ListaPodstron()
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
                $this->UsunPodstrone($key); // Usuwanie podstrony
            }
            if (strpos($key, needle: 'edit') === 0) {
                echo($this->FormularzPodstrony($key));  // Wyświetlenie formularza edycji
            }
      }
      }
    
    }
    
    // Funkcja zwraca formularz do edycji/dodawania podstrony
    public function FormularzPodstrony($key = null)
    {
        global $link; // Połączenie z bazą danych
        $page_id = $key ? preg_replace('/\D/', '', $key) : '';
        $is_edit = $page_id !== ''; // Czy edycja, czy dodawanie?
    
        // Domyślne wartości pól
        $title = '';
        $content = '';
        $alias = '';
        $status = 0;
    
        // Jeśli edytujemy, pobierz dane z bazy
        if ($is_edit) {
            $query = "SELECT * FROM `page_list` WHERE `id` = ? LIMIT 1";
            $stmt = mysqli_prepare($link, $query);
            mysqli_stmt_bind_param($stmt, "i", $page_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            if ($row = mysqli_fetch_assoc($result)) {
                $title = htmlspecialchars($row['page_title']);
                $content = htmlspecialchars($row['page_content']);
                $alias = htmlspecialchars($row['alias']);
                $status = (int)$row['status'];
            }
        }
    
        // Generowanie formularza
        $wynik = '
        <div class="formularz-podstrony">
            <form method="post" enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
                <input type="text" name="title" id="title" placeholder="Tytuł" value="'.htmlspecialchars($title).'" required>
                <br>
                <textarea style="width: 1200px; height: 200px" id="content" name="content" placeholder="Treść strony" required>'
                .htmlspecialchars($content).'</textarea>
                <br>
                <input type="text" name="alias" id="alias" placeholder="Alias" value="'.htmlspecialchars($alias).'" required>
                <br>';

        $wynik .= '
                Czy aktywna? <input type="checkbox" name="check" id="check" '.($status ? 'checked' : '').'><br>
                <input type="hidden" name="page_id" value="'.htmlspecialchars($page_id).'">
                <input type="submit" name="'.($is_edit ? 'edit_submit' : 'add_submit').'" value="Wyślij">
            </form>
        </div>';
    
        return $wynik;
    }
    
    
    
    // Funkcja przetwarza dane z formularza edycji/dodawania podstrony.
    public function PrzetwarzajPodstrone()
    {
        if (isset($_POST['edit_submit']) || isset($_POST['add_submit'])) {
            $title = htmlspecialchars($_POST['title']);
            $content = htmlspecialchars($_POST['content']);
            $check = isset($_POST['check']) ? 1 : 0;
            $alias = htmlspecialchars($_POST['alias']);
            $page_id = isset($_POST['page_id']) ? (int)$_POST['page_id'] : null;
    
            global $link; // Połączenie z bazą danych
    
            if ($page_id) {
                // Aktualizacja istniejącej podstrony
                $query = "UPDATE `page_list` SET `page_title` = ?, `page_content` = ?, `status` = ?, `alias` = ? WHERE `id` = ? LIMIT 1";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt, "ssisi", $title, $content, $check, $alias, $page_id);
            } else {
                // Dodanie nowej podstrony
                $alias = htmlspecialchars($_POST['alias']);
                $query = "INSERT INTO `page_list` (`page_title`, `page_content`, `status`, `alias`) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt, "ssis", $title, $content, $check, $alias);
            }
    
            mysqli_stmt_execute($stmt);
            echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
        }
    }
    
    
    // Funkcja usuwa podstronę z bazy danych na podstawie jej ID.
    public function UsunPodstrone($key)
    {
        $page_id = preg_replace('/\D/', '', $key);  // Wyodrębnienie ID podstrony
        global $link;
    
        // Zapytanie SQL do usunięcia podstrony
        $query = "DELETE FROM `page_list` WHERE `id` = ? LIMIT 1";
        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "i", $page_id);
        mysqli_stmt_execute($stmt);
        echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
    }
    
}


?>





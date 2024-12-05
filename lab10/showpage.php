<?php
session_start(); // Rozpoczęcie sesji
include('cfg.php');

// Funkcja odpowiedzialna za wyświetlenie treści podstrony na podstawie jej identyfikatora.
function PokazPodstrone($id)
{
    global $link; // Użycie zmiennej globalnej $link, która przechowuje połączenie z bazą danych

    // Sprawdzenie, czy identyfikator podstrony jest pusty
    if($id == null)
    {
      return "Nie znaleziono strony";
    }
    else
    {
      $id_clear = htmlspecialchars($id); // Oczyszczenie danych wejściowych w celu zabezpieczenia przed atakami 
      $query="SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1"; // Przygotowanie zapytania SQL w celu pobrania treści podstrony o danym identyfikatorze
      $result=mysqli_query( $link,$query); // Wykonanie zapytania w bazie danych
      $row=mysqli_fetch_array($result); // Pobranie wiersza wyników jako tablicy asocjacyjnej

      // Sprawdzenie, czy zapytanie zwróciło pusty wynik (brak podstrony o takim identyfikatorze)
      if(empty($row['id']))
      {
        $web = '[nie_znaleziono_strony]';
      }
      else
      {
        $web=$row['page_content']; // Jeśli strona została znaleziona, pobierana jest jej zawartość
      }
      return $web; // Zwrócenie zawartości strony lub komunikatu o błędzie
    }
    
}


?>
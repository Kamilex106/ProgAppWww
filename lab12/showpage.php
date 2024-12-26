<?php
session_start(); // Rozpoczęcie sesji
include('cfg.php');

// Funkcja odpowiedzialna za wyświetlenie treści podstrony na podstawie jej identyfikatora.
function PokazPodstrone($id)
{
    global $link; // Użycie zmiennej globalnej $link, która przechowuje połączenie z bazą danych

    // Sprawdzenie, czy identyfikator podstrony jest pusty
    if ($id === null || !is_numeric($id)) {
        return "Nie znaleziono strony";
    }

    // Przygotowanie zapytania SQL z użyciem prepared statements
    $query = "SELECT `page_content`, `status` FROM `page_list` WHERE `id` = ? LIMIT 1";
    $stmt = mysqli_prepare($link, $query);

    if ($stmt) {
        // Przypisanie wartości do zapytania i jego wykonanie
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        // Pobranie wyników
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Sprawdzenie, czy zapytanie zwróciło pusty wynik (brak podstrony o takim identyfikatorze)
        if (empty($row)) {
            $web = '[nie_znaleziono_strony]';
        } else {
            if ($row['status']==1) {
                $web = $row['page_content']; // Pobranie zawartości strony
            }
            else {
                $web = '[strona_nieaktywna]';
            }
        }

        // Zamknięcie przygotowanego zapytania
        mysqli_stmt_close($stmt);
    } else {
        $web = '[błąd_bazy_danych]'; // Obsługa błędów przygotowania zapytania
    }

    return $web; // Zwrócenie zawartości strony lub komunikatu o błędzie
}
?>

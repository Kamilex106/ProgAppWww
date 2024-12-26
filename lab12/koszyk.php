<?php

require_once 'cfg.php'; // Plik konfiguracyjny z połączeniem do bazy danych

$klient_id = $_SESSION['klient_id'];

// Klasa do zarządzania koszykiem klienta
class Koszyk
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    // Dodawanie produktu do koszyka
    public function dodajDoKoszyka($klient_id, $produkt_id) {
        // Sprawdź, czy produkt już istnieje w koszyku
        $query = "SELECT id, ilosc FROM koszyki WHERE klient_id = ? AND produkt_id = ?";
        $stmt = $this->link->prepare($query);
        $stmt->bind_param("ii", $klient_id, $produkt_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Jeśli produkt istnieje, zwiększ ilość
            $row = $result->fetch_assoc();
            $nowa_ilosc = $row['ilosc'] + 1;
            $this->zmienIlosc($row['id'], $nowa_ilosc);
        } 
        else {
            // Jeśli produkt nie istnieje, dodaj go jako nową pozycję
            $insert_query = "INSERT INTO koszyki (klient_id, produkt_id, ilosc) VALUES (?, ?, 1)";
            $insert_stmt = $this->link->prepare($insert_query);
            $insert_stmt->bind_param("ii", $klient_id, $produkt_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
    
        $stmt->close();
    }
    
    // Wyświetlanie koszyka
    public function pokazKoszyk($klient_id)
    {
        $stmt = $this->link->prepare("
            SELECT k.id AS koszyk_id, p.tytul, p.cena_netto, p.podatek_vat, k.ilosc 
            FROM koszyki k 
            JOIN produkty p ON k.produkt_id = p.id 
            WHERE k.klient_id = ?
        ");
        $stmt->bind_param("i", $klient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h2>Twój koszyk</h2>';
        echo '<table border="1" cellpadding="10">';
        echo '<tr>
                <th>Produkt</th>
                <th>Cena netto</th>
                <th>Ilość</th>
                <th>Cena brutto</th>
                <th>Akcje</th>
              </tr>';

        $suma = 0; // Inicjalizacja sumy wartości koszyka
        // Iteracja po produktach w koszyku
        while ($row = $result->fetch_assoc()) {
            $cena_brutto = $row['cena_netto'] + $row['podatek_vat']; // Obliczenie ceny brutto
            $wartosc = $cena_brutto * $row['ilosc']; // Obliczenie wartości produktu w koszyku
            $suma += $wartosc; // Dodanie do sumy

            echo '
                <tr>
                    <td>' . htmlspecialchars($row['tytul']) . '</td>
                    <td>' . number_format($row['cena_netto'], 2) . ' PLN</td>
                    <td>' . $row['ilosc'] . '</td>
                    <td>' . number_format($wartosc, 2) . ' PLN</td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="koszyk_id" value="' . $row['koszyk_id'] . '">
                            <input type="number" name="nowa_ilosc" min="1" value="' . $row['ilosc'] . '">
                            <button type="submit" name="zmien_ilosc">Zmień ilość</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="koszyk_id" value="' . $row['koszyk_id'] . '">
                            <button type="submit" name="usun_produkt">Usuń</button>
                        </form>
                        
                    </td>
                </tr>';
        }
        // Wyświetlenie podsumowania koszyka
        echo '<tr>
                <td colspan="3" align="right"><strong>Łączna wartość:</strong></td>
                <td>' . number_format($suma, 2) . ' PLN</td>
                <td></td>
              </tr>';
        echo '</table>';
        $stmt->close();
    }

    // Usuwanie produktu z koszyka
    public function usunZKoszyka($koszyk_id)
    {
        $stmt = $this->link->prepare("DELETE FROM koszyki WHERE id = ?");
        $stmt->bind_param("i", $koszyk_id);
        $stmt->execute();
        $stmt->close();
    }

    // Aktualizacja ilości w koszyku
    public function zmienIlosc($koszyk_id, $ilosc)
    {
        $stmt = $this->link->prepare("UPDATE koszyki SET ilosc = ? WHERE id = ?");
        $stmt->bind_param("ii", $ilosc, $koszyk_id);
        $stmt->execute();
        $stmt->close();
    }
}

?>

<?php

require_once 'cfg.php'; // Plik konfiguracyjny z połączeniem do bazy danych




$klient_id = $_SESSION['klient_id'];

// Dodajemy funkcje zarządzania koszykiem
class Koszyk
{
    private $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    // Dodawanie produktu do koszyka
    public function dodajDoKoszyka($klient_id, $produkt_id, $ilosc = 1)
    {
        $stmt = $this->link->prepare("
            INSERT INTO koszyki (klient_id, produkt_id, ilosc) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE ilosc = ilosc + ?
        ");
        $stmt->bind_param("iiii", $klient_id, $produkt_id, $ilosc, $ilosc);
        $stmt->execute();
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

        $suma = 0;
        while ($row = $result->fetch_assoc()) {
            $cena_brutto = $row['cena_netto'] + $row['podatek_vat']; // Dodanie VAT
            $wartosc = $cena_brutto * $row['ilosc'];
            $suma += $wartosc;

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

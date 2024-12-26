<?php
class Sklep
{
    private $link;

    public function __construct($link)
    {
        // Połączenie z bazą danych (przekazane z pliku cfg.php)
        $this->link = $link;

        // Sprawdzenie połączenia
        if (mysqli_connect_errno()) {
            die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
        }
    }

    // Funkcja do wyświetlania kategorii w strukturze drzewa
    public function PokazKategorie($parent_id = 0)
    {

        // Przygotowanie zapytania SQL w celu pobrania kategorii, których 'matka' odpowiada $parent_id
        $query = "SELECT * FROM `kategorie` WHERE `matka` = $parent_id ORDER BY `id`";
        $result = mysqli_query($this->link, $query);
    
        // Sprawdzenie, czy zapytanie zwróciło wyniki
        if ($result && mysqli_num_rows($result) > 0) {
            echo '<ul class="kategorie-list">';
            // Iteracja po wynikach zapytania
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li class="kategorie-item">';
                // Tworzenie linku do strony sklepu z filtrowaniem po kategorii
                echo '<a href="index.php?idp=sklep&kategoria=' . $row['id'] . '">' . htmlspecialchars($row['nazwa']) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        }
        // Wyświetlenie przycisku powrotu do głównych kategorii, jeśli nie jesteśmy na poziomie głównym
        if ($parent_id != 0) {
            echo '<div class="powrot-button">';
            echo '<a href="index.php?idp=sklep">Powrót do głównych kategorii</a>';
            echo '</div>';
        }
    }
    
    
    
    // Funkcja rekurencyjnie pobierająca wszystkie podkategorie danej kategorii
    public function getAllSubcategories($parent_id)
    {
        $subcategories = []; // Inicjalizacja tablicy na podkategorie
        $query = "SELECT `id` FROM `kategorie` WHERE `matka` = $parent_id"; // Zapytanie SQL w celu pobrania ID podkategorii dla danej kategorii nadrzędnej
        $result = mysqli_query($this->link, $query);
    
        // Sprawdzenie, czy zapytanie zwróciło wyniki
        if ($result && mysqli_num_rows($result) > 0) {
            // Iteracja po wynikach
            while ($row = mysqli_fetch_assoc($result)) {
                $subcategories[] = $row['id']; // Dodanie ID znalezionej podkategorii do tablicy
                // Rekurencyjne wywołanie funkcji dla znalezionej podkategorii w celu znalezienia jej podkategorii
                $subcategories = array_merge($subcategories, $this->getAllSubcategories($row['id']));
            }
        }
    
        return $subcategories; // Zwrócenie tablicy z ID wszystkich podkategorii
    }
    
    // Funkcja wyświetlająca produkty należące do danej kategorii i jej podkategorii
    public function PokazProduktyPoKategori($kategoria_id)
    {
        // Pobranie wszystkich podkategorii dla danej kategorii matki
        $all_categories = $this->getAllSubcategories($kategoria_id);
        $all_categories[] = $kategoria_id; // Dodajemy samą kategorię matkę
    
        // Tworzymy zapytanie SQL do pobrania produktów z wybranych kategorii
        $category_list = implode(',', $all_categories);
        $query = "SELECT * FROM `produkty` WHERE `kategoria` IN ($category_list) ORDER BY `id` DESC";
        $result = mysqli_query($this->link, $query);
    
        // Sprawdzamy, czy są produkty w wybranych kategoriach
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="produkt">';
                echo 'ID: ' . $row['id'] . ' | Tytuł: ' . htmlspecialchars($row['tytul']) . '<br>';
                echo 'Opis: ' . htmlspecialchars($row['opis']) . '<br>';
                echo 'Dostępność: ' . htmlspecialchars(sprawdzDostepnosc($row)) . '<br>';
                echo 'Cena: ' . $row['cena_netto'] . ' PLN<br>';
                echo '<img src="' . htmlspecialchars($row['zdjecie']) . '" alt="Zdjęcie produktu" style="max-width: 200px;"><br>';
                echo '<br>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="produkt_id" value="' . $row['id'] . '">';
                echo '<button type="submit" name="dodaj_koszyk">Dodaj do koszyka</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo 'Brak produktów w tej kategorii.';
        }
    }
    



    // Metoda do rejestracji użytkownika
    public function zarejestrujKlienta($email, $haslo, $imie = null, $nazwisko = null)
    {
        // Sprawdzenie, czy email już istnieje
        $stmt = $this->link->prepare("SELECT id FROM klienci WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Jeśli liczba znalezionych wierszy jest większa od 0, email jest już zarejestrowany
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return 'Email jest już zarejestrowany.';
        }
        $stmt->close();

        // Hashowanie hasła
        $haslo_hash = password_hash($haslo, PASSWORD_DEFAULT);

        // Dodanie użytkownika do bazy danych
        $stmt = $this->link->prepare("INSERT INTO klienci (email, haslo, imie, nazwisko) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $haslo_hash, $imie, $nazwisko);

        if ($stmt->execute()) {
            $stmt->close();
            return true; // Rejestracja udana
        } else {
            $stmt->close();
            return 'Błąd rejestracji.';
        }
    }

    // Metoda do logowania użytkownika
    public function zalogujKlienta($email, $haslo)
    {
        $stmt = $this->link->prepare("SELECT id, haslo FROM klienci WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Sprawdzenie, czy znaleziono klienta o podanym emailu
        if ($result->num_rows == 1) {
            $klient = $result->fetch_assoc(); // Pobranie danych klienta
            // Weryfikacja hasła
            if (password_verify($haslo, $klient['haslo'])) {
                // Ustawienie sesji
                $_SESSION['klient_id'] = $klient['id'];
                return true; // Logowanie udane
            } else {
                return 'Nieprawidłowe hasło.';
            }
        } else {
            return 'Nie znaleziono użytkownika o podanym adresie email.';
        }

    }

    // Funkcja wyświetlająca przyciski logowania/rejestracji lub informacje o zalogowaniu i przycisk wylogowania
    public function pokazPrzyciskiLogowaniaRejestracji()
    {
        // Obsługa wylogowania
        if (isset($_POST['wyloguj_submit'])) {
            session_unset(); // Usunięcie wszystkich zmiennych sesyjnych
            session_destroy(); // Zniszczenie sesji
            echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
            exit;
        }

        
    
        // Sprawdzenie, czy użytkownik jest zalogowany na podstawie istnienia zmiennej sesyjnej 'klient_id'
        if (!isset($_SESSION['klient_id'])) {
            // Obsługa rejestracji
            if (isset($_POST['rejestracja_submit'])) {
                $email = trim($_POST['email_rejestracja']);
                $haslo = trim($_POST['haslo_rejestracja']);
                $imie = trim($_POST['imie_rejestracja']);
                $nazwisko = trim($_POST['nazwisko_rejestracja']);
    
                // Wywołanie metody rejestracji i wyświetlenie komunikatu
                $wynik_rejestracji = $this->zarejestrujKlienta($email, $haslo, $imie, $nazwisko);
                if ($wynik_rejestracji === true) {
                    echo "<p style='color: green;'>Rejestracja zakończona sukcesem! Możesz się teraz zalogować.</p>";
                } else {
                    echo "<p style='color: red;'>Błąd rejestracji: " . $wynik_rejestracji . "</p>";
                }
            }
    
            // Obsługa logowania
            if (isset($_POST['logowanie_submit'])) {
                $email = trim($_POST['email_logowanie']);
                $haslo = trim($_POST['haslo_logowanie']);
    
                // Wywołanie metody logowania i wyświetlenie komunikatu
                $wynik_logowania = $this->zalogujKlienta($email, $haslo);
                if ($wynik_logowania === true) {
                    echo "<p style='color: green;'>Zalogowano pomyślnie!</p>";
                    echo '<script>window.location.href = window.location.href;</script>'; // Odświeżenie strony
                    exit;
                } else {
                    echo "<p style='color: red;'>Błąd logowania: " . $wynik_logowania . "</p>";
                }
            }
    
            // Wyświetlenie przycisków i formularzy logowania oraz rejestracji
            echo '
            <div class="auth-buttons">
                <button onclick="pokazFormularz(\'logowanie\')">Zaloguj</button>
                <button onclick="pokazFormularz(\'rejestracja\')">Zarejestruj</button>
            </div>
    
            <div id="formularz-logowanie" class="auth-formularz" style="display:none;">
                <h2>Logowanie</h2>
                <form method="post">
                    <label for="email-logowanie">Email:</label>
                    <input type="email" name="email_logowanie" id="email-logowanie" required>
                    <label for="haslo-logowanie">Hasło:</label>
                    <input type="password" name="haslo_logowanie" id="haslo-logowanie" required>
                    <button type="submit" name="logowanie_submit">Zaloguj</button>
                </form>
            </div>
    
            <div id="formularz-rejestracja" class="auth-formularz" style="display:none;">
                <h2>Rejestracja</h2>
                <form method="post">
                    <label for="email-rejestracja">Email:</label>
                    <input type="email" name="email_rejestracja" id="email-rejestracja" required>
                    <label for="haslo-rejestracja">Hasło:</label>
                    <input type="password" name="haslo_rejestracja" id="haslo-rejestracja" required>
                    <label for="imie-rejestracja">Imię:</label>
                    <input type="text" name="imie_rejestracja" id="imie-rejestracja">
                    <label for="nazwisko-rejestracja">Nazwisko:</label>
                    <input type="text" name="nazwisko_rejestracja" id="nazwisko-rejestracja">
                    <button type="submit" name="rejestracja_submit">Zarejestruj</button>
                </form>
            </div>
            ';
        } else {
            // Wyświetlenie informacji o zalogowaniu i przycisku wylogowania
            echo '
            <form method="post">
                <p>Witaj, jesteś zalogowany!</p>
                <button type="submit" name="wyloguj_submit">Wyloguj</button>
                <button type="submit" name="koszyk">Koszyk</button>
            </form>
            ';
        }
    }
    
}
?>


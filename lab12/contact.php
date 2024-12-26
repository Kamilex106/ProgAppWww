<?php
include("cfg.php");

/*
 Obsługuje wyświetlanie i przetwarzanie formularza kontaktowego.
 Funkcjonalności:
 - Wyświetlanie formularza
 - Wysyłanie wiadomości email przez funkcję `mail()`
 - Przypomnienie hasła
 - Obsługa błędów (walidacja danych wejściowych)
 */


 // Zwraca HTML dla formularza kontaktowego
function PokazKontakt()
{
    $wynik = '
    <div class="Kontakt">
     <h2 class="heading">Kontakt:</h2>
      <div class="kontakt">
       <form method="post" name="ContactForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
            <input type="text" name="temat" id="temat" class="formField" placeholder="Podaj temat"> 
            <br>
            <input type="text" name="email" id="email" class="formField" placeholder="Wpisz adres email"> 
            <br>
            <textarea style="width: 1200px; height: 200px;" id="message" name="tresc" placeholder="Treść wiadomości"></textarea>
            <br>
            <input type="submit" name="contact_submit" class="kontakt" value="Wyślij">
       </form>
      </div>
    </div>
    ';

    return $wynik;
}

// Przetwarza formularz i wysyła wiadomość email.
function WyslijMailaKontakt($odbiorca)
{
    // Sprawdzenie, czy wszystkie pola zostały wypełnione
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        echo PokazKontakt(); // Ponownie wyświetla formularz z komunikatem
    } else {
        $mail['subject'] = htmlspecialchars($_POST['temat']);  // Usunięcie znaków specjalnych
        $mail['body'] = htmlspecialchars($_POST['tresc']); // Usunięcie znaków specjalnych
        $mail['sender'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // Walidacja emaila

        if (!$mail['sender']) {
            echo '[niepoprawny_adres_email]'; // Komunikat o błędnym adresie
            return;
        }

        $mail['reciptient'] = $odbiorca;
        // Przygotowanie nagłówków emaila
        $header = "From: Formularz kontaktowy <".$mail['sender'].">\n";
        $header .= "MIME-Version: 1.0\n";
        $header .= "Content-Type: text/plain; charset=utf-8\n";
        $header .= "Content-Transfer-Encoding: 8bit\n";
        $header .= "X-Sender: <".$mail['sender'].">\n";
        $header .= "X-Mailer: PHP/".phpversion()."\n";
        $header .= "Return-Path: <".$mail['sender'].">\n";

        // Wysłanie wiadomości
        if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
            echo '[wiadomosc_wyslana]';
        } else {
            echo '[blad_wysylania]';
        }
    }
}

// Przypomina hasło użytkownikowi poprzez email.
function PrzypomnijHaslo($odbiorca)
{
    global $pass; // Pobranie globalnej zmiennej przechowującej hasło

    // Sprawdzenie, czy hasło jest ustawione
    if (empty($pass)) {
        echo '[brak_hasla]'; // Wyświetlenie komunikatu o braku hasła
        return;  // Zakończenie działania funkcji
    }

    // Przygotowanie danych do wysyłki emaila
    $mail['subject'] = "Przypomnij haslo";
    $mail['body'] = "Twoje hasło: ".$pass;
    $mail['sender'] = 'przypomnij@example.com';
    $mail['reciptient'] = $odbiorca;

    // Przygotowanie nagłówków emaila
    $header = "From: Formularz kontaktowy <".$mail['sender'].">\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: text/plain; charset=utf-8\n";
    $header .= "Content-Transfer-Encoding: 8bit\n";
    $header .= "X-Sender: <".$mail['sender'].">\n";
    $header .= "X-Mailer: PHP/".phpversion()."\n";
    $header .= "Return-Path: <".$mail['sender'].">\n";

    // Wysłanie wiadomości email
    if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
        echo '[wiadomosc_wyslana]';
    } else {
        echo '[blad_wysylania]';
    }
}

// Funkcja zwracająca formularz przypomnienia hasła
function PokazPrzypomnienieHasla()
{
    $wynik = '<h2 class="heading">Przypomnij haslo:</h2> 
    <form method="post" name="PasswordForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
		<input type="text" name="email" id="email" class="formField" placeholder="Wpisz adres email"> 
		<br>
		<input type="submit" name="password_submit" class="remind_password" value="Przypomnij haslo">
	   </form>
    ';

    return $wynik;
}

?>

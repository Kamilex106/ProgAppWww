<?php
include("cfg.php");

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

function WyslijMailaKontakt($odbiorca)
{
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        echo PokazKontakt();
    } else {
        $mail['subject'] = htmlspecialchars($_POST['temat']);
        $mail['body'] = htmlspecialchars($_POST['tresc']);
        $mail['sender'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        if (!$mail['sender']) {
            echo '[niepoprawny_adres_email]';
            return;
        }

        $mail['reciptient'] = $odbiorca;

        $header = "From: Formularz kontaktowy <".$mail['sender'].">\n";
        $header .= "MIME-Version: 1.0\n";
        $header .= "Content-Type: text/plain; charset=utf-8\n";
        $header .= "Content-Transfer-Encoding: 8bit\n";
        $header .= "X-Sender: <".$mail['sender'].">\n";
        $header .= "X-Mailer: PHP/".phpversion()."\n";
        $header .= "Return-Path: <".$mail['sender'].">\n";

        if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
            echo '[wiadomosc_wyslana]';
        } else {
            echo '[blad_wysylania]';
        }
    }
}

function PrzypomnijHaslo($odbiorca)
{
    global $pass;

    if (empty($pass)) {
        echo '[brak_hasla]';
        return;
    }

    $mail['subject'] = "Przypomnij haslo";
    $mail['body'] = "Twoje hasło: ".$pass;
    $mail['sender'] = 'przypomnij@example.com';
    $mail['reciptient'] = $odbiorca;

    $header = "From: Formularz kontaktowy <".$mail['sender'].">\n";
    $header .= "MIME-Version: 1.0\n";
    $header .= "Content-Type: text/plain; charset=utf-8\n";
    $header .= "Content-Transfer-Encoding: 8bit\n";
    $header .= "X-Sender: <".$mail['sender'].">\n";
    $header .= "X-Mailer: PHP/".phpversion()."\n";
    $header .= "Return-Path: <".$mail['sender'].">\n";

    if (mail($mail['reciptient'], $mail['subject'], $mail['body'], $header)) {
        echo '[wiadomosc_wyslana]';
    } else {
        echo '[blad_wysylania]';
    }
}
?>

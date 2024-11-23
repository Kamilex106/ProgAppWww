<?php
include("cfg.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; 

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
    global $email_pass;
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
        echo '[nie_wypelniles_pola]';
        echo PokazKontakt();
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.wp.pl';  
            $mail->SMTPAuth = true;
            $mail->Username = 'kamil.pawww@wp.pl'; 
            $mail->Password = $email_pass;  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;  

            $mail->setFrom('kamil.pawww@wp.pl', 'Formularz kontaktowy');
            $mail->addAddress($odbiorca); 


            $mail->isHTML(true); 
            $mail->Subject = htmlspecialchars($_POST['temat']);
            $mail->Body    = htmlspecialchars($_POST['tresc']);
            $mail->AltBody = htmlspecialchars(strip_tags($_POST['tresc'])); 


            $mail->send();
            echo '[Wiadomosc Wyslana]';
        } catch (Exception $e) {
            echo "Wiadomość nie mogła zostać wysłana. Błąd: {$mail->ErrorInfo}";
        }
    }
}

function PrzypomnijHaslo($odbiorca)
{
    global $pass;
    global $email_pass;


    if (empty($pass)) {
        echo '[brak_hasla]';
        return;
    }


    $mail = new PHPMailer(true);
    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.wp.pl';  
        $mail->SMTPAuth = true;
        $mail->Username = 'kamil.pawww@wp.pl'; 
        $mail->Password =  $email_pass;  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port = 465; 


        $mail->setFrom('kamil.pawww@wp.pl', 'Formularz przypomnienia hasła');
        $mail->addAddress($odbiorca); 


        $mail->isHTML(true); 
        $mail->Subject = "Przypomnienie hasła";
        $mail->Body    = "Twoje hasło to: ".$pass;
        $mail->AltBody = "Twoje hasło to: ".$pass; 

        $mail->send();
        echo '[Haslo wyslane]';
    } catch (Exception $e) {
        echo "Wiadomość nie mogła zostać wysłana. Błąd: {$mail->ErrorInfo}";
    }
}
?>

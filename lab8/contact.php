<?php
function PokazKontakt()
{
    $wynik = '
    <div class="Kontakt">
     <h1 class="heading">Kontakt:</h1>
      <div class="kontakt">
       <form method="post" name="ContactForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
       		<input type="text" name="name" id="name" class="formField" placeholder="Wpisz imię"> 
			<br>
			<input type="text" name="surname" id="surname" class="formField" placeholder="Wpisz nazwisko"> 
			<br>
			<input type="text" name="email" id="email" class="formField" placeholder="Wpisz adres email"> 
			<br>
			<textarea style= "width: 1200px; height: 200px " id="message" name="message" placeholder="Treść wiadomości"></textarea>
			<br>
            <input type="submit" name="contact_submit" class="kontakt" value="Wyslij"
       </form>
      </div>
    </div>
    ';

    return $wynik;
}


function WyslijMailaKontakt($odbiorca)
{
    if(empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email']))
    {
        echo '[nie_wypelniles_pola]';
        echo PokazKontakt();
    }
    else
    {
        $mail['subject'] = $_POST['temat'];
        $mail['body'] = $_POST['tresc'];
        $mail['sender'] = $_POST['email'];
        $mail['reciptient'] = $odbiorca;

        $header = "From: Formularz kontaktowy < ".$mail['sender']. ">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding:"
        $header .= "X-Sender: <".$mail['sender'].">\n";
        $header .= "X-mailer: PRapWWW mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <".$mail['sender']. ">\n";

        mail($mail['reciptient'],$mail['subject'],$mail['body'],$header);

        echo '[wiadomosc_wyslana]';

    }
}


function PrzypomnijHaslo($odbiorca)
{

    {
        $mail['subject'] = "Przypomnij haslo";
        $mail['body'] = "$pass";
        $mail['sender'] = 'przypomnij';
        $mail['reciptient'] = $odbiorca;

        $header = "From: Formularz kontaktowy < ".$mail['sender']. ">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding:"
        $header .= "X-Sender: <".$mail['sender'].">\n";
        $header .= "X-mailer: PRapWWW mail 1.2\n";
        $header .= "X-Priority: 3\n";
        $header .= "Return-Path: <".$mail['sender']. ">\n";

        mail($mail['reciptient'],$mail['subject'],$mail['body'],$header);

        echo '[wiadomosc_wyslana]';

    }
}


?>
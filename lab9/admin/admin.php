<?php
session_start();
include("cfg.php");
//funckcja wyświetla formlularz logowania jeśli zmienna SESSION["is_logged"] jest równa 0
function FormularzLogowania()
{
  if ($_SESSION["is_logged"]==0)
  {
    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Panel CMS:</h1>
      <div class="logowanie">
       <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
        <table class="logowanie">
         <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
         <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
         <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
        </table>
       </form>
      </div>
    </div>
    ';

    return $wynik;
  }


}

/*funkcja sprawdza czy formularz logowania został wysłany i poprawnie wypełniony -
jeśli tak to wyświetla odpowiedni komunikat i ustawia zmienną SESSION["is_logged] na 1, 
a w przeciwnym wypadku wyświetla komunikat o błędzie i wyświetla niżej nowy formularz logowania 
*/
function PrzetwarzanieFormularza()
{
    global $login;
    global $pass;

    if (isset($_POST['x1_submit'])) {
        $log = isset($_POST['login_email']) ? trim($_POST['login_email']) : '';
        $password = isset($_POST['login_pass']) ? trim($_POST['login_pass']) : '';

        if ($log==$login && $password==$pass) {
            echo 'Zalogowano poprawnie';
            $_SESSION["is_logged"]=1;
        } else {
          echo '<p style="color:red;">Wszystkie pola muszą być wypełnione.</p>';
          echo(FormularzLogowania());
        }
    }
}



//funkcja wyświetla listę wszystkich podstron, które zawiera witryna
function ListaPodstron()
{
  global $link;
  $query="SELECT * FROM page_list ORDER BY id LIMIT 100";
  $result = mysqli_query($link,$query);

  while($row = mysqli_fetch_array( $result)) 
  {
    echo($row['id'].' '.$row['page_title'].'  <form method="post">
        <input type="submit" name="delete' .$row['id'].'"
                value="Usun"/>
        
        <input type="submit" name="edit' .$row['id'].'"
                value="Edytuj"/>
    </form>');
  }


  if (isset($_POST['edit_submit']) == false) {
    foreach ($_POST as $key => $value) {
      if (strpos($key, needle: 'delete') === 0) {
          UsunPodstrone($key);
      }
      if (strpos($key, needle: 'edit') === 0) {
        echo(EdytujPodstrone($key));
    }
  }
  }

}

//funkcja zwraca zmienną 'wynik' która wyświetlona pokaże formularz za pomocą którego możemy edytować zawartość podstrony
function EdytujPodstrone($key)
{
  $page_id = preg_replace('/\D/', '', $key); 


  $wynik = '

    <div class="edytowanie">
     <form method="post" name="EditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
<br>
					<input type="text" name="title" id="title"  placeholder="Tytuł"> 
					<br>
					<textarea style= "width: 1200px; height: 200px " id="content" name="content" placeholder="Treść strony"></textarea>
					<br>
          Czy aktywna? <input type="checkbox" name="check" id="check"  
					<br>
          <input type="hidden" name="page_id" value="' . $page_id . '"/>
       <tr><td>&nbsp;</td><td><input type="submit" name="edit_submit" class="edytowanie" value="Wyslij" /></td></tr>
     </form>
    </div>
  ';
  return $wynik;

}

/*funkcja sprawdza czy formularz edytowania został wysłany, a nastepnie 
aktualizuje zawartość danej podstrony w bazie danych
*/ 
function PrzetwarzajEdycje()
{
  if (isset($_POST['edit_submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $check = $_POST['check'];
    $page_id = $_POST['page_id'];
    if ($check == '') {
      $status=0;
    }
    else {
      $status= 1;
    }

    global $link;

    $query="UPDATE `page_list` SET `page_title` = '$title', `page_content` = '$content', `status` = '$status' WHERE `page_list`.`id` = $page_id LIMIT 1";
    $result = mysqli_query($link,$query);

}
}

//funckcja zwaraca zmienną 'wynik' która wyświetlona pokaże formularz dodawania nowej postrony
function DodajNowaPodstrone() 
{
  $wynik = '

  <div class="dodawanie">
   <form method="post" name="EditForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URL'].'">
        <input type="text" name="title" id="title"  placeholder="Tytuł"> 
        <br>
        <textarea style= "width: 1200px; height: 200px " id="content" name="content" placeholder="Treść strony"></textarea>
        <br>
        Czy aktywna? <input type="checkbox" name="check" id="check"  
        <br>
        <input type="text" name="alias" id="alias"  placeholder="Alias"> 
        <br>
     <tr><td>&nbsp;</td><td><input type="submit" name="add_submit" class="dodawanie" value="Wyslij" /></td></tr>
   </form>
  </div>
';
return $wynik;
}


/*funckcja sprawdza czy formularz dodawania nowej podstrony został wysłany - jeśli tak
to dodaje nowy wpis z zawartością podstrony do bazy danych
*/
function PrzetwarzajDodanie()
{
  if (isset($_POST['add_submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $check = $_POST['check'];
    $alias = $_POST['alias'];
    if ($check == '') {
      $status=0;
    }
    else {
      $status= 1;
    }

    global $link;

    $query = "INSERT INTO `page_list` (`page_title`, `page_content`, `status`, `alias`) 
    VALUES ('$title', '$content', '$status', '$alias')";
    $result = mysqli_query($link, $query);

}
}

//funckcja usuwa z bazy danych podstroną która zostaje podana jako argument funkcji
function UsunPodstrone($key)
{
    $page_id = preg_replace('/\D/', '', $key); 
    global $link;

    $query="DELETE FROM `page_list`  WHERE `page_list`.`id` = $page_id LIMIT 1";
    $result = mysqli_query($link,$query);

}



?>





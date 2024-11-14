<?php
include('cfg.php');
function PokazPodstrone($id)
{
    global $link;
    if($id == null)
    {
      return "Nie znaleziono strony";
    }
    else
    {
      $id_clear = htmlspecialchars($id);
      $query="SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
      $result=mysqli_query( $link,$query);
      $row=mysqli_fetch_array($result);

      if(empty($row['id']))
      {
        $web = '[nie_znaleziono_strony]';
      }
      else
      {
        $web=$row['page_content'];
      }
      return $web;
    }
    
}


function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
     <h1 class="heading">Panel CMS:</h1>
      <div class="logowanie">
       <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$SERVER['REQUEST_URL'].'">
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

?>
<?php
    $nr_indeksu='1234567';
    $nrGrupy='X';

    echo 'Jan Kowalski'.$nr_indeksu.'grupa'.$nrGrupy.' <br /> <br /><br /> <br />';
    echo 'Zastosowanie metody include() <br /> <br />';



    echo 'The include expression includes and evaluates the specified file. <br /> <br />';
    include 'vars.php';
    echo "<br /> <br />A $color $fruit"; 

    echo '<br /> <br /> The require_once expression is identical to include except upon failure it will also produce a fatal E_COMPILE_ERROR level error and PHP will check if the file has already been included, and if so, not include (require) it again. <br /> <br />';
    echo "A $color $fruit"; 

    echo '. If in PHP allows for conditional execution of code fragments. PHP features an if structure that is similar to that of C: <br /> <br />';
    echo 'else - html code to run if condition is false <br /> <br />';
    $a=10;
    $b=5;
    if ($a > $b)
    echo "a is bigger than b";
    else
    echo "b is bigger than a";

    echo '<br /> <br /> elseif, as its name suggests, is a combination of if and else. Like else, it extends an if statement to execute a different statement in case the original if expression evaluates to false. However, unlike else, it will execute that alternative expression only if the elseif conditional expression evaluates to true. <br /> <br />';
    if ($a > $b) {
        echo "a is bigger than b";
    } elseif ($a == $b) {
        echo "a is equal to b";
    } else {
        echo "a is smaller than b";
    }

    echo '<br /> <br /> The switch statement is similar to a series of IF statements on the same expression. In many occasions, you may want to compare the same variable (or expression) with many different values, and execute a different piece of code depending on which value it equals to. This is exactly what the switch statement is for. <br /> <br />';
    $i=2;
    switch ($i) {
        case 0:
            echo "i equals 0";
            break;
        case 1:
            echo "i equals 1";
            break;
        case 2:
            echo "i equals 2";
            break;
    }

    echo ' <br /> <br />The meaning of a while statement is simple. It tells PHP to execute the nested statement(s) repeatedly, as long as the while expression evaluates to true. The value of the expression is checked each time at the beginning of the loop, so even if this value changes during the execution of the nested statement(s), execution will not stop until the end of the iteration (each time PHP runs the statements in the loop is one iteration). <br /> <br />';
    $i = 1;
    while ($i <= 10) {
    echo $i++;          
    }

    echo '<br /> <br /> for loops are the most complex loops in PHP. They behave like their C counterparts. The first expression (expr1) is evaluated (executed) once unconditionally at the beginning of the loop.
    In the beginning of each iteration, expr2 is evaluated. If it evaluates to true, the loop continues and the nested statement(s) are executed. If it evaluates to false, the execution of the loop ends. <br /> <br />';
    for ($i = 1; $i <= 10; $i++) {
        echo $i;
    }

    echo '<br /> <br /> An associative array of variables passed to the current script via the URL parameters (aka. query string). Note that the array is not only populated for GET requests, but rather for all requests with a query string. <br /> <br />';
    echo 'Hello ' . htmlspecialchars($_GET["name"]) . '!';

    echo '<br /> <br /> An associative array of variables passed to the current script via the HTTP POST method when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request. <br /> <br />';
    //echo 'Hello ' . htmlspecialchars($_POST["name"]) . '!';

    echo '<br /> <br />An associative array containing session variables available to the current script. See the Session functions documentation for more information on how this is used. <br /> <br />'
    //session_start();
    //$_SESSION["newsession"]=$value;
?>


//funkcja pobiera datę (dzień, miesiąc, rok)
function gettheDate() {
    Todays = new Date();
    TheDate = "Data: " + (Todays.getMonth() + 1) + "/" + Todays.getDate() + "/" + (Todays.getYear() - 100);
    document.getElementById("data").innerHTML = TheDate;
}

var timerID = null;
var timerRunning = false;

//funkcja zatrzymuje zegar
function stopclock() {
    if (timerRunning)
        clearTimeout(timerID);
    timerRunning = false;
}

//funkcja uruchamia zegar
function startclock() {
    stopclock();
    gettheDate();
    showtime();
}

//funckcja wyświetla aktualną datę i godzinę
function showtime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var timeValue = "Godzina: " + ((hours > 12) ? hours - 12 : hours);
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
    timeValue += (hours >= 12) ? " P.M." : " A.M.";
    document.getElementById("zegarek").innerHTML = timeValue;
    timerID = setTimeout("showtime()", 1000);
    timerRunning = true;
}
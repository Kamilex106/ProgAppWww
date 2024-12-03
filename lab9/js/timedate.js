// Funkcja generuje bieżącą datę i wyświetla ją w elemencie o ID "data"
function gettheDate() {
    Todays = new Date(); // Tworzenie obiektu Date, który przechowuje bieżącą datę i czas
    // Tworzenie ciągu tekstowego reprezentującego datę w formacie MM/DD/YY
    TheDate = "Data: " + (Todays.getMonth() + 1) + "/" + Todays.getDate() + "/" + (Todays.getYear() - 100); 
    // Wyświetlenie daty w elemencie HTML o ID "data"
    document.getElementById("data").innerHTML = TheDate;
}


var timerID = null; // Zmienna do przechowywania identyfikatora timera
var timerRunning = false; // Flaga do śledzenia, czy zegar jest aktywny

// Funkcja zatrzymuje zegar, jeśli jest uruchomiony.
function stopclock() {
    // Sprawdza, czy zegar jest aktywny
    if (timerRunning)
        // Jeśli tak, zatrzymuje timer za pomocą clearTimeout
        clearTimeout(timerID);
    // Ustawia flagę na false, aby oznaczyć, że zegar nie działa
    timerRunning = false;
}

// Funkcja uruchamia zegar i wyświetla bieżącą datę oraz czas.
function startclock() {
    stopclock(); // Najpierw zatrzymuje działający zegar (jeśli jest aktywny)
    gettheDate(); // Wyświetla bieżącą datę
    showtime(); // Rozpoczyna wyświetlanie czasu w trybie aktualizacji co sekundę
}

// Funkcja wyświetla bieżący czas w formacie 12-godzinnym z sekundami.
function showtime() {
    var now = new Date(); // Tworzy obiekt Date dla bieżącego czasu
    // Pobiera godziny, minuty i sekundy
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    // Formatuje czas do formatu 12-godzinnego
    var timeValue = "Godzina: " + ((hours > 12) ? hours - 12 : hours);
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
    timeValue += (hours >= 12) ? " P.M." : " A.M.";
    // Wyświetla sformatowany czas w elemencie HTML o ID "zegarek"
    document.getElementById("zegarek").innerHTML = timeValue;
    // Ustawia timer do wywołania tej samej funkcji po 1 sekundzie
    timerID = setTimeout("showtime()", 1000); 
    // Oznacza zegar jako aktywny
    timerRunning = true;
}
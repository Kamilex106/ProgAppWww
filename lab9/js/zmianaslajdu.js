var numer = Math.floor(Math.random()*3)+1; // Inicjalizacja zmiennej numer, która określa aktualny slajd (losowo od 1 do 3)
// Zmienne do przechowywania identyfikatorów timerów		
var timer1 = 0;
var timer2 = 0;
			
// Funkcja pozwala ustawić wybrany slajd.
function ustawslajd(nrslajdu)
{
	// Czyści timery, aby zatrzymać automatyczne zmiany slajdów
	clearTimeout(timer1);
	clearTimeout(timer2);
	numer = nrslajdu - 1; // Ustawia numer slajdu na wybrany (nrslajdu - 1, ponieważ numeracja zaczyna się od 0)
				
	schowaj(); // Wywołuje funkcję odpowiedzialną za ukrycie bieżącego slajdu
	setTimeout("zmienslajd()", 500); // Po 500 ms zmienia slajd na wybrany
				
}
	
// Funkcja ukrywa aktualny slajd za pomocą efektu zanikania.
function schowaj()
	{
		$("#slider").fadeOut(500); // Ukrywa element o ID "slider" w ciągu 500 ms
	}
		
/*
Funkcja zmienia slajd na kolejny w cyklu (1 -> 2 -> 3 -> 1).
Jeśli osiągnięty zostanie ostatni slajd, wraca do pierwszego.
*/
function zmienslajd()
	{
		numer++; if (numer>3) numer=1;
				
		// Tworzy ścieżkę do pliku obrazu odpowiadającego aktualnemu numerowi slajdu
		var plik = "<img src=\"img/j" + numer + ".png\" />"; 
				
		// Wstawia obraz do elementu o ID "slider"
		document.getElementById("slider").innerHTML = plik;
		$("#slider").fadeIn(500); // Wyświetla nowy slajd z efektem pojawiania (fade in) przez 500 ms
				
		timer1 = setTimeout("zmienslajd()", 10000); // Po 10 sekundach zmienia na kolejny slajd
		timer2 = setTimeout("schowaj()", 9500); // Po 9,5 sekundy ukrywa aktualny slajd, aby przygotować go na zmianę
			 
	}
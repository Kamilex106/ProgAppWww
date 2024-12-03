var computed = false; // Zmienna do śledzenia, czy dokonano obliczeń
var decimal = 0; // Zmienna używana do kontrolowania, czy wprowadzono już liczbę dziesiętną (kropkę)

//Funkcja dokonuje konwersji wartości wejściowej z jednej jednostki na inną
function convert (entryform, from, to)
{
	// Pobranie indeksów wybranych jednostek z list rozwijanych
	convertfrom = from.selectedIndex;
	convertto = to.selectedIndex;
	// Obliczenie przeliczonej wartości i wyświetlenie jej w polu "display"
	entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}

//Funkcja dodaje znak (cyfrę lub kropkę dziesiętną) do pola wejściowego.
function addChar (input, character)
{
	// Dodaj znak tylko, jeśli nie jest to kropka dziesiętna już istniejąca w liczbie
	if((character=='.' && decimal=="0") || character!='.')
	{
		// Jeśli pole jest puste lub zawiera "0", zastępujemy je wprowadzonym znakiem, w przeciwnym razie dodajemy znak na końcu
		(input.value == "" || input.value == "0") ? input.value = character : input.value += character
		// Przeprowadzenie konwersji na podstawie aktualnego stanu formularza
		convert(input.form,input.form.measure1,input.form.measure2)
		computed=true; // Oznaczenie, że dokonano obliczeń

		// Jeśli wprowadzony znak to kropka dziesiętna, ustawiamy zmienną decimal na 1
		if(character=='.')
		{
			decimal=1;
		}
	}
}

//Funkcja otwiera nowe okno przeglądarki z określonym adresem URL i ustawieniami.
function openVothcom()
{
	window.open("index.php","Display window","toolbar=no,directories=no,menubar=no");
}

// Funkcja resetuje wartości w polach wejściowych i wyjściowych formularza do zera.
function clear (form)
{
	// Ustawienie wartości pól wejściowych na 0
	form.input.value = 0;
	form.display.value = 0;
	decimal=0; // Resetowanie flagi decimal
}

// Funkcja zmienia kolor tła strony na podany kolor w formacie szesnastkowym.
function changeBackground(hexNumber)
{
	document.bgColor=hexNumber;
}

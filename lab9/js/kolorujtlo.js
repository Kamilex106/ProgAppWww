var computed = false;
var decimal = 0;
//funckja konwertuje wartości z jednej na drugą
function convert (entryform, from, to)
{
	convertfrom = from.selectedIndex;
	convertto = to.selectedIndex;
	entryform.display.value = (entryform.input.value * from[convertfrom].value / to[convertto].value);
}

//funkcja dodaje wybrany znaki
function addChar (input, character)
{
	if((character=='.' && decimal=="0") || character!='.')
	{
		(input.value == "" || input.value == "0") ? input.value = character : input.value += character
		convert(input.form,input.form.measure1,input.form.measure2)
		computed=true;
		if(character=='.')
		{
			decimal=1;
		}
	}
}


//funkcja wyświetla nowe okno ze stroną główną - index.php
function openVothcom()
{
	window.open("index.php","Display window","toolbar=no,directories=no,menubar=no");
}


//funckja czyści formularz
function clear (form)
{
	form.input.value = 0;
	form.display.value = 0;
	decimal=0;
}


//funckcja zmienia tło
function changeBackground(hexNumber)
{
	document.bgColor=hexNumber;
}

var numer = Math.floor(Math.random()*3)+1;
			
var timer1 = 0;
var timer2 = 0;
		

//funckcja pobiera jako argument numer slajdu i go ustawia
function ustawslajd(nrslajdu)
{
	clearTimeout(timer1);
	clearTimeout(timer2);
	numer = nrslajdu - 1;
				
	schowaj();
	setTimeout("zmienslajd()", 500);
				
}
		
//funkcja stopniowa chowa slajd
function schowaj()
	{
		$("#slider").fadeOut(500);
	}
	
	
//funckja okresowo zmienia slajd 
function zmienslajd()
	{
		numer++; if (numer>3) numer=1;
				
		var plik = "<img src=\"img/j" + numer + ".png\" />";
				
		document.getElementById("slider").innerHTML = plik;
		$("#slider").fadeIn(500);
				
		timer1 = setTimeout("zmienslajd()", 10000);
		timer2 = setTimeout("schowaj()", 9500);
			
	}
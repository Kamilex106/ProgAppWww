// Kliknięcie na element o ID "animacjaTestowa1" powoduje animację zmieniającą jego szerokość, przezroczystość, rozmiar czcionki i grubość obramowania
$("#animacjaTestowa1").on("click", function(){
    $(this).animate({
        width: "500px",
        opacity: 0.4,
        fontSize: "3em",
        borderWidth: "10px"
    }, 1500);
});



// Przypisuje różne zachowania do zdarzeń "mouseover" (najazd myszką) i "mouseout" (zjechanie myszką) dla elementu o ID "animacjaTestowa2"
$("#animacjaTestowa2").on({
    "mouseover" : function(){
    $(this).animate({
        width: 300
    }, 800);
},       
"mouseout" : function(){
    $(this).animate({
        width: 200
    }, 800);
}
});

// Kliknięcie na element o ID "animacjaTestowa3" powoduje, że element jest powiększany (ale tylko jeśli aktualnie nie trwa inna animacja na tym elemencie)
$("#animacjaTestowa3").on("click", function() {
    if (!$(this).is(":animated")) {
        $(this).animate({
            width: "+=" + 50,
            height: "+=" + 10,
            opacity: "-=" + 0.1,
            duration: 3000
        });
    }
});

// Kliknięcie na element o ID "animacjaTestowa4" powoduje animację zmieniającą szerokość, przezroczystość, rozmiar czcionki i grubość obramowania
$("#animacjaTestowa4").on("click", function(){
    $(this).animate({
        width: "1000px",
        opacity: 0.6,
        fontSize: "2em",
        borderWidth: "15px"
    }, 1500);
});

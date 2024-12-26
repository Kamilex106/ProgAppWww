// Funkcja do pokazywania odpowiedniego formularza (logowania lub rejestracji) i ukrywania drugiego
function pokazFormularz(typ) {
    // Pobranie elementu formularza logowania z DOM
    var logowanieForm = document.getElementById('formularz-logowanie');
    // Pobranie elementu formularza rejestracji z DOM
    var rejestracjaForm = document.getElementById('formularz-rejestracja');

    // Sprawdzenie, który formularz ma zostać wyświetlony na podstawie przekazanego argumentu 'typ'
    if (typ === 'logowanie') {
        // Ustawienie stylu wyświetlania formularza logowania na 'block', co powoduje jego wyświetlenie
        logowanieForm.style.display = 'block';
        // Ustawienie stylu wyświetlania formularza rejestracji na 'none', co powoduje jego ukrycie
        rejestracjaForm.style.display = 'none';
    } else if (typ === 'rejestracja') {
        // Ustawienie stylu wyświetlania formularza rejestracji na 'block', co powoduje jego wyświetlenie
        rejestracjaForm.style.display = 'block';
        // Ustawienie stylu wyświetlania formularza logowania na 'none', co powoduje jego ukrycie
        logowanieForm.style.display = 'none';
    }
}
function pokazFormularz(typ) {
    var logowanieForm = document.getElementById('formularz-logowanie');
    var rejestracjaForm = document.getElementById('formularz-rejestracja');

    if (typ === 'logowanie') {
        logowanieForm.style.display = 'block';
        rejestracjaForm.style.display = 'none';
    } else if (typ === 'rejestracja') {
        rejestracjaForm.style.display = 'block';
        logowanieForm.style.display = 'none';
    }
}
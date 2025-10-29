$(document).ready(function() {
    $('#menu').on('click', 'a', function(e) {
        e.preventDefault(); // Empêche le rechargement
        const url = $(this).attr('href');

        // Charge la page demandée dans la div #content
        $('#content').load(url);

        // Change l'URL dans la barre du navigateur
        history.pushState(null, '', url);
    });

    // Gère le bouton "Retour" du navigateur
    window.onpopstate = function() {
        $('#content').load(location.href);
    };
});

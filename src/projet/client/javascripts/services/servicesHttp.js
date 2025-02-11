/*
 * Couche de services HTTP (worker).
 *
 */

var BASE_URL = "http://localhost:8080/projet/server/workers/";

/**
 * Fonction permettant de demander la liste des notes au serveur.
 * @param {type} Fonction de callback lors du retour avec succès de l'appel.
 * @param {type} Fonction de callback en cas d'erreur.
 */
function chargerPays(successCallback, errorCallback) {
  $.ajax({
    type: "GET",
    dataType: "xml",
    url: BASE_URL + "DBNoteManager.php",
    success: successCallback,
    error: errorCallback
  });
}





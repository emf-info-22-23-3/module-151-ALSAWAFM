/*
 * Couche de services HTTP (worker).
 *
 */

var BASE_URL = "http://localhost:8080/projet/server/";

/**
 * Fonction permettant de demander la liste des notes au serveur.
 * @param {type} Fonction de callback lors du retour avec succès de l'appel.
 * @param {type} Fonction de callback en cas d'erreur.
 */
function chargerNotes(successCallback, errorCallback) {

  $.ajax({
    type: "GET",
    dataType: "xml",
    url: BASE_URL + "main.php",
    success: successCallback,
    error: errorCallback
  });
}

/**
 * Fonction permettant de charger les données d'équipe.
 * @param {type} teamid, id de l'équipe dans laquelle trouver les joueurs
 * @param {type} Fonction de callback lors du retour avec succès de l'appel.
 * @param {type} Fonction de callback en cas d'erreur.
 */
function connect(email, password, successCallback, errorCallback) {
  $.ajax({
      type: "POST",
      dataType: "xml",
      url: BASE_URL+ "workers/DBUserManager.php",
      data: { 
        action: 'connect', 
        email: email,
        password: password
    },
          success: successCallback,
      error: errorCallback
  });
}

/**
* Fonction permettant de charger les données d'équipe.
* @param {type} teamid, id de l'équipe dans laquelle trouver les joueurs
* @param {type} Fonction de callback lors du retour avec succès de l'appel.
* @param {type} Fonction de callback en cas d'erreur.
*/
function disconnect(successCallback, errorCallback) {
  $.ajax({
      type: "POST",
      dataType: "xml",
      url: BASE_URL+ workers/DBUserManager.php,
      data: 'action=disconnect',
      success: successCallback,
      error: errorCallback
  });
}



/**
 * Fonction permettant de demander la liste des notes au serveur.
 * @param {type} Fonction de callback lors du retour avec succès de l'appel.
 * @param {type} Fonction de callback en cas d'erreur.
 */
function deleteNotes(selectedNotes, successCallback, errorCallback) {

  $.ajax({
    type: "DELETE",
    dataType: "xml",
    url: BASE_URL + "main.php",
    data:{ titel: selectedNotes },
    success: successCallback,
    error: errorCallback
  });
}
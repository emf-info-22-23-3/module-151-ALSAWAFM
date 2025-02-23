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
    data:{ titels: selectedNotes },
    success: successCallback,
    error: errorCallback
  });
}

/**
 * Function to add a new note.
 * @param {string} titel - Title of the note.
 * @param {string} message - Message of the note.
 * @param {string} date - Date for the note.
 * @param {string} fk_category - The category of the note.
 * @param {function} successCallback - Called on success.
 * @param {function} errorCallback - Called on error.
 */
function addNote(titel, message, date,time, fk_category, successCallback, errorCallback) {
  $.ajax({
    type: "POST",
    dataType: "xml",  // Expecting a valid XML response from the server.
    url: BASE_URL + "main.php",
    data: {
      titel: titel,
      message: message,
      date: date,
      time: time,
      fk_category: fk_category
    },
    success: successCallback,
    error: errorCallback
  });
}

function attachEventHandlers() {
  $(".publish-btn").on("click", handlePublishNoteClick);
}

/**
 * Fetch categories from the database.
 * @param {function} successCallback - Callback function for success.
 * @param {function} errorCallback - Callback function for error.
 */
function getCategories(successCallback, errorCallback) {
  $.ajax({
      type: "GET",
      dataType: "xml",
      url: BASE_URL + "main.php?action=getCategories",
      success: successCallback,
      error: errorCallback
  });
}


/*
 * Contrôleur de la vue "index.html"
 *
 */

/**
 * Méthode appelée lors du retour avec succès de la liste des notes
 * @param {type} data
 * @param {type} text
 * @param {type} jqXHR
 * @returns {undefined}
 */
function chargerNotesSuccess(data, text, jqXHR) {   
  var notesGrid = document.getElementById("notes-grid"); // Get the container for notes
  var templateCard = document.querySelector(".note-card.template-card"); // Get the template card
  
  // Loop through all notes in the XML
  $(data).find("note").each(function() {
    var title = $(this).find("title").text(); // Get the title of the note
    var message = $(this).find("message").text(); // Get the message of the note
    var date = $(this).find("date").text(); // Get the date of the note
    
    // Clone the template card and fill it with data
    var noteCard = templateCard.cloneNode(true); // Clone the template
    noteCard.style.display = "block"; // Make it visible
    
    // Update the card with the note's data
    noteCard.querySelector(".note-title").textContent = title;
    noteCard.querySelector(".note-date").textContent = date;
    noteCard.querySelector(".note-preview").textContent = message.length > 100 ? message.substring(0, 100) + "..." : message;
    noteCard.querySelector(".creation-date").textContent = "Created: " + date;

    // Append the new note card to the notes grid
    notesGrid.appendChild(noteCard);
  });
}

/**
 * Méthode appelée en cas d'erreur lors de la lecture des notes
 * @param {type} request
 * @param {type} status
 * @param {type} error
 * @returns {undefined}
 */
function chargerNotesError(request, status, error) {
  alert("Erreur lors de la lecture des notes: " + error);
}

/**
 * Méthode "start" appelée après le chargement complet de la page
 */
$(document).ready(function() {
  // Call the function to load notes when the page is ready
  $.getScript("javascripts/services/servicesHttp.js", function() {
    console.log("servicesHttp.js chargé !");
    chargerNotes(chargerNotesSuccess, chargerNotesError); // Load notes from server
  });
});

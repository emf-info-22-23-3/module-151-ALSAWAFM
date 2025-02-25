/*
 * Controller for the "index.html" view
 */

/**
 * This function is called when the list of notes is successfully returned from the server.
 * It processes and displays the notes by creating note cards and appending them to the grid.
 * 
 * @param {Object} data - The raw data returned from the server, expected to be in XML format.
 * @param {string} text - The status text returned from the server (not used).
 * @param {Object} jqXHR - The jQuery XMLHttpRequest object.
 */
function chargerNotesSuccess(data, text, jqXHR) {
  console.log("chargerNotesSuccess called");
  console.log("Data: ", data); 

  var notesGrid = document.getElementById("notes-grid"); // Get the container for notes
  var templateCard = document.querySelector(".note-card.template-card"); // Get the template card

  // Loop through all notes in the XML data
  $(data).find("note").each(function () {
    var title = $(this).find("title").text();  
    var message = $(this).find("message").text(); 
    var date = $(this).find("date").text();     
    var time = $(this).find("time").text();       
    var likes = $(this).find("likes").text(); 
    var pk_note = $(this).find("pk_note").text(); 

    // Clone the template card and fill it with data
    var noteCard = templateCard.cloneNode(true); // Clone the template
    noteCard.style.display = "block";              // Make it visible

    // Update the card with the note's data
    noteCard.querySelector(".note-title").textContent = title;
    noteCard.querySelector(".note-time").textContent = time;
    noteCard.querySelector(".note-preview").textContent = message.length > 100 ? message.substring(0, 100) + "..." : message;
    noteCard.querySelector(".creation-date").textContent = "Last update: " + date;
    noteCard.querySelector(".like-count").textContent = likes;

    // Attach click event for the like button
    var likeButton = noteCard.querySelector(".like-button");
    likeButton.addEventListener("click", function () {
      incrementLike(pk_note, function () {
        // On success, update the like count in the UI
        var likeCountElement = noteCard.querySelector(".like-count");
        var currentLikes = parseInt(likeCountElement.textContent);
        likeCountElement.textContent = currentLikes + 1;
      }, function () {
        alert("Error liking the note.");
      });
    });

    // Append the new note card to the notes grid
    notesGrid.appendChild(noteCard);
  });
}

/**
 * This function is called in case of an error while reading the notes.
 * It shows an alert with the error message.
 *
 * @param {Object} request - The jqXHR object containing the response.
 * @param {string} status - The status of the request.
 * @param {string} error - The error message returned from the server.
 */
function chargerNotesError(request, status, error) {
  alert("Error loading notes: " + error);
}

/**
 * This function is called in case of an error while reading the notes.
 * It shows an alert with the error message.
 *
 * @param {Object} request - The jqXHR object containing the response.
 * @param {string} status - The status of the request.
 * @param {string} error - The error message returned from the server.
 */
function isAuthenticatedError(request, status, error) {
  alert("Error checking authentication: " + error);
  console.error("Error checking authentication.");
}

/**
 * Success callback for authentication check on index.html.
 * Redirects the user to admin.html if authenticated.
 * 
 * @param {Object} response - The server response.
 */
function authSuccessIndex(response) {
  if ($(response).find("authenticated").text() === "true") {
    window.location.href = "admin.html"; // Redirect if authenticated
  }
}

/**
 * This is the "start" method called after the page has fully loaded.
 * It checks if the admin is already logged in and redirects accordingly.
 * It also loads the services script and fetches the notes from the server.
 */
$(document).ready(function () {

  // Load the services script and fetch the notes
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
    chargerNotes(chargerNotesSuccess, chargerNotesError); // Load notes from server

        // Check authentication status
        isAuthenticated(authSuccessIndex, isAuthenticatedError);
  });
  

});

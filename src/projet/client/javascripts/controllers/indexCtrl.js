/*
 * Controller for the "index.html" view
 */

/**
 * Method called when the list of notes is successfully returned
 * @param {type} data
 * @param {type} text
 * @param {type} jqXHR
 * @returns {undefined}
 */

function chargerNotesSuccess(data, text, jqXHR) {
  console.log("chargerNotesSuccess called");
  console.log("Data: ", data);  // Log the raw response to verify if it's correct

  var notesGrid = document.getElementById("notes-grid"); // Get the container for notes
  var templateCard = document.querySelector(".note-card.template-card"); // Get the template card

  // Loop through all notes in the XML
  $(data).find("note").each(function () {
    var title = $(this).find("titel").text(); // Get the title of the note
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
 * Method called in case of an error while reading the notes
 * @param {type} request
 * @param {type} status
 * @param {type} error
 * @returns {undefined}
 */
function chargerNotesError(request, status, error) {
  alert("Error loading notes: " + error);
}





// Success callback when login is successful
function connectSuccess(data, text, jqXHR) {
  if ($(data).find("result").text() == 'true') {
      alert("Login successful");
      // You can redirect or load the user dashboard after login
      window.location.href = "newNote.html";  // For example, redirect to a dashboard page
  } else {
      alert("Login failed. Incorrect email or password.");
  }
}

function disconnectSuccess(data, text, jqXHR) {
  alert("Utilisateur déconnecté");
chargerPersonnel(chargerPersonnelSuccess, CallbackError);
}







/**
 * "Start" method called after the page is fully loaded
 */
$(document).ready(function () {
  var butConnect = $("#login-btn");

  // Call the function to load notes when the page is ready
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
    chargerNotes(chargerNotesSuccess, chargerNotesError); // Load notes from server
  });

  butConnect.click(function(event) {
event.preventDefault();  // Prevent the form from submitting normally

        var email = $("#email").val();
        var password = $("#password").val();

        // Call the connect function to send data to the server
        connect(email, password, connectSuccess, CallbackError);
  });

});

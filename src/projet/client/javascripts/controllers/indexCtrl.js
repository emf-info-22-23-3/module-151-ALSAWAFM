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
    var title = $(this).find("title").text(); // Get the title of the note
    var message = $(this).find("message").text(); // Get the message of the note
    var date = $(this).find("date").text(); // Get the date of the note
    var time = $(this).find("time").text(); // Get the date of the note
    var likes = $(this).find("likes").text(); // Get the date of the note
    var pk_note = $(this).find("pk_note").text(); // Get the primary key of the note


    // Clone the template card and fill it with data
    var noteCard = templateCard.cloneNode(true); // Clone the template
    noteCard.style.display = "block"; // Make it visible

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
 * Method called in case of an error while reading the notes
 * @param {type} request
 * @param {type} status
 * @param {type} error
 * @returns {undefined}
 */
function chargerNotesError(request, status, error) {
  alert("Error loading notes: " + error);
}


/**
 * "Start" method called after the page is fully loaded
 */
$(document).ready(function () {

  var adminEmail = localStorage.getItem("adminEmail");
    var adminId = localStorage.getItem("adminId");

    if (adminEmail && adminId) {  // Check if admin is already logged in
        window.location.href = "admin.html";
    }

  // Call the function to load notes when the page is ready
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
    chargerNotes(chargerNotesSuccess, chargerNotesError); // Load notes from server
  });
});

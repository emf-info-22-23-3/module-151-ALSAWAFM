/*
 * Controller for the "admin.html" view
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

  var notesGrid = document.getElementById("notes-grid");
  var templateCard = document.querySelector(".note-card.template-card");

  // Clear any previously appended notes (except the template)
  $(".note-card").not(".template-card").remove();

  // Loop through all notes in the XML data
  $(data)
    .find("note")
    .each(function () {
      var title = $(this).find("title").text();
      var message = $(this).find("message").text();
      var date = $(this).find("date").text();
      var time = $(this).find("time").text();
      var likes = $(this).find("likes").text();
      var pk_note = $(this).find("pk_note").text();

      // Clone the template card and fill it with data
      var noteCard = templateCard.cloneNode(true); // Clone the template
      noteCard.classList.remove("template-card"); // Remove the template class
      noteCard.style.display = "block"; // Make it visible

      // Update the card with the note's data
      noteCard.querySelector(".note-title").textContent = title;
      noteCard.querySelector(".note-time").textContent = time;
      noteCard.querySelector(".note-preview").textContent =
        message.length > 100 ? message.substring(0, 100) + "..." : message;
      noteCard.querySelector(".creation-date").textContent =
        "Last update: " + date;
      noteCard.querySelector(".like-count").textContent = likes;

      // Attach click event for the like button
      var likeButton = noteCard.querySelector(".like-button");
      likeButton.addEventListener("click", function () {
        incrementLike(
          pk_note,
          function () {
            // On success, update the like count in the UII
            var likeCountElement = noteCard.querySelector(".like-count");
            var currentLikes = parseInt(likeCountElement.textContent);
            likeCountElement.textContent = currentLikes + 1;
          },
          function () {
            alert("Error liking the note.");
          }
        );
      });

      // Set the data attribute on the delete checkbox
      noteCard
        .querySelector(".delete-checkbox")
        .setAttribute("data-title_note", title);

      // Append the new note card to the notes grid
      notesGrid.appendChild(noteCard);
    });
}

/**
 * This function is called in case of an error while reading the notes.
 * It displays an error alert to inform the user.
 *
 * @param {Object} request - The jqXHR object containing the response.
 * @param {string} status - The status of the request.
 * @param {string} error - The error message returned from the server.
 */
function chargerNotesError(request, status, error) {
  alert("Error loading notes: " + error);
}

/**
 * This function handles the click event for deleting selected notes.
 * It gathers all selected notes and deletes them, updating the UI accordingly.
 */
function handleDeleteSelected() {
  var selectedNotes = getSelectedNotes();

  if (selectedNotes.length > 0) {
    console.log("Deleting notes:", selectedNotes);
    deleteNotes(
      selectedNotes.join(","),
      function (response) {
        console.log("Notes deleted successfully", response);
        removeDeletedNotesFromUI(selectedNotes);
        alert("Selected notes deleted successfully!");
      },
      function (jqXHR, textStatus, errorThrown) {
        console.log("Error deleting notes:", errorThrown);
        alert("Error deleting selected notes. Please try again.");
      }
    );
  } else {
    alert("No notes selected for deletion.");
  }
}

/**
 * This function collects the titles of the selected notes by checking the checkboxes.
 *
 * @returns {Array} The list of selected note titles.
 */
function getSelectedNotes() {
  var selectedNotes = [];

  $(".delete-checkbox:checked").each(function () {
    var titleNote = $(this).data("title_note"); // Get the title from the data attribute
    selectedNotes.push(titleNote);
  });

  return selectedNotes;
}

/**
 * This function removes the deleted note cards from the UI.
 * It ensures that only the deleted notes are removed based on the titles.
 *
 * @param {Array} deletedNotes - List of note titles that were deleted.
 */
function removeDeletedNotesFromUI(deletedNotes) {
  $(".delete-checkbox:checked").each(function () {
    var noteCard = $(this).closest(".note-card");
    var titleNote = $(this).data("title_note");

    if (deletedNotes.includes(titleNote)) {
      noteCard.remove();
    }
  });
}

/**
 * Success callback for authentication check on admin.html.
 * Redirects the user to index.html if not authenticated.
 *
 * @param {Object} response - The server response.
 */
function authSuccessAdmin(response) {
  if ($(response).find("authenticated").text() !== "true") {
    window.location.href = "index.html"; // Redirect if not authenticated
  }
}

/**
 * Error callback for authentication check.
 * Logs an error message if the check fails.
 */
function authError() {
  console.error("Error checking authentication.");
}

/**
 * This is the "start" method that is called after the page has fully loaded.
 * It sets up the page, checks if the admin is logged in, and attaches event handlers for interactions with notes.
 */
$(document).ready(function () {
  // Load the services script and then fetch the notes
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
    chargerNotes(chargerNotesSuccess, chargerNotesError); // Load notes from the server

    isAuthenticated(authSuccessAdmin, authError); // Check authentication status
  });

  // Attach the event handler for the delete button
  $("#delete-selected-btn").click(handleDeleteSelected);

  // Navigate to modifyNote.html when modify button is clicked
  $("#modify-btn").click(function () {
    window.location.href = "modifyNote.html";
  });
});

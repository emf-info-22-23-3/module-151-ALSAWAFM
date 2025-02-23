/*
 * Controller for the "index.html" view
 */

/**
 * Method called when the list of notes is successfully returned
 */
function chargerNotesSuccess(data, text, jqXHR) {
  console.log("chargerNotesSuccess called");
  console.log("Data: ", data);  // Log the raw response to verify if it's correct

  var notesGrid = document.getElementById("notes-grid"); // Get the container for notes
  var templateCard = document.querySelector(".note-card.template-card"); // Get the template card

  // Clear any previously appended notes (except the template)
  $(".note-card").not(".template-card").remove();

  // Loop through all notes in the XML
  $(data).find("note").each(function () {
    var title = $(this).find("titel").text();   // Get the title of the note
    var message = $(this).find("message").text(); // Get the message of the note
    var date = $(this).find("date").text();       // Get the date of the note
    var time = $(this).find("time").text();       // Get the date of the note


    // Clone the template card and fill it with data
    var noteCard = templateCard.cloneNode(true); // Clone the template
    noteCard.classList.remove("template-card");    // Remove the template class
    noteCard.style.display = "block";              // Make it visible

    // Update the card with the note's data
    noteCard.querySelector(".note-title").textContent = title;
    noteCard.querySelector(".note-time").textContent = time;
    noteCard.querySelector(".note-preview").textContent = message.length > 100 ? message.substring(0, 100) + "..." : message;
    noteCard.querySelector(".creation-date").textContent = "Created: " + date;
    
    // **Fix: Set the data attribute on the delete checkbox**
    noteCard.querySelector(".delete-checkbox").setAttribute("data-titel_note", title);

    // Append the new note card to the notes grid
    notesGrid.appendChild(noteCard);
  });
}

/**
 * Method called in case of an error while reading the notes
 */
function chargerNotesError(request, status, error) {
  alert("Error loading notes: " + error);
}

/**
 * Handle the click event for deleting selected notes.
 */
function handleDeleteSelected() {
  var selectedNotes = getSelectedNotes();

  if (selectedNotes.length > 0) {
    console.log("Deleting notes:", selectedNotes); // Debug: check selected notes
    deleteNotes(selectedNotes.join(','), function (response) {
      console.log("Notes deleted successfully", response);
      removeDeletedNotesFromUI(selectedNotes);
      alert("Selected notes deleted successfully!");
    }, function (jqXHR, textStatus, errorThrown) {
      console.log("Error deleting notes:", errorThrown);
      alert("Error deleting selected notes. Please try again.");
    });
  } else {
    alert("No notes selected for deletion.");
  }
}

/**
 * Get the list of selected notes by checking the checkboxes.
 * @returns {Array} The list of selected note titles.
 */
function getSelectedNotes() {
  var selectedNotes = [];

  $(".delete-checkbox:checked").each(function () {
    var titelNote = $(this).data("titel_note");  // Get the title from the data attribute
    selectedNotes.push(titelNote);
  });

  return selectedNotes;
}

/**
 * Remove deleted note cards from the UI.
 * @param {Array} deletedNotes - List of note titles that were deleted.
 */
function removeDeletedNotesFromUI(deletedNotes) {
  $(".delete-checkbox:checked").each(function () {
    var noteCard = $(this).closest(".note-card");
    var titelNote = $(this).data("titel_note");

    if (deletedNotes.includes(titelNote)) {
      noteCard.remove();
    }
  });
}

/**
 * "Start" method called after the page is fully loaded.
 */
$(document).ready(function () {
  // Load the services script and then the notes
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
    chargerNotes(chargerNotesSuccess, chargerNotesError); // Load notes from server
  });
  
  // Attach the event handler for delete button
  $('#delete-selected-btn').click(handleDeleteSelected);
});

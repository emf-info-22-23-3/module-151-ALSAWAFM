/*
 * Controller for the "modifyNote.html" view
 */

/**
 * Load all notes into the select dropdown.
 */
function loadNotes() {
  getNotes(
    function (xml) {
      var notesDropdown = $("#note-select");
      notesDropdown.empty().append('<option value="">Select a Note</option>');

      $(xml).find("note").each(function () {
        var noteId = $(this).find("pk_note").text();
        var title = escapeHtmlEntities($(this).find("title").text()); // Escape output
        notesDropdown.append($('<option>').val(noteId).text(title));
      });
    },
    function () {
      alert("Error loading notes.");
    }
  );
}

/**
 * Load details of the selected note.
 * @param {number} noteId - The ID of the note.
 */
function loadNoteDetails(noteId) {
  getNoteDetails(
    noteId,
    function (xml) {
      var note = $(xml).find("note").first(); // Ensure only one note is selected
      if (note.length === 0) {
        alert("Note not found.");
        return;
      }
      $("#note-title").val(decodeHtmlEntities(note.find("title").text()));
      $("#note-message").val(decodeHtmlEntities(note.find("message").text()));
      $("#category-select").val(note.find("fk_category").text()); // Ensure correct category selection
    },
    function () {
      alert("Error loading note details.");
    }
  );
}

/**
 * Load categories into the dropdown securely.
 */
function loadCategories() {
  getCategories(
    function (xml) {
      var categoryDropdown = $("#category-select");
      categoryDropdown.empty();

      $(xml).find("category").each(function () {
        var id = $(this).find("pk_category").text();
        var name = escapeHtmlEntities($(this).find("category_name").text());
        categoryDropdown.append($('<option>').val(id).text(name));
      });
    },
    function () {
      alert("Error loading categories.");
    }
  );
}

/**
 * Sanitize user input to prevent HTML injection.
 * @param {string} input - The user input that needs sanitization.
 * @return {string} - The sanitized input with any HTML tags escaped.
 */
function sanitizeInput(input) {
  var tempDiv = document.createElement('div');
  tempDiv.textContent = input;  // Auto-escapes HTML
  return tempDiv.innerHTML;
}

/**
 * Escape HTML entities before displaying to prevent XSS attacks.
 * @param {string} str - The string to escape.
 * @return {string} - The escaped HTML string.
 */
function escapeHtmlEntities(str) {
  return str.replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

/**
 * Decode escaped HTML entities (for displaying saved notes).
 * @param {string} input - The HTML-escaped string.
 * @return {string} - The decoded string.
 */
function decodeHtmlEntities(input) {
  var tempDiv = document.createElement("div");
  tempDiv.innerHTML = input;
  return tempDiv.textContent || tempDiv.innerText;
}

/**
 * Update an existing note securely.
 */
// Frontend: Update note function
// Update note function
function updateNote() {
  var noteId = $("#note-select").val();
  var title = $("#note-title").val().trim();
  var message = $("#note-message").val().trim();
  var category = $("#category-select").val();
  var date = new Date().toISOString().split("T")[0];
  var time = new Date().toTimeString().split(" ")[0];
  var adminId = localStorage.getItem("adminId");

  if (!noteId || !title || !message || !category) {
    alert("Please fill in all fields.");
    return;
  }

  // Sanitize title and message
  var sanitizedTitle = sanitizeInput(title);
  var sanitizedMessage = sanitizeInput(message);

  console.log("Sending data to modifyNote:", {
    noteId: noteId,
    title: sanitizedTitle,
    message: sanitizedMessage,
    date: date,
    time: time,
    category: category,
    adminId: adminId
  });

  // Call to the backend to update the note
  modifyNote(
    noteId,
    sanitizedTitle,
    sanitizedMessage,
    date,
    time,
    category,
    adminId, // Pass admin ID here
    function (response) {
      console.log("Modify response:", response); // Log the response to debug

      // Check the <status> element from the response XML
      var status = $(response).find("status").text(); // Get status from the response

      if (status === "success") {
        console.log("Note modified successfully.");
        loadNotes(); // Refresh the note list
        setTimeout(() => {
          $("#note-select").val(noteId).change();
        }, 500);
        alert("Note modified successfully!");
      } else {
        // If there is an error, get the error message from <message> in the response
        var message = $(response).find("message").text() || "Error modifying note.";
        console.log("Error:", message);
        alert(message);
      }
    },
    function (jqXHR, textStatus, errorThrown) {
      // Handle network or server error
      console.error("Modify note failed:", textStatus, errorThrown);
      alert("Error modifying note. Please try again.");
    }
  );
}





/**
 * Clear form fields securely.
 */
function clearForm() {
  $("#note-title").val("");
  $("#note-message").val("");
  $("#category-select").val("");
}

/**
 * This function is called when the page is fully loaded. It loads the notes and categories
 * into the respective dropdowns and sets up event handlers for changing notes and submitting the form.
 */
$(document).ready(function () {
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");

    // ✅ Authentication check
    isAuthenticated(function (response) {
      if ($(response).find("authenticated").text() !== "true") {
        window.location.href = "index.html";
      } else {
        loadNotes();
        loadCategories();

        $("#note-select").off("change").on("change", function () {
          var selectedNoteId = $(this).val();
          if (selectedNoteId) {
            loadNoteDetails(selectedNoteId);
          } else {
            clearForm();
          }
        });

        $(".note-form").off("submit").on("submit", function (event) {
          event.preventDefault();
          updateNote();
        });
      }
    }, function () {
      alert("Error checking authentication. Redirecting...");
      window.location.href = "index.html";
    });
  });
});

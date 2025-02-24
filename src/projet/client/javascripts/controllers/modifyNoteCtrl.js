$(document).ready(function () {
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
    
    loadNotes(); // Load notes into the dropdown
    loadCategories(); // Load categories into the dropdown

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
  });
});

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
 * Escape HTML before displaying to prevent XSS attacks.
 * @param {string} str - The string to escape.
 * @return {string} - Escaped HTML string.
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
 * @return {string} - Decoded string.
 */
function decodeHtmlEntities(input) {
  var tempDiv = document.createElement("div");
  tempDiv.innerHTML = input;
  return tempDiv.textContent || tempDiv.innerText;
}

/**
 * Update an existing note securely.
 */
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

      if ($(response).find("result").text() === "true") {
        alert("Note modified successfully!");
        loadNotes(); // Refresh the note list

        // Wait for notes to reload, then re-select the modified note
        setTimeout(() => {
          $("#note-select").val(noteId).change();
        }, 500);
      } else {
        alert("Error modifying note.");
      }
    },
    function (jqXHR, textStatus, errorThrown) {
      console.error("Modify note failed:", textStatus, errorThrown);

      alert("Error modifying note.");
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

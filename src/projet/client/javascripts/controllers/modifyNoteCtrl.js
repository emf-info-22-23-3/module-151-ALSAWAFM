$(document).ready(function () {
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
  loadNotes(); // Load notes into the dropdown
  loadCategories(); // Load categories into the dropdown

  $("#note-select").change(function () {
    var selectedNoteId = $(this).val();
    if (selectedNoteId) {
      loadNoteDetails(selectedNoteId);
    } else {
      clearForm();
    }
  });

  $(".note-form").submit(function (event) {
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
        var title = $(this).find("title").text();
        notesDropdown.append(`<option value="${noteId}">${title}</option>`);
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
          $("#note-title").val(note.find("title").text());
          $("#note-message").val(note.find("message").text());
          $("#category-select").val(note.find("fk_category").text()); // Ensure correct category selection
      },
      function () {
          alert("Error loading note details.");
      }
  );
}

/**
 * Load categories into the dropdown.
 */
function loadCategories() {
  getCategories(
    function (xml) {
      var categoryDropdown = $("#category-select");
      categoryDropdown.empty();

      $(xml).find("category").each(function () {
        var id = $(this).find("pk_category").text();
        var name = $(this).find("category_name").text();
        categoryDropdown.append(`<option value="${id}">${name}</option>`);
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
  // Create a temporary div element
  var tempDiv = document.createElement('div');
  tempDiv.textContent = input;  // Set text content (this automatically escapes HTML)
  return tempDiv.innerHTML;  // Return the sanitized HTML
}

/**
 * Update an existing note.
 */
function updateNote() {
  var noteId = $("#note-select").val();
  var title = $("#note-title").val().trim();
  var message = $("#note-message").val().trim();
  var category = $("#category-select").val();
  var date = new Date().toISOString().split("T")[0];
  var time = new Date().toTimeString().split(" ")[0];

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
    category: category
  });

  modifyNote(
    noteId,
    sanitizedTitle,
    sanitizedMessage,
    date,
    time,
    category,
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
    function () {
      alert("Error modifying note.");
    }
  );
}


/**
 * Clear form fields.
 */
function clearForm() {
  $("#note-title").val("");
  $("#note-message").val("");
  $("#category-select").val("");
}

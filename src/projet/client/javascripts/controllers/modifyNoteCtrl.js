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
 * Update an existing note.
 */
function updateNote() {
  var noteId = $("#note-select").val();
  var title = $("#note-title").val();
  var message = $("#note-message").val();
  var category = $("#category-select").val();
  var date = new Date().toISOString().split("T")[0];
  var time = new Date().toTimeString().split(" ")[0];

  if (!noteId || !title || !message || !category) {
    alert("Please fill in all fields.");
    return;
  }

  modifyNote(
    noteId,
    title,
    message,
    date,
    time,
    category,
    function (response) {
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

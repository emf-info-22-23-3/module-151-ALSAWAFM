/**
 * Fetch categories from the server and populate the dropdown.
 */
function fetchCategories() {
  getCategories(
    function (data) { // Success callback
      var categoryDropdown = $("select[name='fk_category']");
      categoryDropdown.empty(); // Clear existing options
      categoryDropdown.append('<option value="">Select Category</option>');

      $(data).find("category").each(function () {
        var id = $(this).find("pk_category").text();
        var name = $(this).find("category_name").text();
        categoryDropdown.append('<option value="' + id + '">' + name + '</option>');
      });
    },
    function (request, status, error) { // Error callback
      alert("Error loading categories. Please try again.");
    }
  );
}

function sanitizeInput(input) {
  // Create a temporary div element
  var tempDiv = document.createElement('div');
  tempDiv.textContent = input;  // Set text content (this automatically escapes HTML)
  return tempDiv.innerHTML;  // Return the sanitized HTML
}

function getNoteFormData() {
  var title = $("input[name='title']").val().trim();
  var message = $("textarea[name='message']").val().trim();
  var fk_category = $("select[name='fk_category']").val(); // Get selected category ID

  if (!title || !message || !fk_category) {
    return null;
  }

  return {
    title: sanitizeInput(title),
    message: sanitizeInput(message),
    fk_category: fk_category
  };
}


/**
* Handles the click event on the "Publish note" button.
*/
function handlePublishNoteClick(e) {
  e.preventDefault();

  var noteData = getNoteFormData();
  if (!noteData || !noteData.fk_category) {
    alert("Please fill in all fields, including category.");
    return;
  }

  var now = new Date();
  var currentDate = now.toISOString().split("T")[0]; // YYYY-MM-DD
  var currentTime = now.toTimeString().split(" ")[0]; // HH:MM:SS

  console.log("Sending data to addNote:", {
    title: noteData.title,
    message: noteData.message,
    date: currentDate,
    time: currentTime,
    category: noteData.fk_category
  });

  addNote(
    noteData.title,
    noteData.message,
    currentDate,
    currentTime,
    noteData.fk_category,
    function () {
      alert("Note published successfully!");
      window.location.reload();
      console.log("Server response:", response);

    },
    function (error) { // Modify this line
      alert("Error publishing note. Please try again.");
      console.error("Error response:", error.responseText); // Log full server response
    }
  );
}


function attachEventHandlers() {
  $(".publish-btn").on("click", handlePublishNoteClick);
}

/**
* Attach event handlers after document loads.
*/
$(document).ready(function () {
  $.getScript("javascripts/services/servicesHttp.js", function () {
    console.log("servicesHttp.js loaded!");
    fetchCategories(); // Load categories on page load
    attachEventHandlers();
  });
});

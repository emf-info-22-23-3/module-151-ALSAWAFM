/*
 * Controller for the "newNote.html" view
 */

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
        categoryDropdown.append($('<option>').val(id).text(name));
      });
    },
    function (request, status, error) { // Error callback
      alert("Error loading categories. Please try again.");
    }
  );
}

/**
 * Sanitize user input to prevent HTML injection.
 * @param {string} input - The user input to sanitize.
 * @return {string} - The sanitized input.
 */
function sanitizeInput(input) {
  var tempDiv = document.createElement('div');
  tempDiv.textContent = input;  // Set text content (auto-escapes HTML)
  return tempDiv.innerHTML;  // Return safe content
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
 * Securely creates a text note while preventing HTML injection.
 * @returns {Object|null} - The note data object or null if validation fails.
 */
function createTextNote() {
  var title = $("input[name='title']").val().trim();
  var message = $("textarea[name='message']").val().trim();
  var fk_category = $("select[name='fk_category']").val(); // Get selected category ID

  if (!title || !message || !fk_category) {
    alert("Please fill in all fields.");
    return null;
  }

  return {
    title: sanitizeInput(title), // Ensure safe input
    message: sanitizeInput(message), // Prevent HTML injection
    fk_category: fk_category
  };
}

/**
 * Handles the click event on the "Publish note" button.
 * @param {Event} e - The event object.
 */
function handlePublishNoteClick(e) {
  e.preventDefault();

  var noteData = createTextNote();
  if (!noteData) {
    return;
  }

  var now = new Date();
  var currentDate = now.toISOString().split("T")[0]; // YYYY-MM-DD
  var currentTime = now.toTimeString().split(" ")[0]; // HH:MM:SS

  addNote(
    noteData.title,
    noteData.message,
    currentDate,
    currentTime,
    noteData.fk_category,
    function (response) {
      console.log("Server response:", response);
      alert("Note published successfully!");
      window.location.reload();
    },
    function (error) {
      alert("Error publishing note. Please try again.");
      console.error("Error response:", error.responseText);
    }
  );
}

/**
 * Attach event handlers securely.
 */
function attachEventHandlers() {
  $(".publish-btn").off("click").on("click", handlePublishNoteClick);
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

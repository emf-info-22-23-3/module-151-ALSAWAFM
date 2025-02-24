/*
 * Controller for the "login.html" view
 */


// Success callback when login is successful
function connectSuccess(data, text, jqXHR) {
  console.log("Login successful:", data);

  if ($(data).find("result").text() == 'true') {
    alert("Login successful");

  var adminEmail = $(data).find("email").text().trim();
    var adminId = $(data).find("pk_admin").text().trim();

    if (adminEmail && adminId) {
      localStorage.setItem("adminEmail", adminEmail);
      localStorage.setItem("adminId", adminId);
      
    } else {
      alert("Error retrieving admin details.");
      return;
    }

    // Redirect to admin.html
    window.location.href = "admin.html";
  } else {
    alert("Login failed. Incorrect email or password.");
  }
}

// Disconnect the user (logout)
function disconnectSuccess(data, text, jqXHR) {
  alert("User disconnected");

  // Clear the session storage upon logout
  localStorage.removeItem("adminEmail");
  localStorage.removeItem("adminId");



  // Redirect to index.html after logout
  window.location.href = "index.html";
}

/**
 * Method called in case of an error while reading the notes
 * @param {type} request
 * @param {type} status
 * @param {type} error
 * @returns {undefined}
 */
function CallbackError(request, status, error) {
  console.log("Error login:", error); // Debug log
  alert("Error login: " + error);
}


/**
 * "Start" method called after the page is fully loaded
 */
$(document).ready(function () {
  var butConnect = $("#login-btn");
  var butDisconnect = $("#disconnect-btn");



  butConnect.click(function (event) {
    event.preventDefault();  // Prevent the form from submitting normally

    var email = $("#email").val();
    var password = $("#password").val();
    console.log("Sending email:", email, "and password:", password);


    // Call the connect function to send data to the server
    connect(email, password, connectSuccess, CallbackError);
  });

  butDisconnect.click(function (event) {
    disconnect(disconnectSuccess, CallbackError);
  });

});
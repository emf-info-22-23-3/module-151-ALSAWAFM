/*
 * Controller for the "login.html" view
 */


// Success callback when login is successful
function connectSuccess(data, text, jqXHR) {
  console.log("chargerNotesSuccess called");
  console.log(data);


  if ($(data).find("result").text() == 'true') {
    alert("Login successful");
    // You can redirect or load the user dashboard after login
    window.location.href = "admin.html";  // For example, redirect to a dashboard page
  } else {
    alert("Login failed. Incorrect email or password.");
  }
}

function disconnectSuccess(data, text, jqXHR) {
  alert("Utilisateur déconnecté");
  window.location.href = "index.html";  // Redirect to index.html
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

butDisconnect.click(function(event) {
    disconnect(disconnectSuccess, CallbackError);
});

});

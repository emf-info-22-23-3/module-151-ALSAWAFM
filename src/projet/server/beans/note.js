/*
 * Bean "Note".
 *
 */

var Note = function() {
  // Initializing the properties
  this.titre = '';
  this.message = '';
};

/**
* Setter for the title
* @param String titre
* @returns {undefined}
*/
Note.prototype.setTitre = function(titre) {
this.titre = titre;
};

/**
* Setter for the message
* @param String message
* @returns {undefined}
*/
Note.prototype.setMessage = function(message) {
this.message = message;
};



/**
* Returns the note in text format (title + message)
* @returns The note in text format
*/
Note.prototype.toString = function () {
return `Title: ${this.titre}\nMessage: ${this.message}`;
};

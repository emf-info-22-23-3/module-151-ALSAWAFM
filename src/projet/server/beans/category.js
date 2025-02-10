/*
 * Bean "Category".
 *
 */

/**
 * Bean Category
 * @returns {Category}
 */
var Category = function() {
  // Initializing properties
  this.type = '';
  this.description = '';
};

/**
* Setter for the category type
* @param String type
*/
Category.prototype.setType = function(type) {
this.type = type;
};

/**
* Setter for the category description
* @param String description
*/
Category.prototype.setDescription = function(description) {
this.description = description;
};

/**
* Returns the category in text format (type + description)
* @returns The category in text format
*/
Category.prototype.toString = function () {
return `Type: ${this.type}\nDescription: ${this.description}`;
};

/**
* Getter for the category type
* @returns The type of the category
*/
Category.prototype.getType = function() {
return this.type;
};

/**
* Getter for the category description
* @returns The description of the category
*/
Category.prototype.getDescription = function() {
return this.description;
};

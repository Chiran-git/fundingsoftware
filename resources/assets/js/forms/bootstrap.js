/**
 * Initialize the RJ form extension points.
 */
RJ.forms = {
};

/**
 * Load the RJForm helper class.
 */
require('./form');

/**
 * Define the RJFormError collection class.
 */
require('./errors');

/**
 * Add additional HTTP / form helpers to the RJ object.
 */
$.extend(RJ, require('./http'));

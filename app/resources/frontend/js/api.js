/* Blocks
 * A CMS for front end developers
 * Made with love by Jake Bown
 * twitter.com/@jakebown1
 * jakebown@gmail.com
*/

var data = "json";
var type = "post";

var tapi = {

	get:function(url, array, callback) {
		var that = this;
		type = "get";
		if($.isFunction(callback)) {
			type = "post";
			that.ajax(array, url, callback);
		} else {
			that.ajax(array, url, function(response) {
				return response;
			});
		}
	},

	post:function(url, array, callback) {
		var that = this;
		if($.isFunction(callback)) {
			type = "post";
			that.ajax(array, url, callback);
		} else {
			that.ajax(array, url, function(response) {
				return response;
			});
		}
	},

	data:function(type) {
		var that = this;
		data = type;
	},

	ajax:function(array, url, callback) {
		$.ajax({
		  type: type,
		  url: url,
		  data: array,
		  success: callback,
		  error: callback, 
		  dataType: data
		});
	}

};


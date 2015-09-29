var form = $('form.variables');
var vars = {

	init:function() {
		var that = this;
		that.events();
	},

	events:function() {
		var that = this;
		$('button.save-variable').on('click', function(e) {
			e.preventDefault();
			var validate = page.validate();
			if(validate) {
				var key = form.find('input[name="key"]').val();
				var val = form.find('input[name="value"]').val();
				that.publish(key, val);
			}
		});
	},

	publish:function(k, v) {
		var kv = {key: k, value: v};
		$.ajax({
		  type: "POST",
		  cache: false,
		  url: "/git/blocks/app/class/ajax.php?type=variable",
		  data: {data: kv},
		  dataType: "html",
		  success: function(data) {
		  	$('.inner ul.list').append('<li><p><a>' + kv.key + '</a></p><small>' + kv.value + '</small></li>');
		  },
		});
	}
};

vars.init();
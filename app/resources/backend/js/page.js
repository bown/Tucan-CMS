var component = 1;
var clone = '';

var page = {

	init:function() {
		var that = this;
		clone = $('.new-component').prop('outerHTML');	
		$("ul.page-canvas").sortable({
			items : 'li:not(.locked)'
		});
		that.events();
		that.addItem();
		that.save();
	},


	addItem:function() {
		var that = this;
		var add = $('a.add-component');

		add.on('click', function(e) {
			e.preventDefault();
			var wedge = $('.page-canvas .block.header').prop('outerHTML');
			$('div.components').append(clone);
			$('.new-component:last-child').addClass('required new-component-' + component).attr('data-widget', component);

			$('.page-canvas > span').after(wedge);
			
			var wedge = $('ul.page-canvas .block.header:nth-child(3)');

			wedge.html("<label>New Component</label>");
			wedge.addClass('wedge-' + component).removeClass('locked header');
			wedge.append('<i class="ion-navicon-round"></i>');
			
			component++;
			that.events();

		});
	},

	save:function() {
		var that = this;
		$('a.save-form').on('click', function(e) {
			e.preventDefault();
			var success = that.validate();
			var obj = [];
			var form = [];
			if(success) {

				obj['title'] = $('input[name="title"]').val();
				obj['name'] = $('input[name="page"]').val();
				obj['published'] = $('select[name="published"]').val();

				$('.form-item.new-component').each(function() {
					var componentName = $(this).find('.select select').val();

						obj['component'] = componentName;
						obj['widget'] = $('.block.wedge-' + $(this).data('widget')).index();

					$(this).find('.component-config.active input').each(function() {
							obj[$(this).data('for')] = $(this).val();
					});

					form.push(obj);
					obj = false;
					obj = [];

				});
				
				var data = {};

				data['name'] = form[0].name;
				data['title'] = form[0].title;
				data['published'] = $('select[name="published"]').val();

				for (var prop in form) {
				    for (var i = form.length - 1; i >= 1; i--) {
				    	data[i] = "[";
				    	for(var intg in form[i]) {
				    		data[i] = data[i] + intg + ":" + form[i][intg] + ",";
				    	}
				    	data[i] = data[i] + "]";
				    };
				}
				that.publish(JSON.stringify(data));
			}
		});

		$('.form-item.required').on('click', function() { $(this).removeClass('validate'); })
	},

	publish:function(form) {
		var that = this;
		$.ajax({
		  type: "POST",
		  cache: false,
		  url: "/git/blocks/app/class/ajax.php",
		  data: {data: form},
		  dataType: "html",
		  success: function(data) {
		  	console.log(data);
		  	$('span.notification .inner').text('Success! Content has been saved').parent().addClass('success').removeClass('error hidden');
			that.hideNotification();
		  },
		  error: function(data) {
		  	console.log(data);
		  	$('span.notification .inner').text('Error! ' + data).parent().addClass('error').removeClass('success hidden');
			that.hideNotification();
		  }
		});
	},

	hideNotification:function() {
		setTimeout(function() {
			$('span.notification .inner').text('').parent().removeClass('error success').addClass('hidden');
		}, 8000);
	},

	validate:function() {
		var that = this;
		var success = true;
		count = 0;
		$('.form-item.required').each(function() {
			var item = $(this);
			if(!item.hasClass('placeholder-component')) {
			var componentName = item.find('.select select').val();
			var forms = item.find('select, textarea, input, option');
			if(forms.val() == "" || !forms.val() || forms.val() == 'noval') {
				item.addClass('validate');
				$('span.notification .inner').text('Error! One or more required fields empty').parent().addClass('error').removeClass('success hidden');
				that.hideNotification();
				success = false;
			}
		}

		});

		return success;
	},

	events:function() {
		var that = this;
		var select = $('select.select-component');
		var add = $('a.add-component');

		select.on('change', function(e) {

			var comcon = $(this).parent().parent().find('.component-config');
			comcon.removeClass('active').find('.form-item').addClass('placeholder-component');

			if($(this).val() == "noval") {
				comcon.removeClass('active');
			} else {
				var selected = $(this).parent().parent().find('.for-' + $(this).val());

				selected.addClass('active').find('.form-item').removeClass('placeholder-component');
				var title = $(this).find('option:selected').text();
				$('.block.wedge-' + (component - 1) + ' > label').text(title);
			}

		});


		$('body').on('click', 'a.hide-config', function(e) {
			e.preventDefault();
			var el = $(this);
			if(el.hasClass('state-open')) {
				el.removeClass('state-open').addClass('state-closed');
				el.text('Show');
				el.parent().find('.component-config').hide();
				
			} else {
				el.removeClass('state-closed').addClass('state-open');
				el.text('Hide');
				el.parent().find('.component-config').show();
			}
		});	
	}

};

page.init();
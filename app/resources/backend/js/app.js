	
	/*
	 * Evany CMS
	 * @version 1.0.1
	 * @author Jake Bown <jakebown@gmail.com>
	 */

	 var createModal = "#create";
	 var addField = "button.add-field";
	 var removeField = "button.remove-field";
	 var pageTitle = "input.page-title";
	 var endPointTitle = "input.endpoint-title";
	 var formSubmit = "form#new-component";
	 var fieldBody = ".field-body";
	 var fieldFirst = ".field-first";
	 var fieldTable = ".field-table";
	 var fieldError = ".component-error";
	 var formLoader = "img.eloader";
	 var pageLayout = ".page-layout";
	 var pageComponent = ".page-component";
	 var componentList = ".component-list";
	 var componentListLink = "a.add-component";
	 var saveLayoutButton = "a.save-layout";
	 var saveLayoutForm = "form.save-layout";
	 var fieldCount = 0;
	 var componentList = [];

	 var copy = $(fieldFirst).html();
	 copy = "<tr class=\"field\">" + copy + "</tr>";

	 var app = {

	 	init:function() {

	 	},

	 	openModal:function() {
	 		$('a[data-toggle="modal"]').on('click', function(e) {
	 			e.preventDefault();
	 			var modal = $(this).data('target');
	 			$(modal).removeClass('hidden');

	 		});

	 		$('a[data-dismiss="modal"]').on('click', function(e) {
	 			e.preventDefault();
	 			var modal = $(this).data('target');
	 			$(modal).addClass('hidden');
	 		});
	 	},

	 	createComponent:function() {
	 		var that = this;	

	 		$('body').on('click', addField, function(e) {
	 			e.preventDefault();
	 			fieldCount++;
	 			$(fieldBody).parent().append(copy);

	 			/* Update Field Names */
	 			$(fieldBody).find('.field:last-child input[name="fieldname-0"]').attr('name', 'fieldname-' + fieldCount);
	 			$(fieldBody).find('.field:last-child select[name="fieldtype-0"]').attr('name', 'fieldtype-' + fieldCount);
	 		});

	 		$('body').on('click', removeField, function(e) {
	 			e.preventDefault();
	 			fieldCount--;
	 			$(e.currentTarget).closest('tr').remove();
	 		});
	 	},

	 	pageName:function() {
	 		$(pageTitle).on('keyup', function(e) {
	 			var ep = $(this).val();
	 			ep = ep.replace(/[^a-z0-9\s]/gi, '');
	 			ep = ep.replace(/\s/g, '_');
	 			$(endPointTitle).val(ep.toLowerCase());
	 		});
	 	},

	 	pageTooltip:function() {
	 		$(pageComponent).popover({
	 			html: true
	 		});
	 	},

	 	saveLayout:function() {
	 		var that = this;
	 		$(saveLayoutButton).on('click', function(e) {
	 			e.preventDefault();
	 			$(saveLayoutForm).submit();
	 			return false;
	 		});
	 	},

	 	pageAppend:function() {
	 		var that = this;
	 		var content = "";
	 		var field = "";
	 		var blockId = "";
	 		var counter = 0;
	 		var elements = {
	 			input: "<input data-required class='page-element form-control' type='text'>",
	 			textarea: "<textarea data-required class='page-element form-control'></textarea>",
	 			boolean: "<input data-required type='checkbox' class='page-element form-control'>",
	 			integer: "<input data-required type='number' class='page-element form-control'>",
	 			comma: "<select data-required class='page-element form-control'>[data]</select>"
	 		};
	 		
	 		$(componentListLink).on('click', function(e) {
	 			e.preventDefault();
	 			var title = $(this).data('name');
	 			$(this).addClass('hidden');
	 			if(!componentList[title]) {
	 				componentList[title] = true;
		 			$(pageLayout).append('<li class="list-group-item">'+ $(this).data('name') +' <button class="button is-small component-delete">&times;</button><input type="hidden" value="'+$(this).data('name')+'" name="comp'+$(this).data('name')+counter+'"/></li>');
		 			content = "";
		 			counter++;
		 			that.deleteComponent(title);
		 		}
	 		});
	 	},

	 	deleteComponent:function(title) {
	 		var that = this;
	 		$('button.component-delete').on('click', function(e) {
	 			e.preventDefault();
	 			var component = $(this).parent().find('input[type="hidden"]').val();
	 			$(this).parent().remove();
	 			componentList[title] = false;
	 			$('a.list-group-item[data-name="'+component+'"]').removeClass('hidden');
	 		});
	 	},

	 	createCustom:function() {
	 		var that = this;
	 		var jsText = 'div.custom-css';
	 		var cssText = 'div.custom-js';
	 		var js = new CodeFlask;
	 		var css = new CodeFlask;
	 		if($(jsText).length > 0) {
	 			js.run(jsText, {
		            language: 'js'
		        });
	 		}

	 		if($(cssText).length > 0) {
	 			css.run(cssText, {
		            language: 'css'
		        });
	 		}
	 	},

	 	sortPage:function() {
	 		$(pageLayout).sortable({
		      revert: true
		    });

		    $(pageLayout).draggable({
		      connectToSortable: pageLayout,
		      helper: "clone",
		      revert: "invalid"
		    });
	 	},

	 	submitComponent:function(e) {
	 		var that = this;
	 		$(formSubmit).submit(function(e) {
	 			e.preventDefault();
	 			$(formLoader).addClass('shown');
	 			var form = $(this).serialize();
	 			$.post( "components/create", form).done(function(p) {
	 				setTimeout(function() {
	 					$(formLoader).removeClass('shown');
	 					var res = $.parseJSON(p);
	 					if(res.response.errors) {
	 						$(fieldError).find('span').text(res.response.text);
	 						$(fieldError).removeClass('hidden');
	 						console.log(res.response.text);

	 					} else {
	 						$(createModal).addClass('hidden');
	 						setTimeout(function() {
	 								location.reload();
	 						}, 600);
	 					}
	 				}, 800);
	 			});
	 		});
	 	}

	 };

	 $(document).ready(function() {
	 	app.init();
	 	app.createComponent();
	 	app.submitComponent();
	 	app.pageName();
	 	app.sortPage();
	 	app.pageAppend();
	 	app.saveLayout();
	 	app.createCustom();
	 	app.openModal();
	 });


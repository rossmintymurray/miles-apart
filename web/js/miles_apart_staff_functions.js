/*******************************************
*
* Create onload function for initialisation 
*
*******************************************/ 
function addLoadEvent(func) {
	var oldonload = window.onload;
		if(typeof window.onload != 'function') {
			window.onload = func;
		} else {
			window.onload = function() {
			oldonload();
			func();
		}
	}
}

/*******************************************
* Load the initialisations
*******************************************/ 
addLoadEvent(initialiseFormFocus);
addLoadEvent(initialiseDraggable);
addLoadEvent(ajaxLoadingImageDisplay);
addLoadEvent(initialiseFormCollections);
addLoadEvent(initialiseEmployeePaymentCalculator);
addLoadEvent(initialiseDatePickerSelections);
addLoadEvent(initialisePurchaseOrderSupplierSelect);
addLoadEvent(initialisePurchaseOrderConfirmationSupplierSelect);

//Set global timeout for onkeyup events
var globalTimeout = null; 

/*******************************************
*
* Set up functions 
*
*******************************************/ 
function initialiseFormFocus() {
    $(function(){
        $(document).on('focus','input[type=text]',function(){ this.select(); });
    });
}

/*******************************************
* Init draggable - for CSV import
*******************************************/ 
function initialiseDraggable() {

	$(function() {
		$( "#sortable" ).sortable({
			revert: true
		});

		$( "#draggable" ).draggable({
			connectToSortable: "#sortable",
			helper: "clone",
			revert: "invalid"
		});

		$( "ul, li" ).disableSelection();

		$( "#draggable2" ).draggable();

		$( ".draggable3" ).draggable({
			helper: "clone"
		});

		$( ".droppable" ).droppable({ drop: Drop });
  	});

	//Function for mapping csv headers with databse columns
	function Drop( event, ui ) {
		//Set the ids of each value
		var draggableId = ui.draggable.attr("id");
		var droppableId = $(this).attr("id");

		//Make UI changes to show effect
		$( this ).addClass( "btn-success column_to_import" );

		//Change table header for db header on page
		var html = $("#" + droppableId).text();
		$(html).text(draggableId);
		$("#" + draggableId).attr("class", "text-success");
		$("#" + droppableId).text(draggableId);

		$("#" + draggableId).css({"colour":"#ffffff","background-color":"#5cb85c", "border-color":"#4cae4c"})

		if (draggableId == "product_name") {

		}

	 	//Need to perform AJAX call to 
  	}
}

/*******************************************
* Admin sitewide loading image when ajax is called
*******************************************/ 
function ajaxLoadingImageDisplay() {
	//Set up the loader gif.
  	var loading = $("#loadingImage");
	
	//Show the loader gif when the ajax callback is started.
	$(document).ajaxStart(function () {
		//fade out the page
		$("#wrap").css("opacity", 0.3);
		loading.show();
		

	});

	//Hide the loader gif when the AJAX call back.
	$(document).ajaxStop(function () {
		
		//fade in the page
		$("#wrap").css("opacity", 1);
		loading.hide();
		
	});
}

/*******************************************
*
* Set up form collection handling
*
*******************************************/ 
/*******************************************
* First initialise - for existing collections
*******************************************/ 
function initialiseFormCollections() {
	
	//initialiseEmployeePaymentCalculator();
	var collectionHolder = "";
	
	//Iterate over all the 'form_list' 
	$(".form_list").each(function() {

		collectionHolder = $('#' + $(this).attr('id'));

		//Add a delete link to all of the existing tag form li elements
		$(this).find('li').each(function() {
	        addCollectionFormDeleteLink($(this));
	    });

	});

    $('.btn-add').click(function(e) {
    	var collectionHolder = $('#' + $(this).attr('data-target'));
    	
		// prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form 
        addCollectionForm(collectionHolder);
	});

    //If remove button is cliceked, remove from UI
 	$(document).on("click", ".btn-remove", function(event) {
 		
		var name = $(this).attr('data-related');

		$('*[data-content="'+name+'"]').remove();

		return false;
 	});	 	
}

/*******************************************
* Handle adding to the form
*******************************************/ 
function addCollectionForm(collectionHolder) {

	var prototype = collectionHolder.data('prototype');
	
	var form = prototype.replace(/__name__/g, collectionHolder.children().length);

	collectionHolder.append(form);

	//When a new prototype object is added we need to reinitialise the following so JS will work with the newly added prototype.
	initialiseEmployeePaymentCalculator();
	initialiseVanityURLCheck();
	purchaseOrderConfirmationProductCodeEntry();
}

/*******************************************
* Handle delete link
*******************************************/ 
function addCollectionFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<a href="#">delete this tag</a>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

/*******************************************
* Initialise payment calculator used in daily take business premises form
*******************************************/ 
function initialiseEmployeePaymentCalculator() {
  	//When employee hours are changed
  	$('.employee_payment_total_hours_input').keyup(function(event) {
	 	
	 	//Get the hours value
		var hours_value = $(this).val();

		//Get the ID of the div that needs to be updated
		var divId = $(this).attr('id');
		divId = divId.match(/\d+/);

		//Append the div id to the existing ID
		var employeeDiv = "#milesapart_staffbundle_dailytakebusinesspremises_employee_payment_employee__payment" + divId + "_employee";
		var employee_id = $(employeeDiv).val();
		divId = "#milesapart_staffbundle_dailytakebusinesspremises_employee_payment_employee__payment" + divId + "_employee_payment_total";

		//Check global timeout so the function doesn't start immidiately
		if (globalTimeout != null) {
			clearTimeout(globalTimeout);
		}
		globalTimeout = setTimeout(function() {
			globalTimeout = null;
			//Ajax call to send employee and hours and return total value.
			$.ajax({
				type: "POST",
				url: window.location.protocol + "//" + window.location.host + "/Miles-Apart/web/app_dev.php/staff/finances/employee-payment-calculator",
				dataType: "json",
				data: {hours_value : hours_value, employee_id : employee_id},
				success : function(data) 
				{		
					$(divId).val(data['employee_total_pay']);	
				}, 
				fail: function() {
					alert('failed');
				}
			});
		}, 500);
  	});
}

/*******************************************
* Initialise DatPickerSelection for view daily takes
*******************************************/ 
function initialiseDatePickerSelections() {
	$('#startdatepicker').change(function() {
		var value = $('#startdatepicker').val();
		$('#enddatepicker').val(value);
	});

	$('#enddatepicker').change(function() {
		var start_date = $('#startdatepicker').val();
		var end_date = $('#enddatepicker').val();  
	});

	$('#comparestartdatepicker').change(function() {
		var value = $('#comparestartdatepicker').val();
		$('#compareenddatepicker').val(value);
	});

	$('#compareenddatepicker').change(function() {
		var start_date = $('#comparestartdatepicker').val();
		var end_date = $('#compareenddatepicker').val();  
	});
	 
}

/*******************************************
* Set up purchase order supplier select (for dislaying products so they can be added to a purchase order)
*******************************************/ 
function initialisePurchaseOrderSupplierSelect() {
  	//When the supplier is selected, initiate function.
  	$('#milesapart_staffbundle_purchaseordersselectsupplier_supplier').change(function() {

	 	//Set the supplier id from the select.
		var supplierId = $('#milesapart_staffbundle_purchaseordersselectsupplier_supplier').val();

		//Call the AJAX query.
		//Make AJAX call to update database
		$.ajax({
			type: "POST",
			url: globalBaseUrl + "purchase-orders/new-supplier-purchase-order/edit-order",
			dataType: 'html',
			data: { supplier_id: supplierId  },
			success: function(data){
				//Load returned HTML into page.
				$('#supplier_purchase_order_page_content').html(data);
			}, 
			fail: function() {
			 	alert('failed');
		  	}
		//End of AJAX.
		}); 
	//End of change function.
	});
}

/*******************************************
* Set up purchase order CONFIRMATION supplier select (manually adding products from an offline purchase order)
*******************************************/ 
function initialisePurchaseOrderConfirmationSupplierSelect() {
	//When the supplier is selected, initiate function.
	$('#milesapart_staffbundle_purchaseorderconfirmationselectsupplier_supplier').change(function() {

	 	//Set the supplier id from the select.
		var supplierId = $('#milesapart_staffbundle_purchaseorderconfirmationselectsupplier_supplier').val();

		//Call the AJAX query.
		//Make AJAX call to update database
		$.ajax({
			type: "POST",
			url: globalBaseUrl + "purchase-orders/process-purchase-order-confirmation/supplier-select",
			dataType: 'html',
			data: { supplier_id: supplierId  },
			success: function(data){
				//Load returned HTML into page.
				$('#purchase_order_confirmation_supplier_selected_content').html(data);
			}, 
		  	fail: function() {
			 	alert('failed');
		  	}
		//End of AJAX.
		}); 
	//End of change function.
	});
}

/*******************************************
* Set up to title case to capitalise string
*******************************************/ 
String.prototype.toTitleCase = function() {

	var i, j, str, lowers, uppers;
	str = this.replace(/([^\W_]+[^\s-]*) */g, function(txt) {
		return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
	});

  	// Certain minor words should be left lowercase unless 
  	// they are the first or last words in the string
  	lowers = ['A', 'An', 'The', 'And', 'But', 'Or', 'For', 'Nor', 'As', 'At', 
  	'By', 'For', 'From', 'In', 'Into', 'Near', 'Of', 'On', 'Onto', 'To', 'With'];
  	for (i = 0, j = lowers.length; i < j; i++)
	 	str = str.replace(new RegExp('\\s' + lowers[i] + '\\s', 'g'), 
		function(txt) {
		  	return txt.toLowerCase();
		});

  	// Certain words such as initialisms or acronyms should be left uppercase
  	uppers = ['Id', 'Tv'];
  	for (i = 0, j = uppers.length; i < j; i++)
	  	str = str.replace(new RegExp('\\b' + uppers[i] + '\\b', 'g'), 
		 	uppers[i].toUpperCase());

  	return str;
}
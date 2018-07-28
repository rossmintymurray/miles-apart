/*********************************************
** This code pulls intit argumants from header 
*********************************************/
var environment = document.currentScript.getAttribute('data-environment');   
if (typeof environment === "undefined" ) {
   var environment = 'prod';
}

//Define the base URL that will be used for all calls - depending on environment
//UPDATET HIS WHEN ON PRODUCTION SERVER
if(environment == "dev") {
    var globalBaseUrl = "http://localhost:8888/Miles-Apart/web/app_dev.php/staff/";
} else if (environment == "test") {
    var globalBaseUrl = "http://test.miles-apart.com/app_test.php/staff/";
} else {
    var globalBaseUrl = "https://www.miles-apart.com/staff/";
}

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
addLoadEvent(findSupplierSubmit);
addLoadEvent(findCustomerSubmit);
addLoadEvent(findSupplierRepresentativeSubmit);


//Set up the handles for adding products to list
addLoadEvent(processTransferRequestAddFormSubmit);
addLoadEvent(processStocktakeFormSubmit);
addLoadEvent(processSeasonalStorageBoxAddFormSubmit);
addLoadEvent(processAddProductToPrintListAddFormSubmit);

addLoadEvent(processAddProductToPurchaseOrderFormSubmit);


addLoadEvent(processStoreDeliveryFormSubmit);
addLoadEvent(processViewSeasonalStorageBoxSubmit);
addLoadEvent(initialiseVanityURLCheck);
addLoadEvent(purchaseOrderConfirmationProductCodeEntry);
addLoadEvent(initialiseProductSearchAutoComplete);
addLoadEvent(initialiseStorageBoxProductSearchAutoComplete);

//Set global timeout for onkeyup events
var globalTimeout = null; 






//THIS IS ONLY USED FOR TRANSFER REQUEST - THINK ABOUT ONE FOR ALL ADD proDUCT TO LIST SEARCHES - AND USE TIMEOUT
//Initialise on key down for product search
function initialiseProductSearchAutoComplete() {

  	var content = false;
	$( "#milesapart_staffbundle_producttransferrequest_product_name").keyup(function( event ) {
		searchText = $('#milesapart_staffbundle_producttransferrequest_product_name').val();
		if (searchText != "") {
			$.ajax({
				type: "POST",
				url: globalBaseUrl + "products/product-search",
				dataType: "json",
				data: {searchText : searchText},
				success : function(data) 
				{
					var table = '<h3>' + data['products'].length + ' Products Matched</h3><table class="table table-striped small" id="matched_barcode_products"><thead><tr><th>Product Name</th><th>Price</th><th>Supplier Code</th><th>Barcode</th><th>Supplier</th></tr></thead><tbody>';
					var tr ="";
					for (i=0; i < data['products'].length ; i++) {
					 	content = true;
						tr += '<tr onclick="addProductToTransferRequest(' + data['products'][i]['product_id'] +') ">' ;
						// create a new textInputBox  
						var selectInput = '<input type="checkbox" id="" name="" title="" />';  
						// create a new Label Text
						tr += '<td>' + data['products'][i]['product_name']  + '</td>';
						tr += '<td>' + data['products'][i]['product_price']  + '</td>'; 
						tr += '<td>' + data['products'][i]['product_supplier_code']  + '</td>';
						tr += '<td>' + data['products'][i]['product_barcode']  + '</td>';
						tr += '<td>' + data['products'][i]['supplier_name']  + '</td>'; 
						tr +='</tr>';
					}

					table = table + tr + "</tbody></table>";

					$('#found_products_display').html(table);
					$('#found_products_display').slideDown('fast');

				
				}, 
				fail: function() {
					alert('failed');
				}
			});
		} else {
			$('#found_products_display').slideUp('fast');
		}

	});

//THESE HIDE THE RESULTS WHEN THE FOCUS CHANGES - THEY NEED TO BE UPDATES AS IT IS NOT USABEL
 $( "#milesapart_staffbundle_producttransferrequest_product_name" ).focus(function( event ) {
  if (content == true) {
	 $('#found_products_display').slideDown('fast');
  }
	 
  });

  $( "#milesapart_staffbundle_producttransferrequest_product_name" ).blur(function( event ) {
	 $('#found_products_display').slideUp('fast');
  });

  $( "#milesapart_staffbundle_producttransferrequest_product_barcode" ).focus(function( event ) {
	 $('#found_products_display').slideUp('fast');
  });

}

//Initialise on key down for product search
function initialiseStorageBoxProductSearchAutoComplete() {

  var content = false;
	$( "#milesapart_staffbundle_addproducttoseasonalstoragebox_product_name" ).keyup(function( event ) {
		searchText = $('#milesapart_staffbundle_addproducttoseasonalstoragebox_product_name').val();
		if (searchText != "") {
			$.ajax({
				type: "POST",
				url: globalBaseUrl + "products/product-search",
				dataType: "json",
				data: {searchText : searchText},
				success : function(data) 
				{
					
					var table = '<h3>' + data['products'].length + ' Products Matched</h3><table class="table table-striped small" id="matched_barcode_products"><thead><tr><th>Product Name</th><th>Price</th><th>Supplier Code</th><th>Barcode</th><th>Supplier</th></tr></thead><tbody>';
					var tr ="";
					for (i=0; i < data['products'].length ; i++) {
					 content = true;
						tr += '<tr onclick="addToSeasonalStorageBox(' + data['products'][i]['product_id'] +') ">' ;
						// create a new textInputBox  
						var selectInput = '<input type="checkbox" id="" name="" title="" />';  
						// create a new Label Text
						tr += '<td>' + data['products'][i]['product_name']  + '</td>';
						tr += '<td>' + data['products'][i]['product_price']  + '</td>'; 
						tr += '<td>' + data['products'][i]['product_supplier_code']  + '</td>';
						tr += '<td>' + data['products'][i]['product_barcode']  + '</td>';
						tr += '<td>' + data['products'][i]['supplier_name']  + '</td>'; 
						tr +='</tr>';
					}

					table = table + tr + "</tbody></table>";

					$('#found_products_display').html(table);
					$('#found_products_display').slideDown('fast');

				
				}, 
				fail: function() {
					alert('failed');
				}
			});
		} else {
			$('#found_products_display').slideUp('fast');
		}

	});

 $( "#milesapart_staffbundle_addproducttoseasonalstoragebox_product_name" ).focus(function( event ) {
  if (content == true) {
	 $('#found_products_display').slideDown('fast');
  }
	 
  });

  $( "#milesapart_staffbundle_addproducttoseasonalstoragebox_product_name" ).blur(function( event ) {
	 $('#found_products_display').slideUp('fast');
  });

  $( "#milesapart_staffbundle_addproducttoseasonalstoragebox_product_barcode" ).focus(function( event ) {
	 $('#found_products_display').slideUp('fast');
  });

}



 

/*******************************************
* Function to initiate CSV import into DB & to display the output
*******************************************/ 
function submitProductListCSV() {
	
	//Get the headers 
	var columns = [];
	var columnCount = 0;

	//For each column, add to column array
	$(".column_to_import").each(function (index, item) {
		columns[index] = [$(this).text(), $(this).attr('id')];
		columnCount++;
  	});

	//Make the AJAX post.
  	$.ajax({
		type: "POST",
		url: "dedupe",
		dataType: 'html',
		data: { columns : columns },
		success: function(data){
		  	
		  	//var obj = jQuery.parseJSON(data);
		  	
			//$("#save_csv_test").html($('#"first_wrapper"' , data).html());
			//$("#import_area").html($('#duplicates_wrapper' , data).html());
			
			$("#import_area").html(data);
				
		}, 
		fail: function() {
		  alert('failed1');
		}
  	});
}

function submitDatePickerSelections(dates) {


  //Set values for the start and end date
  var start_date = dates[0];
  var end_date = dates[1];

  //Check if compare is set.
  if ($('input.compare').is(':checked')) {
	 var compare_start_date = $('#comparestartdatepicker').val();
	 var compare_send_date = $('#compareenddatepicker').val();
  } else {
	 var compare_start_date = null;
	 var compare_end_date = null;
  }


  //Call JavaScript to update the graphs, by reloading the page. 
  $.ajax({
	 type: "POST",
	 url: "view-daily-takes-by-date",
	 dataType: 'html',
	 data: { start_date : start_date, end_date : end_date, compare_start_date : compare_start_date, compare_end_date : compare_end_date },
	 success: function(data){
		  window.location.replace("view-daily-takes");
	 }, 
	 fail: function() {
		alert('failed');
	 }
  });
}

function submitAnalyseFinancesDatePickerSelections(dates) {


  //Set values for the start and end date
  var start_date = dates[0];
  var end_date = dates[1];

 

  //Check if compare is set.
  if ($('input.compare').is(':checked')) {
	 var compare_start_date = $('#comparestartdatepicker').val();
	 var compare_send_date = $('#compareenddatepicker').val();
  } else {
	 var compare_start_date = null;
	 var compare_end_date = null;
  }


  //Call JavaScript to update the graphs, by reloading the page. 
  $.ajax({
	 type: "POST",
	 url: "view-daily-takes-by-date",
	 dataType: 'html',
	 data: { start_date : start_date, end_date : end_date, compare_start_date : compare_start_date, compare_end_date : compare_end_date },
	 success: function(data){
		  window.location.replace("analyse-finances");

	 }, 
	 fail: function() {
		alert('failed');
	 }
  });
}

function toggleCompareDatePicker() {
  if ($('input.compare').is(':checked')) {
	 $("#compare_date_fields").slideUp('slow');
  } else {
	  $("#compare_date_fields").slideDown('slow');
  }
}

//Function for retrieving seasonal storage box contents once box code has been scanned.
function processViewSeasonalStorageBoxSubmit() {
  $("form[name='milesapart_staffbundle_viewstoragebox']").submit(function(e){
	 
	 e.preventDefault(); //prevent submit

	var boxCode = $("#milesapart_staffbundle_viewstoragebox_seasonal_storage_box_code").val();
	 //Call the serverr code and return the formatted table from in html
	$.ajax({
		type: "POST",
		url: "box-contents/submit",
		dataType: 'html',
		data: { box_code : boxCode },
		success: function(data){
		  $("#seasonal_box_contents_display").html(data);

		}, 
		fail: function() {
			alert('failed');
		}
  });
	 
  });
}

function emptySeasonalStorageBox(boxCode) {
	
	//Call the serverr code and return the formatted table from in html
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "products/empty-box",
		dataType: 'json',
		data: { box_code : boxCode },
		success: function(data){
		  alert('worked');

		}, 
		fail: function() {
			alert('failed');
		}
  });
	 

}

function processAmazonProductAddFormSubmit(formUrl) {
  	$("form[name='milesapart_sellerbundle_amazonproductmodal']").submit(function(e){
		e.preventDefault(); //prevent submit
		alert("submitted");

		var formData = new FormData(this);
	    $.ajax({
	        url: formUrl,
	        type: 'POST',
	        data:  formData,
	        mimeType:"multipart/form-data",
	        contentType: false,
	        cache: false,
	        processData:false,
	        dataType: 'json',
	        success: function(data, textStatus, jqXHR)
	        {
	        	alert(data['status']);
	        	if(data['status'] == "success") {
	        		//Hide the modal
	        		$('#amazon_product_modal').hide();

	        		alert("the product has been added to the database");
	        		/*swal({
						title: 'Product has been added.',
						text: "Please click the button below if you wish to edit in depth information for the product you just added",
						type: 'success',
						//showCancelButton: true,
						confirmButtonColor: '#3085d6',
						//cancelButtonColor: '#d33',
						confirmButtonText: 'Edit details'
					/*}).then((result) => {
						if (result.value) {
						    window.open(data['editUrl']);
						  // result.dismiss can be 'cancel', 'overlay',
						  // 'close', and 'timer'
						  } else if (result.dismiss === 'cancel') {
						    swal(
						      'Cancelled',
						      'Your imaginary file is safe :)',
						      'error'
						    )
						}
						
					})
	*/
					

					//Need to add the proiduct to list as originally intended
					addProductToList(data['submitUrl'], data['functionName'], data['variablePrepend'], data['barcode']);
	        	}
	        	

	        	//Show the info alert box
	        },
	        error: function(jqXHR, textStatus, errorThrown)
	        {
	        }
	    });
	    e.preventDefault(); //Prevent Default action.
	    
	});

}



function processTransferRequestAddFormSubmit() {
  $("form[name='milesapart_staffbundle_producttransferrequest']").submit(function(e){
	 
	 e.preventDefault(); //prevent submit
	 processTransferRequestAdd();
	 
  });
}


function processTransferRequestAdd() {
  var submitUrl = globalBaseUrl + "transfer-requests/submit";
  var functionName = "TransferRequest";
  var variablePrepend = "product_transfer_request_";

  addProductToList(submitUrl, functionName, variablePrepend);
}

function newTransferRequestProduct() {

  var submitUrl = globalBaseUrl + "transfer-requests/new-product-submit";
  var functionName = "TransferRequest";
  var variablePrepend = "product_transfer_request_";

  newProductListProduct(submitUrl, functionName, variablePrepend);
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function processStocktakeFormSubmit() {
  $("form[name='stocktake_form']").submit(function(e){
	 
	 e.preventDefault(); //prevent submit
	 processStocktakeForm()
  });
}

function processStocktakeForm() {
	 //Get the barcode and check if it is a shelf or a product.
	 var barcode = $('#stocktake_barcode_input').val();
	 if(isNumeric(barcode)) { 
		
		//Check if shelf has been selected.

		//Add the product to the stocktake.
		processStocktakeAdd();

	//Check if the barcode has a '-' in the third character, and therfore a seasonal storage box.
	 } else if(barcode.charAt(2) == '-'){ 
	 	
	 	processStocktakeSeasonalStorageBoxAdd(barcode);

	 } else {

		//Cal the shelf AJAX code.
		//Create ajax call with array/JSON of ids, this will iterate thorough and added.
		$.ajax({
		  type: "POST",
		  url: globalBaseUrl + "products/select-stocktake-shelf",
		  dataType: 'json',
		  data: { barcode : barcode  },
		  success: function(data) { 
				
				  //Shelf item display
				  var html_content ="<h3>" + data['stock_location_shelf_code'] + " <small  class=\"page_sub_header\">in " + data['stock_location_name'] + ", " + data['business_premises_name'] + ".</small></h3>";
					 
					 //Check if existiong products and if so make the table.
					 if (data['stocktake_products']['products'].length > 0) {
						
						//Initiate the table row.
						var table_row = "";
						for (i=0; i < data['stocktake_products']['products'].length; i++) {
						  //Make the table row.
						  table_row += "<tr id=\"product_row_" + data['stocktake_products']['products'][i]['prod_id'] + "\"><td>" + data['stocktake_products']['products'][i]['product_name'] + "</td><td>" + data['stocktake_products']['products'][i]['product_price'] +"</td><td>" + data['stocktake_products']['products'][i]['stocktake_product_qty'] + "</td><td>" + data['stocktake_products']['products'][i]['product_supplier_code'] + "</td><td>" + data['stocktake_products']['products'][i]['product_barcode'] + "</td><td>" + data['stocktake_products']['products'][i]['supplier_name'] + "</td></tr>";
						}
						
						 $("table > tbody").html(table_row);
					 }

					

				  $("#shelf_info").html(html_content);

			 

				//show the form field for product search
					$('#stocktake_search_field').slideDown('fast');

					//Reset the barcode and search fields
				$("#stocktake_barcode_input").val("");
				$("#stocktake_search_input").val("");
			}, 
		  fail: function() {
			 alert('Ajax failed');
		  }

	//End of AJAX function
	 });

  //End of if numeric
  }
	 
  
}

function processStocktakeSeasonalStorageBoxAdd(barcode) {
	//Create ajax call with array/JSON of ids, this will iterate thorough and added.
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "products/add-stocktake-seasonal-storage-box/submit",
		dataType: 'json',
		data: { barcode : barcode  },
		success: function(data) { 
			alert(data['seasonal_storage_box_code']);
			//Reset the barcode and search fields
			$("#stocktake_barcode_input").val("");
			$("#stocktake_search_input").val("");
		}, 

	  	fail: function() {
		 	alert('Ajax failed');
	  	}

	//End of AJAX function
	});
}

function processStocktakeAdd() {
  var submitUrl = globalBaseUrl + "products/add-stocktake-product/submit";
  var functionName = "StocktakeProduct";
  var variablePrepend = "stocktake_product_";

  addProductToList(submitUrl, functionName, variablePrepend);
}

function newStocktakeProduct() {

  var submitUrl = globalBaseUrl + "products/new-product-submit";
  var functionName = "Stocktake";
  var variablePrepend = "stocktake_product_";

  newProductListProduct(submitUrl, functionName, variablePrepend);
}

function processAddProductToPrintListAddFormSubmit() {
  $("form[name='milesapart_staffbundle_addproducttolist']").submit(function(e){
	 
	 e.preventDefault(); //prevent submit
	 processAddProductToPrintListAdd();
	 
  });
}

function processAddProductToPrintListAdd() {
  var submitUrl = globalBaseUrl + "submit";
  var functionName = "PrintRequest";
  var variablePrepend = "print_request";

  //Get the print request type
  //var printRequestType = 

  addProductToList(submitUrl, functionName, variablePrepend);
}

function newAddProductToPrintListProduct() {

  var submitUrl = globalBaseUrl + "/new-product-submit";
  var functionName = "PrintRequest";
  var variablePrepend = "print_request_";

  newProductListProduct(submitUrl, functionName, variablePrepend);
}

function processAddProductToPurchaseOrderFormSubmit() {
  $("form[name='milesapart_staffbundle_addproducttopurchaseorder']").submit(function(e){
	 
	 e.preventDefault(); //prevent submit
	 processAddProductToPurchaseOrderAdd();
	 
  });
}

function processAddProductToPurchaseOrderAdd() {
	
	//Check if barcode was submitted, or search strings
	if ($(".add_product_to_list_product_barcode").val() == "") {
		
		if ($(".add_product_to_list_product_supplier_code").val() != "") { 
			var product_supplier_code = $(".add_product_to_list_product_supplier_code").val();
	 	} else {
			var product_supplier_code = null;
	  	}

	  	if ($(".add_product_to_list_product_name").val() != "") { 
			var product_search_string = $(".add_product_to_list_product_name").val();
	 	} else {
			var product_search_string = null;
	  	}

	  	//Get the products that could match and display for user to select
	  	$.ajax({
			type: "POST",
			url: globalBaseUrl + "purchase-orders/add-product-to-purchase-order/find-product",
			dataType: 'json',
			data: { product_supplier_code : product_supplier_code, product_search_string: product_search_string  },
			success: function(data){
		  		$(".add_product_to_list_product_name").val("");
		  		$(".add_product_to_list_product_supplier_code").val("");
		  		
		  		//Show the found products so one can be selected
		  		//Show area 
		  		$("#found_products_display").slideDown('fast');
		  		$("#found_products_display").html("<h4>Found products</h4>");
		  		
		  		//Create the table header
		  		$("#found_products_display").html("<table id=\"found_products_table\" class=\"table table-striped small\"><thead></thead><tbody></tbody><table>");
		  		
		  		//Show each product row, with option to select
		  		var table_row = "";
		  		for(i=0;i < data['products'].length; i++){
					console.log(data['products'][0]);
					//Make the table row.
					table_row += "<tr id=\"product_row_" + data['products'][i] + "\"><td>" + data['products'][i]['product_name'] + "</td><td>" + data['products'][i]['product_price'] +"</td><td>" + data['products'][i]['product_supplier_code'] + "</td><td>" + data['products'][i]['product_supplier'] + "</td><td><a href=\"#\" onclick=\"selectFoundProductForPurchaseOrder(" + data['products'][i]['product_barcode'] + ")\"><span class=\"label label-primary\">Select</span></a></td></tr>";
				}
				
				//Apend the rows to the table	
				$("#found_products_table > tbody").html(table_row);
		  		
			}, 
		  	fail: function() {
			 	alert('Ajax failed');
		  	}

		//End of AJAX function
		});

	//If the barcode is set
	} else {

		var submitUrl = globalBaseUrl + "purchase-orders/add-product-to-purchase-order/submit";
		var functionName = "PurchaseOrderProduct";
		var variablePrepend = "purchase_order_product";

		addProductToList(submitUrl, functionName, variablePrepend);
	}
}

function selectFoundProductForPurchaseOrder(barcode) {
	
	//Put the barcode in the form 
	$(".add_product_to_list_product_barcode").val(barcode);

	//Submit the form 
	processAddProductToPurchaseOrderAdd();

	$("#found_products_display").slideUp('fast');
}

function newAddProductToPurchaseOrderProduct() {

  var submitUrl = globalBaseUrl + "new-product-submit";
  var functionName = "PurchaseOrderProduct";
  var variablePrepend = "purchase_order_product_";

  newProductListProduct(submitUrl, functionName, variablePrepend);
}
  
function processSeasonalStorageBoxAddFormSubmit() {
  $("form[name='milesapart_staffbundle_addproducttoseasonalstoragebox']").submit(function(e){
	 
	 e.preventDefault(); //prevent submit
	 
	 processSeasonalStorageBoxAdd();
  });
}

function processSeasonalStorageBoxAdd() {
  var submitUrl = globalBaseUrl + "add-product-to-seasonal-storage-box/submit";
  var functionName = "SeasonalStorageBox";
  var variablePrepend = "stocktake_product_";

  addProductToList(submitUrl, functionName, variablePrepend);
}

function submitMultipleProductsSelection() {
	//Get the ids of each product selected and add them to the transfer request (create new transfer request product).
	//Find the selected products
	var selected = [];
	$('#matched_barcode_products input:checked').each(function() {
		 selected.push($(this).attr('id'));
	});

	var selectedString = "";
	for (i=0; i < selected.length; i++) {
		if (i +1 == selected.length){
		selectedString += selected[i];
		} else {
			selectedString += selected[i] + "-";
		}
	}

	//Create ajax call with array/JSON of ids, this will iterate thorough and added.
	$.ajax({
		  type: "POST",
		  url: globalBaseUrl + "multiple-products-select-submit",
		  dataType: 'json',
		  data: { selected_string : selectedString  },
		  success: function(data){
				for (i=0; i < data["product_transfer_request"].length; i++) {

					//Create the form rows
					 var formRow = "<tr id=\"" + data['product_transfer_request_id'] + "\"  class=\"new-table-add\"><td>" + data["product_transfer_request"][i]['product_name'] + "</td><td>" +
						  data["product_transfer_request"][i]['product_price'] + "</td><td>" + data["product_transfer_request"][i]['product_transfer_request_qty'] + "</td><td>" + data["product_transfer_request"][i]['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery\">" + data["product_transfer_request"][i]['product_barcode'] + "</td><td>" + data["product_transfer_request"][i]['supplier_name'] + "</td></tr>";
					$(formRow).prependTo("#existing_transfer_request_products").find('*').effect("highlight", {color:"#71a01e"}, 2000);

				  $(".hide_on_xs_jquery").addClass("hide_on_xs");
				  $(".hide_on_non_xs_jquery").addClass("hide_on_non_xs");

				}

				//Hide the table
					$('#found_products_display').slideUp('fast');

					//Reset the barcode and search fields
				$("#milesapart_staffbundle_producttransferrequest_product_barcode").val("");
				$("#milesapart_staffbundle_producttransferrequest_product_name").val("");
			}, 
		  fail: function() {
			 alert('Ajax failed');
		  }

	//End of AJAX function
	 });
}

function addProductToTransferRequest(productId) {
	//Create ajax call with array/JSON of ids, this will iterate thorough and added.
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "multiple-products-select-submit",
		dataType: 'json',
		data: { selected_string : productId  },
		success: function(data){
			

				//Create the form rows
				var formRow = "<tr id=\"" + data['product_transfer_request_id'] + "\" class=\"new-table-add\"><td>" + data["product_transfer_request"][0]['product_name'] + "</td><td>" +
				data["product_transfer_request"][0]['product_price'] + "</td><td>" + data["product_transfer_request"][0]['product_transfer_request_qty'] + "</td><td>" + data["product_transfer_request"][0]['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery\">" + data["product_transfer_request"][0]['product_barcode'] + "</td><td>" + data["product_transfer_request"][0]['supplier_name'] + "</td></tr>";
				$(formRow).prependTo("#existing_transfer_request_products").find('*').effect("highlight", {color:"#71a01e"}, 2000);

				$(".hide_on_xs_jquery").addClass("hide_on_xs");
				$(".hide_on_non_xs_jquery").addClass("hide_on_non_xs");

			

			//Hide the table
			$('#found_products_display').slideUp('fast');

			//Reset the barcode and search fields
			$("#milesapart_staffbundle_producttransferrequest_product_barcode").val("");
			$("#milesapart_staffbundle_producttransferrequest_product_name").val("");


		}, 
		fail: function() {
			alert('Ajax failed');
		}

	//End of AJAX function
	});
}

function updateProductTransferQty(productTransferRequestId) {
  var productTransferNewQty = $("#product_transfer_new_qty").val();
  
  //Make AJAX call to update database
  $.ajax({
		  type: "POST",
		  url: globalBaseUrl + "new-qty-submit",
		  dataType: 'json',
		  data: { new_qty: productTransferNewQty, product_transfer_request_id: productTransferRequestId  },
		  success: function(data){
			 if (data["success"] == true) {
				//Find the row of the updated product, NOT WORKING AT THE MOMENT!!!!!!!! IT JUST HIGHLIGHTS LAST ROW

				//Update the qty in the table
				$('table > tbody > tr:nth-child(1) > td:nth-child(3)').html(productTransferNewQty);

				//Highlight the row.
				$("table > tbody > tr:nth-child(1)").find("*").effect("highlight", {color:"#71a01e"}, 2000);

				//Ensure the box is green
			 } else {
				alert("Qty not updated");
			 }
		  }, 
		  fail: function() {
			 alert('failed');
		  }
  });

}



function newProductTransferProduct(){
  var newProductName = $("#new_product_name").val().toTitleCase();
  var newProductTransferQty = $("#new_product_transfer_qty").val();
  var newProductTransferProductSupplierCode = $("#new_product_product_supplier_code").val().toUpperCase();
  var newProductTransferProductPrice = $("#new_transfer_product_price").val();
  var newProductBarcode = $("#new_product_barcode").html();

	if (newProductTransferProductSupplierCode == "SUPPLIER CODE...") {
	  newProductTransferProductSupplierCode = null;
  }

  if (newProductTransferProductPrice == "Price...") {
	  newProductTransferProductPrice = null;
  }

 if (newProductName != "Please type product name..." && newProductTransferQty != "Qty...") {
	 

	 //Make AJAX call to update database
	 $.ajax({
		  type: "POST",
		  url: globalBaseUrl + "new-product-submit",
		  dataType: 'json',
		  data: { new_product_name: newProductName, new_product_transfer_qty : newProductTransferQty, new_product_barcode: newProductBarcode, new_product_product_supplier_code : newProductTransferProductSupplierCode, new_transfer_product_price: newProductTransferProductPrice },
		  success: function(data){
			 //First the pop up
			 if (data["success"] == true) {

				var popupContent = "<div class=\"row\"><div class=\"col-md-4 col-xs-10\"><h6>Product Name</h6><h5>" + data['product_name'] + "</h5></div><div class=\"hide_on_non_xs_jquery col-xs-1\"><a href=\"#\" class=\"popup_close\" onclick=\"hidePopup();\"><span class=\"glyphicon glyphicon-remove\"></span> </a></div><div class=\"col-md-2 col-xs-6\"><h6>Request Qty</h6><input class=\"col-md-6 col-xs-6 form-control\"  id=\"product_transfer_new_qty\" type=\"text\" value=\"" + data['product_transfer_request_qty'] + "\" /></div><div class=\"col-md-2 col-xs-6\"><h6>Supplier Code</h6><h5>" + data['product_supplier_code'] + "</h5></div><div class=\"col-md-2 col-xs-6\"><h6>Barcode</h6><h5>" + data['product_barcode'] + "</h5></div><span style=\"display:none\" id=\"latest_product_transfer_request_id\">"+ data['product_transfer_request_id'] + "</span><div class=\"col-md-1 col-md-offset-1 col-xs-6\"><a href=\"#\" class=\"btn btn-default col-xs-12\" style=\"margin-top:10px\" onclick=\"updateProductTransferQty()\">Update</a></div></div>";
				//if ($("#popup_form_response").css('background-color') == 'rgba(219, 41, 37, .9)') {
				  $('#popup_form_response').animate({backgroundColor: 'rgba(113, 160, 30, .97)'}, 500);
				//}
				$("#popup_form_response_content").html(popupContent);
				$("#popup_form_response").slideDown('slow', function() {
				  //$( "#product_transfer_new_qty" ).focus();
				});
				

				//Then the form row
			 
				  var formRow = "<tr id=\"" + data['product_transfer_request_id'] + "\"  class=\"new-table-add\"><td>" + data['product_name'] + "</td><td>" +
						data['product_price'] + "</td><td>" + data['product_transfer_request_qty'] + "</td><td>" + data['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery\">" + data['product_barcode'] + "</td><td>" + data['supplier_name'] + "</td></tr>";
				 
				$(formRow).prependTo("table > tbody").find('*').effect("highlight", {color:"#71a01e"}, 2000);

				$(".hide_on_xs_jquery").addClass("hide_on_xs");
				$(".hide_on_non_xs_jquery").addClass("hide_on_non_xs");

			 

		  } else {
			 alert("failed at the server");
		  }
		  }, 
		  fail: function() {
			 alert('failed');
		  }

  });

  } else {
	 alert("Please ensure the product name and required qty is added.");
  }
}



//Supplier serach
function findSupplierSubmit() {
  	$( "#milesapart_staffbundlefindsupplier_supplier_name" ).keyup(function( event ) {
	  	//Set to delay to ensure typing has finished
	  	if (globalTimeout != null) {
			clearTimeout(globalTimeout);
		}
		globalTimeout = setTimeout(function() {
			globalTimeout = null;
			var searchString = $('#milesapart_staffbundlefindsupplier_supplier_name').val();

			$.ajax({
				type: "POST",
				url: globalBaseUrl + "suppliers/find-suppliers/submit",
				dataType: 'html',
				data: { searchString : searchString  },
				success: function(data){
					$("#suppliers_table").html(data);
				  
				 
				}, 
				fail: function() {
					alert('failed');
				}
			});
		}, 500);
  });
}

//Customer serach
function findCustomerSubmit() {
	$(".customer_search_field").keyup(function( event ) {
	  	//Set to delay to ensure typing has finished
	  	if (globalTimeout != null) {
			clearTimeout(globalTimeout);
		}
		globalTimeout = setTimeout(function() {
			globalTimeout = null;
	      
	      	//Create
	    	
			var customer_name = $('#milesapart_staffbundlefindcustomer_customer_name').val();
			var customer_email = $('#milesapart_staffbundlefindcustomer_customer_email').val();
			var business_name = $('#milesapart_staffbundlefindcustomer_business_name').val();
		 
			$.ajax({
				type: "POST",
				url: globalBaseUrl + "view-customers/search",
				dataType: 'html',
				data: { customer_name : customer_name, customer_email: customer_email, business_name: business_name },
				success: function(data){
					$("#customers_table").html(data);
				  
				 
				}, 
				fail: function() {
					alert('failed');
				}
			}); 
		}, 500 );

	  	
	});
}


//Supplier serach
function findSupplierRepresentativeSubmit() {
  $( "#milesapart_staffbundlefindsupplierrepresentative_supplier_representative_full_name" ).keyup(function( event ) {
	 var search_string = $('#milesapart_staffbundlefindsupplierrepresentative_supplier_representative_full_name').val().toString();

	 $.ajax({
			 type: "POST",
			 url: globalBaseUrl + "suppliers/find-supplier-representatives/submit",
			 dataType: 'html',
			 data: { search_string : search_string  },
			 success: function(data){
				  $("#supplier_representatives_table").html(data);
				  
				 
			 }, 
			 fail: function() {
				alert('failed');
			 }
		});
  });
}

//NEW SEASONAL STORAGE BOX CREATION
function newSeasonalStorageBox(printer) {

  //First request the new box to be created with an ajax request
  $.ajax({
			 type: "POST",
			 url: globalBaseUrl + "new-seasonal-storage-box",
			 dataType: 'json',
			 data: { },
			 success: function(data){
				 

				  
				  /*
				  //Creates the label on the server side 
				  $("#barcode").JsBarcode(data['new_box_code'],{
					 width:2,
					 height:100,
					 quite: 10,
					 format:"CODE128",
					 backgroundColor:"#fff",
					 lineColor:"#000"
				  });
				  */
				  if(printer == "local") {
					 printSeasonalBoxLabelLocal(data['new_box_code']);
					 //printSeasonalBoxLabel("26AM-FR-N-7-3");
				  } else {
					 printSeasonalBoxLabelShared(data['new_box_code']);
				  }
				 
			 }, 
			 fail: function() {
				alert('failed');
			 }
		});

  //Receive the box code and barcode image url back from the JAX response


  //Create the Dymo label print


  //Update the page with barcide and allow scanning/searching of products

}



function hidePopup() {
  $("#popup_form_response").slideUp('slow');
}











function addSelectedMultipleProductsToList(submitUrl, variablePrepend) {
	//Get the ids of each product selected and add them to the transfer request (create new transfer request product).
	//Find the selected products
	 
	var selected = [];
	$('#matched_barcode_products input:checked').each(function() {
		 selected.push($(this).attr('id'));
	});

	var selectedString = "";
	for (i=0; i < selected.length; i++) {
		if (i +1 == selected.length){
		selectedString += selected[i];
		} else {
			selectedString += selected[i] + "-";
		}
	}

	//Create ajax call with array/JSON of ids, this will iterate thorough and added.
	$.ajax({
		  type: "POST",
		  url: submitUrl + "-multiple-products",
		  dataType: 'json',
		  data: { selected_string : selectedString  },
		  success: function(data){
			 alert(data['product_list'].length);
				for (i=0; i < data['product_list'].length; i++) {
				  alert(data['product_list'].length);
					//Create the form rows
					 var formRow = "<tr id=\"" + data['product_list'] + "\"  class=\"new-table-add\"><td>" + data['product_list'][i]['product_name'] + "</td><td>" + data['product_list'][i]['product_price'] + "</td><td>" + data['product_list'][i]['stocktake_product_qty'] + "</td><td>" + data['product_list'][i]['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery\">" + data['product_list'][i]['product_barcode'] + "</td><td>" + data['product_list'][i]['supplier_name'] + "</td></tr>";
					$(formRow).prependTo("table > tbody").find('*').effect("highlight", {color:"#71a01e"}, 2000);

				  $(".hide_on_xs_jquery").addClass("hide_on_xs");
				  $(".hide_on_non_xs_jquery").addClass("hide_on_non_xs");
				  alert("should be ok");
				}

				//Hide the table
					$('#found_products_display').slideUp('fast');

					//Reset the barcode and search fields
				$(".add_product_to_list_product_barcode").val("");
				$("add_product_to_list_product_name").val("");
			}, 
		  fail: function() {
			 alert('Ajax failed');
		  }

	//End of AJAX function
	 });
}





$(function(){       
	 $('*[data-href]').click(function(){
		  window.location = $(this).data('href');
		  return false;
	 });
});


function sendPurchaseOrder(id) {

	 //Call ajax function to get the product, add it to the purchase order and return success or failure, and data, on the product added.

	 //Check if order minimum value is reached, if known.

		//Show confirm box if no.
		var redirect = false;
		if (confirm("Would you like to proceed?")) {
		  
		  redirect = true;
		} else {
		  
		}
		//Show confirm box if the minimum value has been reached.


	 //If confirm ok, proceed to send the order.
	 if (redirect == true) {
		//Redirect 
		window.location.replace("send-purchase-order/" + id);
	 } else {

	 }

  }


/************************************************
* Pick and pack functions
************************************************/
addLoadEvent(processPickProductSubmit);
addLoadEvent(processPackProductSubmit);

function processPickProductSubmit() {
	$("form[name='milesapart_staffbundle_pickproduct']").submit(function(e){
 
	 	e.preventDefault(); //prevent submit
	 	processPickProductForm();
	});
}

function processPackProductSubmit() {
	$("form[name='milesapart_staffbundle_packproduct']").submit(function(e){
 
 		e.preventDefault(); //prevent submit
 		processPackProductForm();
	});
}

function processPickProductForm() {

	//Check the barcode exists
	if ($("#milesapart_staffbundle_pickproduct_product_barcode").val() != "") {
		 var barcode = $("#milesapart_staffbundle_pickproduct_product_barcode").val();
	  } else {
		 var barcode = null;
		 alert("Please enter a barcode");
		 return false;
	  }

	  var order_id = $("#pick_product_order_id").html();
	  var remaining_qty = $("#pick_product_remaining_qty").html();

	  
//Make AJAX call to update database
  	$.ajax({
		  type: "POST",
		  url: globalBaseUrl + "pickpack/process-pick-product",
		  dataType: 'json',
		  data: { order_id: order_id, barcode: barcode  },
		  success: function(data){
		  	
			 if (data["success"] == true) {
				//Find the row of the updated product
				//Update the qty in the table and highlight that row
				
				$('#' +barcode).find("*").css("background-color", ":#71a01e");
				
				//Update the remaining qty.
				remaining_qty = remaining_qty - 1;
				
				$("#pick_product_remaining_qty").html(remaining_qty);

				//If no more remain, show the complete pick for this order button
				if(remaining_qty == 0) {
					$("#complete_order_pick_button").removeClass("disabled");
				}

				//Ensure the box is green
			 } else {
				alert("Qty not updated");
			 }
		  }, 
		  fail: function() {
			 alert('failed');
		  }
  	});
}

function processPackProductForm() {

	//Check the barcode exists
	if ($("#milesapart_staffbundle_packproduct_product_barcode").val() != "") {
		 var barcode = $("#milesapart_staffbundle_packproduct_product_barcode").val();
	  } else {
		 var barcode = null;
		 alert("Please enter a barcode");
		 return false;
	  }

	  var order_id = $("#pack_product_order_id").html();
	  var remaining_qty = $("#pack_product_remaining_qty").html();

//Make AJAX call to update database
  	$.ajax({
		  type: "POST",
		  url: globalBaseUrl + "process-pack-product",
		  dataType: 'json',
		  data: { order_id: order_id, barcode: barcode  },
		  success: function(data){
		  	
			 if (data["success"] == true) {
				//Find the row of the updated product
				//Update the qty in the table and highlight that row
				
				$('#' +barcode).find("*").css("background-color", ":#71a01e");

				//Update the remaining qty.
				remaining_qty = remaining_qty - 1;
				
				$("#pack_product_remaining_qty").html(remaining_qty);

				//If no more remain, show the complete pick for this order button
				if(remaining_qty == 0) {
					$("#complete_order_pack_button").removeClass("disabled");
				}

				//Ensure the box is green
			 } else {
				alert("Qty not updated");
			 }
		  }, 
		  fail: function() {
			 alert('failed');
		  }
  	});
}

function completeOrderPostage(orderId) {

	//Set the new variables in case the size of package does not match the db
	var newWeight = null;
	var newHeight = null;
	var newWidth = null;
	var newDepth = null;

	//First check if there are multiple items in the order
	if($("#pack_product_product_qty").html() > 1) {
		//Need to show dialogue to check that the calculated size and weight is accurate.
		//Get the calculated size and weight

		//Show the dialogue (allowing new va;ues to be entered).
	

		swal({
			title: 'New package details',
			html:
				'<p>Please enter the correct values below</p>'+
				'<form><div class="form-group">'+
				'<label for="swal-weight">Weight(g)</label>'+
				'<input id="swal-weight" class="swal2-input form-control" autofocus placeholder="" value="'+weight+'">' +
				'</div></form>'+
				'<label for="swal-height" style="text-align:left;">Height(mm)</label>'+
				'<input id="swal-height" class="swal2-input" placeholder="" value="'+height+'">' +
				'<label for="swal-width">Width(mm)</label>'+
				'<input id="swal-width" class="swal2-input" placeholder="" value="'+width+'">'+
				'<label for="swal-depth">Depth(mm)</label>'+
				'<input id="swal-depth" class="swal2-input" placeholder="" value="'+depth+'">',
			 	preConfirm: function() {
			   		return new Promise(function(resolve) {
				   		if (true) {
						    resolve();
					      	newWeight = document.getElementById('swal-weight').value;
							newHeight = document.getElementById('swal-height').value;
							newWidth = document.getElementById('swal-width').value;
							newDepth = document.getElementById('swal-depth').value;
						}
			  		});
			 	}
			}).then(function(result) {
		});
	} else {
		//Show the max dimensions for the SINGLE product package
		//swal({   title: "Check parcel size/weight",   text: "<p>Please ensure that the weight and dimensions of the parcel are no larger than</p><p>Weight - "+weight +"<br />Height - "+height+"<br />Width - "+width+"<br />Depth - "+ depth+"</p>",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){   swal("Deleted!", "Your imaginary file has been deleted.", "success"); });
	}

	//AJAX call to complete the order. create shipment and print label
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "royal-mail/complete-shipment/"+orderId,
		dataType: 'json',
		data: {newWeight: newWeight, newHeight: newHeight, newWidth: newWidth, newDepth: newDepth},
		success: function(data){
	  		if(data["existing"] == true) {
	  			swal(
				  'Oops...',
				  'This order already has an existing shipment, please check the order details page',
				  'error'
				);
	  		} else {
	  			//SHOW ANY ERROR MESSAGES FIRST, THEN OPEN LABEL
				//Set up the error messages
			 	swal.setDefaults({
					confirmButtonText: 'Next &rarr;',
					showCancelButton: true,
					animation: false
				});

				var steps = [];
			
			
				//Check if it was allocated
				if(data["create_shipment_API_call_response"]['allocated'] == true){

					//Check if there were any errors
					if('errors' in data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']) {

						//Check if there are single or multiple errors
						if(data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['errors']['error'].length > 1) {
							//Iterate over errors and add them to the array
							data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['errors']['error'].forEach(function(entry) {
							    
								steps.push({
									title: "Error - " + entry['errorCode'],   
									text: entry['errorDescription'],   
									type: "error",   
									confirmButtonText: "OK" ,
									showCancelButton: false
								});
							});

						} else {
							//Only one error so just add one.
							steps.push({
								title: "Error - " + data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['errors']['error']['errorCode'],   
								text: data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['errors']['error']['errorDescription'],   
								type: "error",   
								confirmButtonText: "OK" ,
								showCancelButton: false
							});
						}
					}

					//Check if there were any errors
					if('warnings' in data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']) {

						//Chck if there are single or multiple warnings
						if(data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['warnings']['warning'].length > 1) {
							//Iterate over warnings and add them to the array
							data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['warnings']['warning'].forEach(function(entry) {
							
								steps.push({
									title: "Warning - " + entry['warningCode'],   
									text: entry['warningDescription'],   
									type: "warning",   
									confirmButtonText: "OK" ,
									showCancelButton: false
								});
							});

						} else {
							steps.push({
								title: "Warning - " + data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['warnings']['warning']['warningCode'],   
								text: data["create_shipment_API_call_response"]['array'][0]['createShipmentResponse']['integrationFooter']['warnings']['warning']['warningDescription'],   
								type: "warning",   
								confirmButtonText: "OK" ,
								showCancelButton: false
							});

						}
					}

				} else {
					//Check there was a response 
					if(data['create_shipment_API_call_response']['array'] != null) {
						//It was not allocated due to server fault, find the fault
						//Iterate over warnings and add them to the array
						steps.push({
							title: "Fault - " + data["create_shipment_API_call_response"]['array']['soapenvBody']['soapenvFault']['detail']['v1exceptionDetails']['exceptionCode'],   
							text: data["create_shipment_API_call_response"]['array']['soapenvBody']['soapenvFault']['detail']['v1exceptionDetails']['exceptionText'],   
							type: "warning",   
							confirmButtonText: "OK" ,
							showCancelButton: false
						});
					} else {
						steps.push({
							title: "Fault - " ,   
							text: "An unknown fault has occured." + data["create_shipment_API_call_response"]['err'],   
							type: "warning",   
							confirmButtonText: "OK" ,
							showCancelButton: false
						});
					}
				}

				//Check if the label was created
				if(data["label"] == false){

					//Check the label response for errors
					if('errors' in data["create_label_API_call_response"]['array'][0]['printLabelResponse']['integrationFooter']) {

						steps.push({
							title: "Error - " + data["create_label_API_call_response"]['array'][0]['printLabelResponse']['integrationFooter']['errors']['error']['errorCode'],   
							text: data["create_label_API_call_response"]['array'][0]['printLabelResponse']['integrationFooter']['errors']['error']['errorDescription'],   
							type: "error",   
							confirmButtonText: "OK" ,
							showCancelButton: false
						});
					}
					
				}

				swal.queue(steps).then(function() {
					swal.resetDefaults()
				  	swal({
					    title: 'Shipment has issues!',
					    confirmButtonText: 'OK',
					    showCancelButton: false,
					    html: 'Please review the shipment',
				  	})
				}, function() {
					swal.resetDefaults()
				})

				//If the shipment has been allocated, open the label and redirect
			 	if (data["allocated"] == "Allocated" && data["label"] != false) {
					
					//Open the window with the royal mail label
					myWindow=window.open('','','width=200,height=100');
				    myWindow.document.write("<img style=\"width:3.7in; height:6in;\" src=\"" +globalBaseUrl + "royal_mail_labels/" + orderId + ".jpg\"></img><script type=\"text/javascript\">window.setTimeout(window.print(), 2000);</script>");
				   
				   	//If success, load new pack screen
					window.location.href = globalBaseUrl + "pickpack/pack";
				} 
		 	}

		
		}, 
		fail: function() {
			alert('failed');
		}
  	});
}

function reprintShipmentLabel(shipmentId) {
 alert(shipmentId);
}


function processStoreDeliveryFormSubmit() {
  $("form[name='store_delivery_form']").submit(function(e){
	 
	 e.preventDefault(); //prevent submit
	 processStoreDeliveryForm()
  });
}

function processStoreDeliveryForm() {
	//Get the barcode and check if it is a shelf or a product.
	var barcode = $('#store_delivery_barcode_input').val();
	if(isNumeric(barcode)) { 
    
	    //Check if shelf has been selected.

	    //Add the product to the storage.
	    processStoreDeliveryAdd(barcode);

 	} else {

	    //Cal the shelf AJAX code.
	    //Create ajax call with array/JSON of ids, this will iterate thorough and added.
	    $.ajax({
			type: "POST",
			url: globalBaseUrl + "goods-in/store-delivery/select-storage-shelf",
			dataType: 'json',
			data: { barcode : barcode  },
			success: function(data) { 
                //Reset the barcode and search fields
            	$("#store_delivery_barcode_input").val("");

            	//Update view with shelf detail
                $("#store_delivery_shelf_code_selected").html('Shelf <b>' + data['shelf_code'] + '</b> selected');
            	
        	}, 
	      	fail: function() {
	         	alert('Ajax failed');
	      	}

		//End of AJAX function
 		});

	//End of if numeric
	}

}

function processStoreDeliveryAdd(barcode) {

	//Pass product barcode to server to update database
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "goods-in/store-delivery/store-delivery-process",
		dataType: 'json',
		data: { barcode : barcode },
		success: function(data){

			//Check if shelf was set
            if(data['stock_location_shelf'] == false) {

                //Instuct the user to scan a shelf first
                alert("Please scan a shelf first");
            } else {

                //Need to get success or failure from the server
                if (data['stored_success'] == true) {

                    //Update the colour of the table row to green
                    //Get the product id
                    var rowId = data['supplier_delivery_product_id'];
                    $('#product_row_' + rowId).find("*").css("background-color", ":#71a01e");

                    //Check if there are remaining products to be stored
                    $("#supplier_delivery_lines_to_store").html("<b>" + data['remaining_qty_to_store'] + "</b>");

                    //If there are no more products to be stored, disable the barcode button
					if(data['remaining_qty_to_store'] == 0 ) {
						$("#milesapart_staffbundle_store_delivery_submit").addClass("disabled");
					}

                }


                if (data['already_stored'] == true) {
                    alert("This product has already been stored on shelf " + data['already_stored_shelf_code']);
                }
            }
		}, 
      	fail: function() {
         	alert('Ajax failed');
      	}

	//End of AJAX function
	});

	//Update table to show product as stored.
}

function printWindow() {
	window.print();
	/*window.onfocus=function() {
		window.close();
	}*/
}


function initialiseVanityURLCheck() {

    $('.url_append').keyup(function() {
        
        var divId = $(this).attr('id');
		divId = divId.match(/\d+/);

		var responseDiv = "#promotion" + divId;

        vanityURLCheck($(this).val(), responseDiv);
    });
    
}


function vanityURLCheck(vanityURL, responseDiv) {	

	//Set the div to show the response
	if(responseDiv == null) {
		responseDiv = "#vanity_url_check_response";
	}
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "campaigns/vanity-url-check",
		dataType: 'json',
		data: { vanityURL : vanityURL },
		success: function(data){

			if(data['available'] == true) {
				$(responseDiv).html("<h3><span class=\"label label-success\">\"" + vanityURL+"\" is available</span></h3>");
				$(responseDiv).css("color", ":#71a01e");
			} else {
				$(responseDiv).html("<h3><span class=\"label label-warning\">\"" + vanityURL+"\"  is unavailable</span></h3>");
				$(responseDiv).css("color", ":rgba(219, 41, 37, .97)");
			}
			
		}, 
      	fail: function() {
         	alert('Ajax failed');
      	}

	//End of AJAX function
	});

}

function submitPurchaseOrderConfirmationForManualInput() {
	//Get the selected purchase orders that the confirmation relates to
	alert("hi");
	var myCheckboxes = new Array();
	$("input:checked").each(function() {
	   	myCheckboxes.push($(this).val());
	});

	var purchaseOrders = "";
	if(myCheckboxes.length == 1) {
		purchaseOrders = myCheckboxes[0];
	} else if(myCheckboxes.length > 1) {
		for(i=0; i < myCheckboxes.length; i++) {
			if(i == 0) {
				purchaseOrders = myCheckboxes[0];
			} else {
				purchaseOrders = purchaseOrders + "-" + myCheckboxes[i];
			}
		}
	}
	
	var urlRelocate = globalBaseUrl + "purchase-orders/process-purchase-order-confirmation/manual-input/" + purchaseOrders
	window.location.href = urlRelocate;
}

function purchaseOrderConfirmationProductCodeEntry() {

	//When the product code is changed
	$('.purchase_order_confirmation_product_supplier_code').change(function() {
	 	
	 	var productCode = $(this).val();
	 	var tableRowId = parseInt($(this).attr('id').replace(/[^0-9\.]/g, ''), 10);
	 	var responseDiv = "#purchase__order__product" + tableRowId;

	 	//Use AJAx call to check if product matches the code/supplier
	 	$.ajax({
			type: "POST",
			url: globalBaseUrl + "purchase-orders/supplier-code-check",
			dataType: 'json',
			data: { productCode : productCode },
			success: function(data){

				if(data['success'] == true) {
					//Insert the values into the table
					
					$(responseDiv).find("td").eq(1).html(data['product_name']);
					$(responseDiv).find("td").eq(2).html(data['product_barcode']);
					$(responseDiv).find("td").eq(3).html(data['product_outer_quantity']);
					$(responseDiv).find("td").eq(4).html(data['product_inner_quantity']);
					$(responseDiv).find("td").eq(6).find("input").val(data['product_cost']);
					//alert($(responseDiv + " td:eq(5)")).html();
				} else {
					//Product has not been found in the database
					alert('not found');
				}
				
			}, 
	      	fail: function() {
	         	alert('Ajax failed');
	      	}
	    });
	});
}

function addProductGroupToPrintRequest(productGroupId, printRequestTypeId) {

	 	//Use AJAx call to check if product matches the code/supplier
	 	$.ajax({
			type: "POST",
			url: globalBaseUrl + "products/add-product-group-to-print-request",
			dataType: 'json',
			data: { productGroupId : productGroupId, printRequestTypeId: printRequestTypeId },
			success: function(data){

				if(data['success'] == true) {
					
					alert("Added.");
				} else {
					//Product has not been found in the database
					alert('not found');
				}
				
			}, 
	      	fail: function() {
	         	alert('Ajax failed');
	      	}
	    });

}


function showAnswerQuestionModal(question_id) {

	
	//Get the form
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "products/answer-question",
		dataType: 'json',
		data: { question_id: question_id },
		success: function(data){
		 	//Check if a match was made
		 	//if(data['success'] == true) {
		 		//Info needs to be displayed to user with option to import/save date in the MS database
		 		

				//Show modal with Amazon product information and allowing this info to be saved (creating a new product in the MA DB)
				$("#amazon_product_modal").html(data['html']);
				$('#amazon_product_modal').modal();
				
			//} else {
				//alert("no question found to answer")
			//}
		}, 
      	fail: function() {
         	alert('Ajax failed');
      	}
    });

}

function submitAnswerQuestionModal() {
		

		//Get the data
		var product_answer_text = $('#milesapart_adminbundle_productanswer_product_answer_text').val();
		var product_question_id = $('#milesapart_adminbundle_productanswer_product_question').val();


		//Send to the server
		$.ajax({
			type: "POST",
			url: globalBaseUrl + "products/answer-question/submit",
			dataType: 'json',
			data: { product_answer_text: product_answer_text,  product_question_id: product_question_id},
			success: function(data){
			 	//Check if a match was made
			 	if(data['success'] == true) {
			 		//Info needs to be displayed to user with option to import/save date in the MS database
			 		
					//Show modal with Amazon product information and allowing this info to be saved (creating a new product in the MA DB)
					$('#amazon_product_modal').modal('hide');

					//Make question bg green
					$('#question_row_' + product_question_id ).children().animate({backgroundColor:'#71a01e'}, 300);
					//Reduce number of unanswered questions
					var remainingQuestions = parseInt($('#unanswered_questions_total').html()) - 1;
					$('#unanswered_questions_total').html(remainingQuestions);
				}
			}, 
	      	fail: function() {
	         	alert('Ajax failed');
	      	}
	    });
	

}

function showApproveReviewModal(review_id) {

	//Get the form
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "products/approve-review",
		dataType: 'json',
		data: { review_id: review_id },
		success: function(data){
		 	//Check if a match was made
		 	//if(data['success'] == true) {
		 		//Info needs to be displayed to user with option to import/save date in the MS database
		 		

				//Show modal with Amazon product information and allowing this info to be saved (creating a new product in the MA DB)
				$("#amazon_product_modal").html(data['html']);
				$('#amazon_product_modal').modal();
				
			//} else {
				//alert("no question found to answer")
			//}
		}, 
      	fail: function() {
         	alert('Ajax failed');
      	}
    });

}

function submitApproveReviewModal(review_id) {
		
		//Send to the server
		$.ajax({
			type: "POST",
			url: globalBaseUrl + "products/approve-review/submit",
			dataType: 'json',
			data: { review_id: review_id},
			success: function(data){
			 	//Check if a match was made
			 	if(data['success'] == true) {
			 		//Info needs to be displayed to user with option to import/save date in the MS database
			 		
					//Show modal with Amazon product information and allowing this info to be saved (creating a new product in the MA DB)
					$('#amazon_product_modal').modal('hide');

					//Make question bg green
					$('#review_row_' + review_id ).children().animate({backgroundColor:'#71a01e'}, 300);
					//Reduce number of unanswered questions
					var remainingReviews = parseInt($('#unapproved_reviews_total').html()) - 1;
					$('#unapproved_reviews_total').html(remainingReviews);
				}
			}, 
	      	fail: function() {
	         	alert('Ajax failed');
	      	}
	    });
	

}


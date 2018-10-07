/***************************************************
*
* Product Helper functions - for Adding product to list and associated functions
*
***************************************************/
/***************************************************
* Adding products to list 
***************************************************/
function addProductToList(submitUrl, functionName, variablePrepend, barcode) {
	/* Data examples
	  	submitUrl = "./../submit"
	  	functionName = "StocktakeProduct"
	 	variablePrepend = stocktake_product

  	*/
  	//Set up the variables required
	quantityVariable = variablePrepend + "_qty";
	idVariable = variablePrepend + "_id";

	//Set barcode to false if it has not been set by the Amazon Modal
	barcode = typeof barcode !== 'undefined' ? a : false;
	
	//Get barcode
	if(barcode == false) {
		if ($(".add_product_to_list_product_barcode").val() != "") {
			var barcode = $(".add_product_to_list_product_barcode").val();

			//Check if the barcode starts with letters (and is therefore a product group)
			

		} else {
			var barcode = null;
		}
	}
	 
  	//Get product supplier code
	if ($(".add_product_to_list_product_supplier_code").val() != "") { 
		var product_supplier_code = $(".add_product_to_list_product_supplier_code").val();
	} else {
		var product_supplier_code = null;
	}

	//Get product name and use as search query
	var search_query = $(".add_product_to_list_product_name").val();

	//If there is no searh query, set it to 0
	if (typeof search_query === 'undefined') {
	 	// variable is undefined
		search_query = "0";
	}

	//Create and make the AJAX call to return a product or array of products that match the barcode/supplier code/search
	$.ajax({
		type: "POST",
		url: submitUrl,
		dataType: 'json',
		data: { barcode : barcode, product_supplier_code: product_supplier_code, search_query : search_query   },
		success: function(data){
			//Need to get if a product was found from server
			if(data['found'] == true) {
				
				//Check if only one product with matching barcode was found
				if (data['single'] == true) {

				  	//CALL THE MODAL FOR UPDATING PRICE AND SHORT NAME/SUBTITLE 
				  	addProductToListModal(data, variablePrepend);

				  


				  	//First the pop up
				  	var popupContent = "<div class=\"row\"><div class=\"col-md-4 col-xs-10\"><h6>Product Name</h6><h5>" + data['product_name'] + "</h5></div><div class=\"hide_on_non_xs_jquery col-xs-1\"><a href=\"#\" class=\"popup_close\" onclick=\"hidePopup();\"><span class=\"glyphicon glyphicon-remove\"></span> </a></div><div class=\"col-md-2 col-xs-6\"><h6>Qty</h6><input class=\"col-md-6 col-xs-6 form-control\"  id=\"product_new_qty\" type=\"text\" /></div><div class=\"col-md-2 col-xs-6\"><h6>Supplier Code</h6><h5>" + data['product_supplier_code'] + "</h5></div><div class=\"col-md-2 col-xs-6\"><h6>Barcode</h6><h5 class=\"small\">" + data['product_barcode'] + "</h5></div><span style=\"display:none\" id=\"latest_stocktake_product_id\">"+ data['product_id'] + "</span><div class=\"col-md-1 col-md-offset-1 col-xs-6\"><a href=\"#\" class=\"btn btn-default col-xs-12\" style=\"margin-top:10px\" onclick=\"updateProductListQty('" + data['product_id'] + "', '" + submitUrl +"')\">Update</a></div></div>";

				  	//if ($("#popup_form_response").css('background-color') == 'rgba(219, 41, 37, .9)') {
					 	$('#popup_form_response').animate({backgroundColor: 'rgba(113, 160, 30, .97)'}, 500);
				  	//}

				  	$("#popup_form_response_content").html(popupContent);
				  	$("#popup_form_response").slideDown('slow', function() {
					 	//Set focus on the Qty form field.
					 	if(functionName != "PrintRequest") {
					 		$("#product_new_qty").focus();
				  		}
				  	});
				  
				  	//Then the table row

				  	//IS PURCHASE ORDER PRODUCT
				 	if(variablePrepend == "purchase_order_product_") {
				 		
				 		//Difine the form row
			 		 	var formRow = "<tr id=\"product_row_" + data['product_id'] + "\"  class=\"new-table-add\"><td>" + data['product_supplier_code'] + "</td><td>" + data['product_name'] + "</td><td class=\"text-center\">" +
						data['product_outers_quantity'] + "</td><td class=\"text-center\">" + data['product_inners_quantity'] + "</td><td class=\"text-center\">" +
						data['product_qty'] + "</td><td class=\"text-center\">£" + data['product_cost'] + "</td><td class=\"text-center\">£" + parseFloat(Math.round((data['product_qty'] * data['product_cost']) * 100) / 100).toFixed(2) + "</td><td class=\"hide_on_xs_jquery text-center\">" + data['product_barcode'] + "</td></tr>";
				
					
					//NOT PURCHASE ORDER PRODUCT
					} else {
						
						//Define the form row
				  		var formRow = "<tr id=\"product_row_" + data['product_id'] + "\"  class=\"new-table-add\"><td>" + data['product_name'] + "</td><td>" +
						data['product_price'] + "</td><td>" + data['product_qty'] + "</td><td>" + data['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery\">" + data['product_barcode'] + "</td><td>" + data['supplier_name'] + "</td></tr>";
				 	}

				  	//Check if a table id has been recieved (thus purchase order product is being added)
				  	if(data['table_id']) {

					 	var prependDiv = "existing_purchase_order_" + data['table_id'];
					 	//First check if a table exists on the page.
					 	if($("#" + prependDiv + " > tbody").length) {
						
							//If id does exist, add to specific table
							$(formRow).prependTo("#" + prependDiv + " > tbody").find('*').effect("highlight", {color:"#71a01e"}, 7000);
					 	} else {
							
							//If table does not exist, create the table.
							var table = "<div class=\"row\"><div class=\"col-md-10 col-xs-9\"><h4>" + data['supplier_name'] + "</h4></div><div class=\"col-md-2 col-xs-3\"><a class=\"btn btn-default btn-sm col-md-12 col-xs-12 success-colour\" href=\"/Miles-Apart/web/app_dev.php/staff/purchase-orders/send-purchase-order/" + data['table_id'] + "\" role=\"button\"><span class=\"glyphicon glyphicon-export\"></span><span class=\"hide_on_xs\">Send order</span></a></div></div><table class=\"table table-striped small existing_products\" id=\"" + prependDiv + "\"><thead><tr><th width=\"10%\" class=\"text-left\">Code</th><th width=\"25%\" class=\"text-left\">Product name</th><th width=\"10%\" class=\"text-center\">Outers Qty</th><th width=\"10%\" class=\"text-center\">Inners Qty</th><th width=\"10%\" class=\"text-center\">Unit Qty</th><th width=\"10%\" class=\"text-center\">Unit Cost</th><th width=\"10%\" class=\"text-center\">Total Cost</th><th width=\"15%\" class=\"hide_on_xs text-center\">Barcode</th></thead><tbody></tbody></table>";
							$('#outstanding_purchase_orders').prepend(table).each(function() {
								//Add the form for to the created table
						  		$(formRow).prependTo("#" + prependDiv + " > tbody").find('*').effect("highlight", {color:"#71a01e"}, 3000);
							});
						}
					
				  	} else {
					 	//Otherwise add to the single table.
					 	$(formRow).prependTo("table > tbody").find('*').effect("highlight", {color:"#71a01e"}, 2000);
				  	}
					 
					
				  

				  	$(".hide_on_xs_jquery").addClass("hide_on_xs");
				  	$(".hide_on_non_xs_jquery").addClass("hide_on_non_xs");

				  	//Reset the barcode and search fields
				  	$(".add_product_to_list_product_barcode").val("");
				  	$(".add_product_to_list_product_supplier_code").val("");
				  	$(".add_product_to_list_product_name").val("");

				//If single is not true, but found is true.
				} else {
				  //If not the only product that matches the barcode
				  //Create the table
				  
				  
				var table = '<h3>' + data['products'].length + ' Products Found</h3><table class="table table-striped small" id="matched_barcode_products"><thead><tr><th>Product Name</th><th>Price</th><th>Supplier Code</th><th>Barcode</th><th>Supplier</th><th>Qty</th><th>Select</th></tr></thead><tbody>';

				  //Add each product to the table, allowing aech to be selected 
				  var tr ='';
				  for (i=0; i < data['products'].length ; i++) {
						//check if duplicate of existing product transfer request
						tr += "<tr id=\"" + data[idVariable] + "\" >" ;
						// create a new textInputBox  
						var selectInput = '<input type="checkbox" id="" name="" title="" />';  
						// create a new Label Text
						tr += '<td>' + data['products'][i]['product_name']  + '</td>';
						tr += '<td>' + data['products'][i]['product_price']  + '</td>'; 
						tr += '<td>' + data['products'][i]['product_supplier_code']  + '</td>';
						tr += '<td>' + data['products'][i]['product_barcode']  + '</td>';
						tr += '<td>' + data['products'][i]['supplier_name']  + '</td>'; 
						tr += "<td><input class=\"form-control col-md-6\" type=\"text\" onfocus=\"if (this.value == '4') {this.value = ''; this.style.color='#333333'}\" onblur=\"if (this.value == '') {this.value = '4'; this.style.color='#888888';}\" value=\"4\" /></td>";
						tr += '<td><input class="form-control" type="checkbox" id="' + data['products'][i]['product_id']  + '"/></td>'; 
						tr +='</tr>';
					}
					
					var submit_button = "<button class=\"btn btn-primary col-md-1 col-md-offset-11\" id=\"multiple_product_submit\" onclick=\"addSelectedMultipleProductsToList('" + submitUrl +"', '" + variablePrepend + "')\">Submit</button>";
					//Complete the table.
					table = table + tr + "</tbody></table>" + submit_button;

					//Display the table
					$('#found_products_display').html(table).slideDown('fast');

				//End of if single 
				}

			 //If product has been found and already exists in storage box.
			 } else if (data['duplicate'] == true) {
				//Show the warning popup
				var popupContent = "<div class=\"row\"><div class=\"col-xs-10\"><h6>Note</h6></div><div class=\"col-xs-2\"><a href=\"#\" class=\"popup_close\" onclick=\"hidePopup();\"><span class=\"glyphicon glyphicon-remove\"></span> </a></div><div class=\"col-md-9\"><h5>" + data['product_name'] + " already added to list</h5></div><div class=\"col-md-1 col-xs-12\"><h6>Qty</h6><h5><input class=\"col-md-12 col-xs-12 form-control\"  style=\"color:#888888\" id=\"product_new_qty\" type=\"text\" value=\"" + data['product_qty'] + "\" onfocus=\"if (this.value == '" + data['product_qty'] + "') {this.value = ''; this.style.color='#333333'}\" onblur=\"if (this.value == '') {this.value = '" + data['product_qty'] + "'; this.style.color='#888888';}\" /></h5></div><div class=\"col-md-2\"><a href=\"#\" class=\"btn btn-default col-md-12 col-xs-12\" style=\"margin-top:20px\" onclick=\"updateProductListQty('" + data['product_id'] + "', '" + submitUrl +"')\">Update Qty</a></div></div>";
				$("#popup_form_response_content").html(popupContent);
				
				//if ($("#popup_form_response").css('background-color') == 'rgba(113, 160, 30, .9)') {
				  $('#popup_form_response').animate({backgroundColor: 'rgba(245, 114, 27, .97)'}, 500);
				//}
				$("#popup_form_response").slideDown('slow');
			 
				//Reset the barcode and search fields
				$(".add_product_to_list_product_barcode").val("");
				$(".add_product_to_list_product_supplier_code").val("");
				$(".add_product_to_list_product_name").val("");
			 
			//If the product has not been found
			} else {

			 	/*************************************
			 	*
			 	* This is the point to check for Amazon product and show these options if they exist
			 	*
			 	**************************************/
			 	var match = false;

			 	//Need to create AJAX call to query the Amazon API with the barcode scanned.
			 	$.ajax({
					type: "POST",
					url: globalBaseUrl + "seller/amazon/product-modal",
					dataType: 'json',
					data: { barcode: barcode, submitUrl: submitUrl, functionName: functionName, variablePrepend: variablePrepend },
					success: function(data){

                        //alert(data);
					 	//Check if a match was made
					 	if(data['match'] == true) {
					 		//Info needs to be displayed to user with option to import/save date in the MA database
							//Show modal with Amazon product information and allowing this info to be saved ( creating a new product in the MA DB)
							$("#amazon_product_modal").html(data['html']);
							$('#amazon_product_modal').modal();
							match = true;
					 	} else {
					 		//Show the red form if there was no match with Amazon
							//Set up the supplier options.
							var supplierList = "";

							$.ajax({
								type: "POST",
								url: globalBaseUrl + "suppliers/get-suppliers-list",
								dataType: 'html',
								data: {  },
								success: function(secondData){
									supplierList = secondData;

									if (product_supplier_code != null) {
										var supplier_code = product_supplier_code;
									} else {
										var supplier_code = "Supplier code...";
									}


									//Show the error popup
									var popupContent = "<span style=\"display:none\" id=\"new_product_barcode\">" + barcode + "</span><div class=\"row\"><div class=\"col-md-3\"><h6>Note</h6><h5>Product not found</h5></div><div class=\"col-xs-12 col-md-6\"><div class=\"row\"><div class=\"col-md-10 col-xs-9\"><h6>Product Name</h6><input class=\"col-md-12 col-xs-12 form-control\"  style=\"color:#888888\" id=\"new_product_name\" style=\"margin-top:5px\" type=\"text\" value=\"Please type product name...\" onfocus=\"if (this.value == 'Please type product name...') {this.value = ''; this.style.color='#333333'}\" onblur=\"if (this.value == '') {this.value = 'Please type product name...'; this.style.color='#888888';}\"  /></div><div class=\"col-md-2 col-xs-3\"><h6>Qty</h6><h5><input class=\"col-md-12 col-xs-12 form-control\"  style=\"color:#888888\" id=\"new_product_qty\" type=\"text\" value=\"Qty...\" onfocus=\"if (this.value == 'Qty...') {this.value = ''; this.style.color='#333333'}\" onblur=\"if (this.value == '') {this.value = 'Qty...'; this.style.color='#888888';}\" /></h5></div></div><div class=\"row\"><div class=\"col-md-3 col-xs-6\"><h6>Supplier Code</h6><input class=\"col-md-12 col-xs-12 form-control\"  style=\"color:#888888\" id=\"new_product_supplier_code\" style=\"margin-top:5px\" type=\"text\" value=\"" + supplier_code + "\" onfocus=\"if (this.value == 'Supplier code...') {this.value = ''; this.style.color='#333333'}\" onblur=\"if (this.value == '') {this.value = 'Supplier code...'; this.style.color='#888888';}\"  /></div><div class=\"col-md-3 col-xs-6\"><h6>Price</h6><h5><input class=\"col-md-12 col-xs-12 form-control\"  style=\"color:#888888\" id=\"new_product_price\" type=\"text\" value=\"Price...\" onfocus=\"if (this.value == 'Price...') {this.value = ''; this.style.color='#333333'}\" onblur=\"if (this.value == '') {this.value = 'Price...'; this.style.color='#888888';}\" /></h5></div><div class=\"col-md-6 col-xs-12 xs_clear\"><h6>Supplier</h6><h5><select class=\"col-md-12 col-xs-12 form-control\"  style=\"color:#333333;\" id=\"new_product_supplier\"><option>Select one...</option>" + supplierList + "</select></h5></div></div></div><div class=\"col-md-2 col-md-offset-1\"><a href=\"#\" class=\"btn btn-default col-md-12 col-xs-12\" style=\"margin-top:10px\" onclick=\"newProductListProduct('" + submitUrl + "', '" + functionName + "', '" + variablePrepend + "')\">Add Product</a></div></div>";

									$("#popup_form_response_content").html(popupContent);
									//if ($("#popup_form_response").css('background-color') == 'rgba(113, 160, 30, .9)') {
										$('#popup_form_response').animate({backgroundColor: 'rgba(219, 41, 37, .97)'}, 500);
									//}
									$("#popup_form_response").slideDown('slow');



								},

								fail: function() {
									alert('Ajax supplier list failed');
								}

							});
						}

	
					}, 

				  	fail: function() {
					 	alert('Ajax supplier list failed');
				  	}

				});
			 	/***********************************
			 	* End of Amazon product check
			 	***********************************/


			 	

			//End of found if
			} 
			
		//AJAX function, end of success 
		}, 
		 fail: function() {
			alert('Ajax failed');
		}

	//End of AJAX function
	});

	//Reset the barcode and search fields
	$(".add_product_to_list_product_barcode").val("");
	$(".add_product_to_list_product_supplier_code").val("");
	$(".add_product_to_list_product_name").val("");
}


/*******************************************
* Add product to list modal
*******************************************/
function addProductToListModal(data, variablePrepend) { 
	//Set up initial variables
	var modalRequired = false;
	var html = '';
	var product_price_for_edit = data['product_price'].substring(1);
	//Genertate the content of the modal depending on the data missing and the list the product is being added to
	//IF PRICE DOES NOT EXIST
	if (data['product_price'] == null | data['product_price'] == 'N/A') {
		modalRequired = true;
		html += '<div class="form-group">'+
					'<label for="swal-short_name"><b>Price</b></label>'+
				'<div class="input-group">'+
					'<div class="input-group-addon">£</div>'+
					'<input type="text" id="swal-new_price" class="form-control col-xs-12" autofocus placeholder="Price">' +
				'</div>'+
			'</div>';

	//PRICE DOES EXIST 
	} else {
		//IF THIS IS A PRICE REQUEST CHECK CURRENT PRICE IS VALID
		if(variablePrepend == "print_request") {
			modalRequired = true;
			html += '<div class="form-group">'+
						'<label for="swal-short_name"><b>Price</b></label>'+
					'<div class="input-group">'+
						'<div class="input-group-addon">£</div>'+
						'<input type="text" id="swal-new_price" class="form-control col-xs-12" autofocus placeholder="Price" value="'+product_price_for_edit+'">' +
					'</div>'+
				'</div>';
		}
	}

	//If print request check short name and subtitle
	if(variablePrepend == "print_request") {
		//If short name is missing
		if(data['short_name'] == null) {
			//Add the product full name to the html code
			html += '<p><b>Full Name:</b> '+ data['product_name'];
			modalRequired = true;
			html += '<div class="form-group">'+
						'<label for="swal-short_name"><b>Short name</b></label>'+
						'<input type="text" id="swal-short_name" style="text-transform:capitalize;" class="form-control col-xs-12" autofocus placeholder="Short name">' +
					'</div>';
						
		}

		//If subtitle is missing
		if(data['subtitle'] == null) {
			modalRequired = true;
			html += '<div class="form-group">'+
						'<label for="swal-subtitle"><b>Subtitle</b></label>'+
						'<input type="text" id="swal-subtitle" style="text-transform:capitalize;" class="form-control col-xs-12" placeholder="Subtitle">' +
					'</div>';
		}
	}

	//IF Supplier DOES NOT EXIST
	if (data['supplier_name'] == null | data['supplier_name'] == '') {
		alert("no supplier");
		var supplierSelect = true;
		modalRequired = true;
		var supplierList = "";
		//Get list of suppliers
		$.ajax({
			type: "POST",
			url: globalBaseUrl + "suppliers/get-suppliers-list",
			dataType: 'html',
			data: {  },
			success: function(secondData){
		
			  	supplierList = secondData;
			  	
				html += '<div class="form-group">'+
							'<label for="swal-supplier"><b>Supplier</b></label>'+
							'<select type="text" id="swal-supplier" class="form-control col-xs-12" placeholder="Supplier">' +
								supplierList +
							'</select>' +
						'</div>';
			}
		});

		
	}
	
	//DISPLAY THE MODAL (break out into new function)
	if(modalRequired) {
		html += '<input type="hidden" id="swal-old_price" value="'+product_price_for_edit+'">';
		html += '<input type="hidden" id="swal-product_id" value="'+data['prod_id']+'">';
		html += '<input type="hidden" id="swal-prod_id" value="'+data['product_id']+'">';
		//Create the submit button 
		var submitButton = '<button class="btn btn-success" onclick="submitProductUpdateModal(\''+variablePrepend+'\')">Save changes</button>';

		//Add a delay if supplier list is required
		if(supplierSelect) {
			setTimeout(function() { 
				displayAddProductToListModal(html, submitButton)
			}, 7000);
		} else {
			displayAddProductToListModal(html, submitButton);
		}
	}
	
}


/*******************************************
* DISPLAY Add product to list modal
*******************************************/
function displayAddProductToListModal(html, submitButton) {
	//Put the old price and product id in the form
	
	$("#popup_price_input_form").html(html);
	$("#popup_price_input_submit").html(submitButton);
	$('#popup_price_input').modal();
}

/*******************************************
* SUBMIT Add product to list modal
*******************************************/
function submitProductUpdateModal(variablePrepend) {

	var modalClose = false;

	//get the variables
	var oldPrice = $('#swal-old_price').val();
	var newPrice = $('#swal-new_price').val();
	try {
		var shortName = $('#swal-short_name').val().toTitleCase();
	}
	catch(e)
	{
		console.log(e);
	}
	try {
		var subtitle = $('#swal-subtitle').val().toTitleCase();
	}
	catch(e)
	{
		console.log(e);
	}
	var productId = $('#swal-product_id').val();
	var prodId = $('#swal-prod_id').val();
	var supplier = $('#swal-supplier').val();
	console.log(supplier);
	//CHeck if the price has changed
	if(newPrice != oldPrice) {

		//Set the new price
		setMissingProductPrice(productId, newPrice).done(function(result) {

			//Update the price in the table row
			var row = "#product_row_"+ prodId;
			//Purchase order product doesn't have price in the table row so we exclude
			if(variablePrepend != "purchase_order_product") {
				$(row).find('td:eq(1)').html("£"+newPrice);
			}

			$('#popup_price_input').modal('hide');
		}).fail(function() {
			alert("the update failed");
		});
	} else {
		modalClose = true;
	}
	

	//SHORT NAME/SUBTITLE
	if(shortName || subtitle) {
		setMissingShortNameAndSubtitle(productId, shortName, subtitle).done(function(result) {
			//Price updated successfully
			//Update the price in the table row
			$('#popup_price_input').modal('hide');
			
		}).fail(function() {
			alert("the update failed");
		});
	} else {
		modalClose = true;
	}

	//Supplier
	if(supplier) {
		setMissingSupplier(productId, supplier).done(function(result) {
			//Price updated successfully
			//Update the price in the table row
			$('#popup_price_input').modal('hide');
			
		}).fail(function() {
			alert("the update failed");
		});
	} else {
		modalClose = true;
	}

	if(modalClose) {
		$('#popup_price_input').modal('hide');
	}
}

/*******************************************
* Update product price (ID of product, New price decimal)
*******************************************/
function setMissingProductPrice(productId, newPrice) {
	alert(globalBaseUrl);
  	//Make AJAX call to update database
  	return $.ajax({
		type: "POST",
		url: globalBaseUrl + "products/update-price",
		dataType: 'json',
		data: { new_price: newPrice, product_id: productId  },

		success: function(data){
			
			if (data["success"] == true) {
			  	return true;
			} else {
				
				alert("Price not updated");
			}
		}, 
		fail: function() {
			alert('failed');
		}
	});
}

/*******************************************
* Update product short name and subtitle
*******************************************/
function setMissingShortNameAndSubtitle(productId, short_name, subtitle) {
alert(globalBaseUrl);
  	//Make AJAX call to update database
  	return $.ajax({
		type: "POST",
		url: globalBaseUrl + "products/update-short-name-and-subtitle",
		dataType: 'json',
		data: { short_name: short_name, subtitle: subtitle, product_id: productId  },

		success: function(data){
		  	
			if (data["success"] == true) {
			  	return true;
			} else {
				
				alert("Qty not updated");

			}
		}, 
		fail: function() {
			alert('failed');
		}
	});
}

/*******************************************
* Update product supplier
*******************************************/
function setMissingSupplier(productId, supplier) {
alert(globalBaseUrl);
  	//Make AJAX call to update database
  	return $.ajax({
		type: "POST",
		url: globalBaseUrl + "products/update-supplier",
		dataType: 'json',
		data: { supplier: supplier, product_id: productId  },

		success: function(data){
		  	
			if (data["success"] == true) {
			  	return true;
			} else {
				alert("Qty not updated");

			}
		}, 
		fail: function() {
			alert('failed');
		}
	});
}


$("#new_product_supplier").change(function() {
	alert('cahnged');
})

/*******************************************
* Add new product from the product list pop up (red box)
*******************************************/
function newProductListProduct(submitUrl, functionName, variablePrepend){

	//Set up the variables
  	quantityVariable = variablePrepend + "qty";
  	idVariable = variablePrepend + "id";


  	var newProductName = $("#new_product_name").val().toTitleCase();
  	var newProductQty = $("#new_product_qty").val();
  	var newProductSupplierCode = $("#new_product_supplier_code").val().toUpperCase();
  	var newProductPrice = $("#new_product_price").val();
  	var newProductBarcode = $("#new_product_barcode").html();
  	
  	var newProductSupplierId = $("#new_product_supplier").val();

  	//Handle defaults values remaining in the form
	if (newProductSupplierCode == "SUPPLIER CODE...") {
	  	newProductSupplierCode = null;
  	}
  	if (newProductPrice == "Price...") {
	  	newProductPrice = null;

		//Ensure price is added for a print request
		if(variablePrepend = "print_request") {
		  	alert("The price is required to print price");
		  	return;
		}
  	}

  	//Check that product name and qty have been set
  	if (newProductName != "Please type product name..." && newProductQty != "Qty...") {
	 
  		alert(newProductSupplierId);
		//Make AJAX call to add product to database
		$.ajax({
			type: "POST",
			url: submitUrl+"-new-product",
			dataType: 'json',
			data: { new_product_name: newProductName, new_product_qty : newProductQty, new_product_barcode: newProductBarcode, new_product_supplier_code : newProductSupplierCode, new_product_price: newProductPrice, new_product_supplier_id: newProductSupplierId },
			success: function(data){

				//First the pop up
				if (data["success"] == true) {
					
					//Create the green pop up ontent
					var popupContent = "<div class=\"row\"><div class=\"col-md-4 col-xs-10\"><h6>Product Name</h6><h5>" + data['product_name'] + "</h5></div><div class=\"hide_on_non_xs_jquery col-xs-1\"><a href=\"#\" class=\"popup_close\" onclick=\"hidePopup();\"><span class=\"glyphicon glyphicon-remove\"></span> </a></div><div class=\"col-md-2 col-xs-6\"><h6>Request Qty</h6><input class=\"col-md-6 col-xs-6 form-control\"  id=\"product_new_qty\" type=\"text\" value=\"" + data['product_qty'] + "\" /></div><div class=\"col-md-2 col-xs-6\"><h6>Supplier Code</h6><h5>" + data['product_supplier_code'] + "</h5></div><div class=\"col-md-2 col-xs-6\"><h6>Barcode</h6><h5>" + data['product_barcode'] + "</h5></div><span style=\"display:none\" id=\"latest_product_transfer_request_id\">"+ data['product_id'] + "</span><div class=\"col-md-1 col-md-offset-1 col-xs-6\"><a href=\"#\" class=\"btn btn-default col-xs-12\" style=\"margin-top:10px\" onclick=\"updateProductListQty('" + data['product_id'] + "', '" + submitUrl +"')\">Update</a></div></div>";
					$('#popup_form_response').animate({backgroundColor: 'rgba(113, 160, 30, .97)'}, 500);
					$("#popup_form_response_content").html(popupContent);
					$("#popup_form_response").slideDown('slow');
					

					//Then the table row
				 	if(variablePrepend = "purchase_order_product") {
				 		var formRow = "<tr id=\"product_row_" + data['product_id'] + "\"  class=\"new-table-add\"><td>" + data['product_name'] + "</td><td class=\"text-center\">" +
							data['product_price'] + "</td><td class=\"text-center\">" + data['product_qty'] + "</td><td class=\"text-center\">" + data['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery text-center\">" + data['product_barcode'] + "</td><td class=\"text-center\">" + data['supplier_name'] + "</td></tr>";
					} else {
					  	var formRow = "<tr id=\"product_row_" + data['product_id'] + "\"  class=\"new-table-add\"><td>" + data['product_name'] + "</td><td>" +
							data['product_price'] + "</td><td>" + data['product_qty'] + "</td><td>" + data['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery\">" + data['product_barcode'] + "</td><td>" + data['supplier_name'] + "</td></tr>";
					}
					 
					//Check if purchase order id exists. (This sets up individual tables for the add product to purchase order page).
					if(data['table_id']) {

						var prependDiv = "existing_purchase_order_" + data['table_id'];
						
						//First check if a table exists on the page.
						if($("#" + prependDiv + " > tbody").length) {
						  
							//If id does exist, add to specific table
							$(formRow).prependTo("#" + prependDiv + " > tbody").find('*').effect("highlight", {color:"#71a01e"}, 7000);
						} else {
							//If table does not exist, create the table.
							var table = "<div class=\"row\"><div class=\"col-md-10 col-xs-9\"><h4>" + data['supplier_name'] + "</h4></div><div class=\"col-md-2 col-xs-3\"><a class=\"btn btn-default btn-sm col-md-12 col-xs-12 success-colour\" href=\"/Miles-Apart/web/app_dev.php/staff/purchase-orders/send-purchase-order/2\" role=\"button\"><span class=\"glyphicon glyphicon-export\"></span><span class=\"hide_on_xs\">Send order</span></a></div></div><table class=\"table table-striped small existing_products\" id=\"" + prependDiv + "\"><thead><tr><th>Product name</th><th>Price</th><th>Ordered Quantity</th><th>Product Code</th><th class=\"hide_on_xs\">Barcode</th><th>Supplier</th></thead><tbody></tbody></table>";
							$('#outstanding_purchase_orders').prepend(table).each(function() {
							  	$(formRow).prependTo("#" + prependDiv + " > tbody").find('*').effect("highlight", {color:"#71a01e"}, 3000);
							});

						} 
							
					} else {
						//If table does not exist, create the table.
						$(formRow).prependTo("table > tbody").find('*').effect("highlight", {color:"#71a01e"}, 2000);
				 	}

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

/*******************************************
* Update qty of products in product list ( when qty is changed in the green popup)
*******************************************/
function updateProductListQty(productId, submitUrl) {
  	var productNewQty = $("#product_new_qty").val();
  
	//Make AJAX call to update database
	$.ajax({
		type: "POST",
		url: submitUrl + "-new-qty",
		dataType: 'json',
		data: { new_qty: productNewQty, product_id: productId  },
		success: function(data){
			if (data["success"] == true) {
				//Find the row of the updated product
				//Update the qty in the table and highlight that row
				$('#product_row_' +productId + '> td:nth-child(3)').html(productNewQty);
				$('#product_row_' +productId).find("*").effect("highlight", {color:"#71a01e"}, 2000);

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

/*******************************************
* Function to add PRODUCT GROUP to the transfer request list 
*******************************************/
function addProductGroupToTransferRequest(barcode) {
	//Create and make the AJAX call to return a product or array of products that match the barcode/supplier code/search
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "add-product-group-to-transfer-request",
		dataType: 'json',
		data: { barcode : barcode  },
		success: function(data){
			//Need to get if a product was found from server
			if(data['found'] == true) {
			
			  	//First the pop up
			  	var popupContent = "<div class=\"row\"><div class=\"col-md-4 col-xs-10\"><h6>Product Group Name</h6><h5>" + data['product_name'] + "</h5></div><div class=\"hide_on_non_xs_jquery col-xs-1\"><a href=\"#\" class=\"popup_close\" onclick=\"hidePopup();\"><span class=\"glyphicon glyphicon-remove\"></span> </a></div><div class=\"col-md-2 col-xs-6\"><h6>Qty</h6><input class=\"col-md-6 col-xs-6 form-control\"  id=\"product_new_qty\" type=\"text\" /></div><div class=\"col-md-2 col-xs-6\"><h6>Supplier Code</h6><h5>" + data['product_supplier_code'] + "</h5></div><div class=\"col-md-2 col-xs-6\"><h6>Barcode</h6><h5 class=\"small\">" + data['product_barcode'] + "</h5></div><span style=\"display:none\" id=\"latest_stocktake_product_id\">"+ data['product_id'] + "</span><div class=\"col-md-1 col-md-offset-1 col-xs-6\"><a href=\"#\" class=\"btn btn-default col-xs-12\" style=\"margin-top:10px\" onclick=\"updateProductListQty('" + data['product_id'] + "', '" + submitUrl +"')\">Update</a></div></div>";

			  	//if ($("#popup_form_response").css('background-color') == 'rgba(219, 41, 37, .9)') {
				 	$('#popup_form_response').animate({backgroundColor: 'rgba(113, 160, 30, .97)'}, 500);
			  	//}

			  	$("#popup_form_response_content").html(popupContent);
			  	$("#popup_form_response").slideDown('slow', function() {
				 	//Set focus on the Qty form field.
				 	if(functionName != "PrintRequest") {
				 		$("#product_new_qty").focus();
			  		}
			  	});
				  
			  	//Then the table row
				//Define the form row
		  		var formRow = "<tr id=\"product_row_" + data['product_id'] + "\"  class=\"new-table-add\"><td>" + data['product_name'] + "</td><td>" +
				data['product_price'] + "</td><td>" + data['product_qty'] + "</td><td>" + data['product_supplier_code'] + "</td><td class=\"hide_on_xs_jquery\">" + data['product_barcode'] + "</td><td>" + data['supplier_name'] + "</td></tr>";
				  	
			 	//Otherwise add to the single table.
			 	$(formRow).prependTo("table > tbody").find('*').effect("highlight", {color:"#71a01e"}, 2000);
				  	
			  	$(".hide_on_xs_jquery").addClass("hide_on_xs");
			  	$(".hide_on_non_xs_jquery").addClass("hide_on_non_xs");

			  	//Reset the barcode and search fields
			  	$(".add_product_to_list_product_barcode").val("");
			  	$(".add_product_to_list_product_supplier_code").val("");
			  	$(".add_product_to_list_product_name").val("");

			//If product has been found and already exists in storage box.
			} else if (data['duplicate'] == true) {
				//Show the warning popup
				var popupContent = "<div class=\"row\"><div class=\"col-xs-10\"><h6>Note</h6></div><div class=\"col-xs-2\"><a href=\"#\" class=\"popup_close\" onclick=\"hidePopup();\"><span class=\"glyphicon glyphicon-remove\"></span> </a></div><div class=\"col-md-9\"><h5>" + data['product_name'] + " already added to list</h5></div><div class=\"col-md-1 col-xs-12\"><h6>Qty</h6><h5><input class=\"col-md-12 col-xs-12 form-control\"  style=\"color:#888888\" id=\"product_new_qty\" type=\"text\" value=\"" + data['product_qty'] + "\" onfocus=\"if (this.value == '" + data['product_qty'] + "') {this.value = ''; this.style.color='#333333'}\" onblur=\"if (this.value == '') {this.value = '" + data['product_qty'] + "'; this.style.color='#888888';}\" /></h5></div><div class=\"col-md-2\"><a href=\"#\" class=\"btn btn-default col-md-12 col-xs-12\" style=\"margin-top:20px\" onclick=\"updateProductListQty('" + data['product_id'] + "', '" + submitUrl +"')\">Update Qty</a></div></div>";
				$("#popup_form_response_content").html(popupContent);
				
				//if ($("#popup_form_response").css('background-color') == 'rgba(113, 160, 30, .9)') {
				  $('#popup_form_response').animate({backgroundColor: 'rgba(245, 114, 27, .97)'}, 500);
				//}
				$("#popup_form_response").slideDown('slow');
			 
				//Reset the barcode and search fields
				$(".add_product_to_list_product_barcode").val("");
				$(".add_product_to_list_product_supplier_code").val("");
				$(".add_product_to_list_product_name").val("");
			 
			//If the product has not been found
			} else {
			 	alert("the product group could not be found");
			}
		}, 
		fail: function() {
			alert('failed');
		}
	});
}
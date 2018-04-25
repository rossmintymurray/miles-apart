/*////////////////////Hiding and showing the shopping cart///////////////////*/
//Hide the shopping basket on page load.
function hideShoppingBasket() {
	$('#shopping_basket_wrapper').css("display", "none");
}


//Create function to show the shopping basket for 10 seconds, then hide.
/*function showTimedPopupShoppingBasket() {
	$('#shopping_basket_wrapper').delay(100).fadeIn(200, function() {
	$('#shopping_basket_wrapper').delay(2000).fadeOut(1000);
});
}*/




/*End of hiding and showing the shopping basket.*/

//Initialise the array
	shoppingBasket = Array();																				
/*Shopping basket display to show on page load*/
function shoppingBasketDisp()
{
	

	//Check basket length
	if (shoppingBasket.length > 0) {
	
		//There are items in the shopping basket so create the table.

		//Define the div.
		var div = document.getElementById('shopping_basket_wrapper');
		//Hide the no items text.

		var message = document.getElementById('no_items');
		if (message) {
			div.removeChild(message);
		}

		var table_exists = document.getElementById('shopping_basket_table');
		if (table_exists) {
			div.removeChild(table_exists);
		}

		var checkoutLink = document.getElementById('check_out_link');
		if (checkoutLink) {
			div.removeChild(checkoutLink);
		}
		
		var viewBasketLink = document.getElementById('view_basket_link');
		if (viewBasketLink) {
			div.removeChild(viewBasketLink);
		}
		
		var emptyBasketLink = document.getElementById('empty_basket_link');
		if (emptyBasketLink) {
			div.removeChild(emptyBasketLink);
		}

		

		//Create the table.

		var table = document.createElement("table");
		
		//Create Headers.
		var newRow = document.createElement("tr");
		var newHead1 = document.createElement("th");
		var newHead2 = document.createElement("th");
		var newHead3 = document.createElement("th");
		var newHead4 = document.createElement("th");
		var head1 = document.createTextNode("Product");
		var head2 = document.createTextNode("");
		var head3 = document.createTextNode("Qty");
		var head4 = document.createTextNode("Price");
		
		
		
		div.appendChild(table);
		table.setAttribute("id", "shopping_basket_table");
		table.setAttribute("style", "padding:10px");
		table.appendChild(newRow);
		newRow.appendChild(newHead1);
		newHead1.appendChild(head1);
		newRow.appendChild(newHead2);
		newHead2.appendChild(head2);
		newRow.appendChild(newHead3);
		newHead3.appendChild(head3);
		newRow.appendChild(newHead4);
		newHead4.appendChild(head4);
		
		newHead1.setAttribute("class", "product_column");
		newHead2.setAttribute("class", "price_column");
		newHead3.setAttribute("class", "qty_column");
		newHead4.setAttribute("class", "total_column");
		var totalsBig = 0;

		
		//Go through contents of the table creating now row each time
		for (var i = 0; i < shoppingBasket.length; i++) 
		if ( shoppingBasket[i]["quantity"] > 0 ) {
		
		{
	
			var newRow = document.createElement("tr");
			var newCol1 = document.createElement("td");
			var newCol2 = document.createElement("td");
			var newCol3 = document.createElement("td");
			var newCol4 = document.createElement("td");
			var col1 = document.createTextNode(shoppingBasket[i]["name"]);
			var col2 = document.createTextNode(shoppingBasket[i]["weight"]);
			
			
			
			var plusHolder = document.createElement("img");
			var minusHolder = document.createElement("img");
			
			
			var qtyPlus = document.createElement("a");
			plusHolder.setAttribute('src', './images/website/icons/small_plus.png');
			minusHolder.setAttribute('src', './images/website/icons/small_minus.png');
		
			qtyPlus.setAttribute('href', 'javascript:;');
			qtyPlus.setAttribute('onclick', 'addToSessionShoppingBasket(' + shoppingBasket[i]["code"] + ', "basket_view")');
			
			var qtyMinus = document.createElement("a");
			qtyMinus.setAttribute('href', 'javascript:;');
			qtyMinus.setAttribute('onclick', 'removeFromSessionShoppingBasket(' + shoppingBasket[i]["code"] + ', "basket_view")');
			


			var col3 = document.createTextNode(" "+ shoppingBasket[i]["quantity"]);
			
		
			
			
			var col4Big = (parseFloat(shoppingBasket[i]["quantity"]) * parseFloat(shoppingBasket[i]["price"]));
			var col4 = document.createTextNode("\u00A3" + col4Big.toFixed(2));
			var totalsBig = totalsBig + (parseFloat(shoppingBasket[i]["quantity"]) * parseFloat(shoppingBasket[i]["price"]));
			var totals = totalsBig.toFixed(2);
			table.appendChild(newRow);
			
			newRow.appendChild(newCol1);
			newCol1.appendChild(col1);
			newRow.appendChild(newCol2);
			newCol2.appendChild(col2);
			newRow.appendChild(newCol3);
			
			newCol3.appendChild(qtyMinus);
			qtyMinus.appendChild(minusHolder);
			newCol3.appendChild(col3);
			
			
			newCol3.appendChild(qtyPlus);
			qtyPlus.appendChild(plusHolder);
		
			newRow.appendChild(newCol4);
			newCol4.appendChild(col4);
			newCol1.setAttribute("class", "product_column");
		newCol2.setAttribute("class", "price_column");
		newCol3.setAttribute("class", "qty_column");
		newCol4.setAttribute("class", "total_column");
		}
		}
	
	//Create Totals
		var newRowt = document.createElement("tr");
		var newHeadB = document.createElement("td");
		var newHeadB2 = document.createElement("td");
		var newHeadT = document.createElement("td"); 
		var newColt = document.createElement("td");
		var tHead = document.createTextNode("Total")
		var colt = document.createTextNode("\u00A3" + totals);
		
		table.appendChild(newRowt);
		newRowt.appendChild(newHeadB);
		newRowt.appendChild(newHeadB2);
		newRowt.appendChild(newHeadT);
		newHeadT.setAttribute("class", "bold_totals");
		newHeadT.appendChild(tHead);
		newRowt.appendChild(newColt);
		newColt.setAttribute("class", "bold_totals");
		newColt.appendChild(colt);
		
		var newLink = document.createElement("a");
		var checkOutText = document.createTextNode("Check Out");
		div.appendChild(newLink);
		newLink.setAttribute("class", "green_button");
		newLink.setAttribute("id", "check_out_link");
		newLink.setAttribute("href","checkout.php");
	
		newLink.appendChild(checkOutText);
		
		var newLink2 = document.createElement("a");
		var viewBasketText = document.createTextNode("View Basket");
		div.appendChild(newLink2);
		newLink2.setAttribute("class", "grey_button");
		
		newLink2.setAttribute('id', 'view_basket_link');
		newLink2.setAttribute('href','javascript:;');
		newLink2.setAttribute('onclick','viewBasket()');
		newLink2.appendChild(viewBasketText);
		
		var newLink3 = document.createElement("a");
		var emptyBasketText = document.createTextNode("Empty Basket");
		newHeadB.appendChild(newLink3);
		
		newLink3.setAttribute('id', 'empty_basket_link');
		newLink3.setAttribute('href','javascript:;');
		newLink3.setAttribute('onclick','emptyBasket()');
		newLink3.appendChild(emptyBasketText);


	} else {
	
	var div = document.getElementById('shopping_basket_wrapper');

var message = document.getElementById('no_items');
		if (message) {
			div.removeChild(message);
		}

		var table_exists = document.getElementById('shopping_basket_table');
		if (table_exists) {
			div.removeChild(table_exists);
		}

		var checkoutLink = document.getElementById('check_out_link');
		if (checkoutLink) {
			div.removeChild(checkoutLink);
		}
		
		var viewBasketLink = document.getElementById('view_basket_link');
		if (viewBasketLink) {
			div.removeChild(viewBasketLink);
		}

	var div2 = document.getElementById('shopping_basket_wrapper');
	var newDiv = document.createElement("p");
	var none = document.createTextNode("Your basket is currently empty.");
	if (div2) {
	div2.appendChild(newDiv);
	newDiv.setAttribute("id", "no_items");
	newDiv.appendChild(none);
	}
	}	

}

function addToShoppingBasket($id, location)
{

	if (location == "product") {
	
	var productCode = $id;
	
	var priceDiv = "product_view_port_price_" + productCode;
	var itemName = document.getElementById($id).getElementsByTagName('h5')[0].innerText;
	var itemPrice = document.getElementById(priceDiv).innerText;
	var itemCode = document.getElementById($id).getElementsByTagName('select')[0].value;
	var itemWeight = $("#"+ $id + " option:selected").html();
	var itemQuantity = 1;
	
	} else if (location == "product_view") {
	
	var itemCode = $id;
	
	
	var itemName = document.getElementById("product_display_name").innerText;
	var itemPrice = document.getElementById("product_display_price_" + itemCode).innerText;
	
	var itemWeight = document.getElementById("product_display_weight_" + itemCode).innerText;
	var itemQuantity = 1;
	
	} else if (location == "rotator_view") {
	
	var itemCode = $id;
	
	
	var itemName = document.getElementById("product_rotator_name_text").innerText;
	var itemPrice = document.getElementById("product_rotator_price_" + itemCode).innerText;
	
	var itemWeight = document.getElementById("product_rotator_weight_" + itemCode).innerText;
	var itemQuantity = 1;
	
	
	
	} else if (location == "checkout_page_view") {
	
	var itemCode = $id;
	
	
	var itemName = document.getElementById("checkout_product_name_" + itemCode).innerText;
	var itemPrice = document.getElementById("checkout_product_price_" + itemCode).innerText;
	

	var itemQuantity = 1;
	
	
	
	}

		/*Check the array contents to see if there is the selected product already in the basket,
		if so increase the quantity by 1.*/


		//If there is stuff un the basket.
		if (shoppingBasket.length > 0) {
			var inBasket = 0;
			//Go through each item in the basket.
	
			for (var i = 0; i < shoppingBasket.length; i++) 
			{	
				//Set existing id for each item in basket.
				var existingId = shoppingBasket[i]["code"];
				//If item code in basket equals code submitted.
				if (parseInt(existingId) == parseInt(itemCode)) {
					var inBasket = 1;
					var location = i;
					break;
				} else {
					var inBasket = 0;
				}
			}
	
		
			if (inBasket == 1) {
				//Add existing quantity to quantity submitted.
			
				
			
		 		var itemQuantity = parseInt(shoppingBasket[location]["quantity"]) + parseInt(itemQuantity);
		 		//Set new variables
				shoppingBasket[location]["code"] = itemCode;
				shoppingBasket[location]["name"] = itemName;
				shoppingBasket[location]["price"] = itemPrice;
				shoppingBasket[location]["quantity"] = itemQuantity;
				shoppingBasket[location]["weight"] = itemWeight;
			
			
				//If existing id does not match submitted id.	
		 } else if (inBasket == 0) {
		 	//Check the quantity is less than the max quantity.
			
			
			
		 	/*If the product does not exist then add the product to the array*/
			shoppingBasketContents = Array();
			shoppingBasketContents["code"] = itemCode;
			shoppingBasketContents["name"] = itemName;
			shoppingBasketContents["price"] = itemPrice;
			shoppingBasketContents["quantity"] = itemQuantity;
			shoppingBasketContents["weight"] = itemWeight;
			
			var location = shoppingBasket.length;
			shoppingBasket[location] = shoppingBasketContents;
			
		 }
		
		
	//If there is not stuff in the basket.
	} else {
	
		/*If the product does not exist then add the product to the array*/
		shoppingBasketContents = Array();
		shoppingBasketContents["code"] = itemCode;
		shoppingBasketContents["name"] = itemName;
		shoppingBasketContents["price"] = itemPrice;
		shoppingBasketContents["quantity"] = itemQuantity;
		shoppingBasketContents["weight"] = itemWeight;
		var location = shoppingBasket.length;
		shoppingBasket[location] = shoppingBasketContents;
}
	
	shoppingBasketDisp();
	
	
	

}

function addToShoppingBasketFromAdd($id, weight)
{
	var productCode = $id;
	var nameDiv = "pack_"+$id;
	var priceDiv = "product_view_port_price_" + productCode;
	var itemName = document.getElementById(nameDiv).innerText;
	var itemPrice = document.getElementById(priceDiv).innerText;
	var itemCode = $id
	var itemWeight = weight
	var itemQuantity = 1;

/*Check the array contents to see if there is the selected product already in the basket,
if so increase the quantity by 1.*/

	//If there is stuff un the basket.
	if (shoppingBasket.length > 0) {
		var inBasket = 0;
		//Go through each item in the basket.
	
		for (var i = 0; i < shoppingBasket.length; i++) 
		{	
			//Set existing id for each item in basket.
			var existingId = shoppingBasket[i]["code"];
			//If item code in basket equals code submitted.
			if (parseInt(existingId) == parseInt(itemCode)) {
				var inBasket = 1;
				var location = i;
				break;
			} else {
				var inBasket = 0;
			}
		}
	
		
		if (inBasket == 1) {
			//Add existing quantity to quantity submitted.
		 	var itemQuantity = parseInt(shoppingBasket[location]["quantity"]) + parseInt(itemQuantity);
		 	//Set new variables
			shoppingBasket[location]["code"] = itemCode;
			shoppingBasket[location]["name"] = itemName;
			shoppingBasket[location]["price"] = itemPrice;
			shoppingBasket[location]["quantity"] = itemQuantity;
			shoppingBasket[location]["weight"] = itemWeight;
			
		//If existing id does not match submitted id.	
		 } else if (inBasket == 0) {
		 	/*If the product does not exist then add the product to the array*/
			shoppingBasketContents = Array();
			shoppingBasketContents["code"] = itemCode;
			shoppingBasketContents["name"] = itemName;
			shoppingBasketContents["price"] = itemPrice;
			shoppingBasketContents["quantity"] = itemQuantity;
			shoppingBasketContents["weight"] = itemWeight;
			var location = shoppingBasket.length;
			shoppingBasket[location] = shoppingBasketContents;
			
		 }
		
		
	//If there is not stuff in the basket.
	} else {
	
		/*If the product does not exist then add the product to the array*/
		shoppingBasketContents = Array();
		shoppingBasketContents["code"] = itemCode;
		shoppingBasketContents["name"] = itemName;
		shoppingBasketContents["price"] = itemPrice;
		shoppingBasketContents["quantity"] = itemQuantity;
		shoppingBasketContents["weight"] = itemWeight;
		var location = shoppingBasket.length;
		shoppingBasket[location] = shoppingBasketContents;

	}
	shoppingBasketDisp();
	showTimedPopupShoppingBasket();

}



function addOneToShoppingBasket($id, from)
{
	var itemCode = $id;
	if (shoppingBasket.length > 0) {
		var inBasket = 0;
		//Go through each item in the basket.
	
		for (var i = 0; i < shoppingBasket.length; i++) 
		{	
			//Set existing id for each item in basket.
			var existingId = shoppingBasket[i]["code"];
			//If item code in basket equals code submitted.
			if (parseInt(existingId) == parseInt(itemCode)) {
				var inBasket = 1;
				var location = i;
				break;
			} else {
				var inBasket = 0;
			}
		}
		if (inBasket == 1) {
			//Add existing quantity to quantity submitted.
		 	var itemQuantity = parseInt(shoppingBasket[location]["quantity"]) + 1;
		 	//Set new variables
			
			shoppingBasket[location]["quantity"] = itemQuantity;
		if (from == "view") {
		
			viewBasket();
			shoppingBasketDisp();
		} else if (from == "basket_view"){
			shoppingBasketDisp();
			}
		//If existing id does not match submitted id.	
		 } else if (inBasket == 0) {
			alert ('This item is not yet in your basket');
		}
	}
}

function minusOneFromShoppingBasket($id, from)
{
	var itemCode = $id;
	if (shoppingBasket.length > 0) {
		var inBasket = 0;
		//Go through each item in the basket.
	
		for (var i = 0; i < shoppingBasket.length; i++) 
		{	
			//Set existing id for each item in basket.
			var existingId = shoppingBasket[i]["code"];
			//If item code in basket equals code submitted.
			if (parseInt(existingId) == parseInt(itemCode)) {
				var inBasket = 1;
				var location = i;
				break;
			} else {
				var inBasket = 0;
			}
		}
		if (inBasket == 1) {
			//Add existing quantity to quantity submitted.
		 	var itemQuantity = parseInt(shoppingBasket[location]["quantity"]) - 1;
		 	//Set new variables
			
			shoppingBasket[location]["quantity"] = itemQuantity;
			if (from == "view") {
			viewBasket();
			shoppingBasketDisp();
			} else if (from == "basket_view") {
			shoppingBasketDisp();
			}
		//If existing id does not match submitted id.	
		 } else if (inBasket == 0) {
			alert ('This item is not yet in your basket');
		}
	}
}



function viewBasket()
{

	//Check basket length
	if (shoppingBasket.length > 0) {
	
		//There are items in the shopping basket so create the table.
		var outerDiv = document.getElementById('checkout_outer_outer_wrapper');
		var div = document.getElementById('checkout_outer_wrapper');
		var innerDiv = document.getElementById('checkout_wrapper');

		var table_exists = document.getElementById('checkout_wrapper');
		if (table_exists) {
			div.removeChild(table_exists);
		}
		
		var check_out = document.getElementById('check_out_check_out_link');
		if (check_out) {
			div.removeChild(check_out);
		}
		
		var close = document.getElementById('check_out_close_link');
		if (close) {
			div.removeChild(close);
		}
		
		
		

		
	
		
	//Target and show the popup.

	var page = document.getElementById("page_content_wrapper");
	
	
	
	//Create the wrapper.
	var divWrapper = document.createElement("div");
	divWrapper.setAttribute('id', 'checkout_wrapper');
	div.appendChild(divWrapper);
	
	//Create the wapper header.
	var checkOutHead = document.createElement("h3");
	checkOutHead.setAttribute('class', 'checkout_header');
	var checkOutHeadText = document.createTextNode('Your Basket');
	var checkOutSubHead = document.createElement("h6");
	checkOutSubHead.setAttribute('class', 'checkout_subheader');

	var checkOutSubHeadText = document.createTextNode('');
	
	
	
	divWrapper.appendChild(checkOutHead);
	checkOutHead.appendChild(checkOutHeadText);
	divWrapper.appendChild(checkOutSubHead);
	checkOutSubHead.appendChild(checkOutSubHeadText);
	
	
	
	
	//Create Headers.
		var newRow = document.createElement("tr");
		var newHead1 = document.createElement("th");
		var newHead2 = document.createElement("th");
		var newHead3 = document.createElement("th");
		var newHead4 = document.createElement("th");
		var newHead5 = document.createElement("th");
		var head1 = document.createTextNode("Product");
		var head2 = document.createTextNode("Price");
		var head3 = document.createTextNode("Qty");
		var head4 = document.createTextNode("Total");
		var head5 = document.createTextNode("Weight");
		newHead1.setAttribute("class", "checkout_table_product_column");
		newHead2.setAttribute("class", "checkout_table_price_column");
		newHead3.setAttribute("class", "checkout_table_qty_column");
		
		newHead4.setAttribute("class", "checkout_table_total_column");
		newHead5.setAttribute("class", "checkout_table_weight_column");
		
		var table = document.createElement("table");
		table.setAttribute('id', 'checkout_table');
		divWrapper.appendChild(table);
		
		table.appendChild(newRow);
		newRow.appendChild(newHead1);
		newHead1.appendChild(head1);
		newRow.appendChild(newHead5);
		newHead5.appendChild(head5);
		newRow.appendChild(newHead2);
		newHead2.appendChild(head2);
		newRow.appendChild(newHead3);
		newHead3.appendChild(head3);
		newRow.appendChild(newHead4);
		newHead4.appendChild(head4);
	
	
	
	
	
	
	//Set totals.
	var totalsBig = 0;
	for (var i = 0; i < shoppingBasket.length; i++) 
	{
		
	
		var newRow = document.createElement("tr");
		var newCol1 = document.createElement("td");
		var newCol2 = document.createElement("td");
		var newCol3 = document.createElement("td");
		var newCol4 = document.createElement("td");
		var newCol5 = document.createElement("td");
		var newCol6 = document.createElement("td");
		var newCol7 = document.createElement("td");
		var plusHolder = document.createElement("img");
		var minusHolder = document.createElement("img");
		var qtyPlus = document.createElement("a");
		
		plusHolder.setAttribute('src', './images/website/icons/small_plus.png');
		minusHolder.setAttribute('src', './images/website/icons/small_minus.png');
		
		qtyPlus.setAttribute('href', 'javascript:;');
		qtyPlus.setAttribute('onclick', 'addToSessionShoppingBasket(' + shoppingBasket[i]["code"] + ', "view")');
		var qtyPlusText = document.createTextNode(" +");
		var qtyMinus = document.createElement("a");
		qtyMinus.setAttribute('href', 'javascript:;');
		qtyMinus.setAttribute('onclick', 'removeFromSessionShoppingBasket(' + shoppingBasket[i]["code"] + ', "view")');
		var qtyMinusText = document.createTextNode("- ");
		var col1 = document.createTextNode(shoppingBasket[i]["name"]);
		var col2 = document.createTextNode("\u00A3" + shoppingBasket[i]["price"]);		
		var col3 = document.createTextNode(" " + shoppingBasket[i]["quantity"] + " ");
		var col4Big = ((shoppingBasket[i]["quantity"]) * (shoppingBasket[i]["price"]));
		var col4 = document.createTextNode("\u00A3" + col4Big.toFixed(2));
		var col5 = document.createTextNode(shoppingBasket[i]["weight"]);
		var totalsBig = totalsBig + ((shoppingBasket[i]["quantity"]) * (shoppingBasket[i]["price"]));
		var totals = totalsBig.toFixed(2);
		
		newCol1.setAttribute("class", "checkout_table_product_column");
		newCol2.setAttribute("class", "checkout_table_price_column");
		newCol3.setAttribute("class", "checkout_table_qty_column");
		newCol4.setAttribute("class", "checkout_table_total_column");
		newCol5.setAttribute("class", "checkout_table_weight_column");
		newCol6.setAttribute("class", "checkout_table_plus_column");
		newCol7.setAttribute("class", "checkout_table_minus_column");
	
		if (shoppingBasket[i]["quantity"] > 0) {
		table.appendChild(newRow);
		newRow.appendChild(newCol1);
		newCol1.appendChild(col1);
		newRow.appendChild(newCol5);
		newCol5.appendChild(col5);
		newRow.appendChild(newCol2);
		newCol2.appendChild(col2);
		
		
		
		
		newRow.appendChild(newCol3);
		newCol3.appendChild(qtyMinus);
		qtyMinus.appendChild(minusHolder);
		newCol3.appendChild(col3);
		
		newCol3.appendChild(qtyPlus);
		qtyPlus.appendChild(plusHolder);
		
		newRow.appendChild(newCol4);
		newCol4.appendChild(col4);
		
		
		}
		
	}
	
	//Create Totals
		var newRowt = document.createElement("tr");
		var newHeadB = document.createElement("td");
		var newHeadB2 = document.createElement("td");
		var newHeadT = document.createElement("td"); 
		var newHeadT2 = document.createElement("td");
		var newColt = document.createElement("td");
		var tHead = document.createTextNode("Total")
		var colt = document.createTextNode("\u00A3" + totals);
		
		table.appendChild(newRowt);
		newRowt.appendChild(newHeadB);
		newRowt.appendChild(newHeadB2);
		newRowt.appendChild(newHeadT);
		newRowt.appendChild(newHeadT2);
		newHeadT2.setAttribute("class", "bold_totals");
		
		newHeadT2.appendChild(tHead);
		newRowt.appendChild(newColt);
		newColt.setAttribute("class", "bold_totals");
		newColt.appendChild(colt);
		
		if (totals == 0) {
			closeCheckout();
		}
		
		var newLink = document.createElement("a");
		var checkOutText = document.createTextNode("Check Out");
		div.appendChild(newLink);
		newLink.setAttribute("class", "green_button");
		newLink.setAttribute("id", "check_out_check_out_link");
		newLink.setAttribute("href","checkout.php");
		
		newLink.appendChild(checkOutText);
		
		var newLink2 = document.createElement("a");
		var viewBasketText = document.createTextNode("Close");
		div.appendChild(newLink2);
		newLink2.setAttribute("class", "grey_button");
		newLink2.setAttribute('id', 'check_out_close_link');
		newLink2.setAttribute('href','javascript:;');
		newLink2.setAttribute('onclick','parent.$.fancybox.close();');
		newLink2.appendChild(viewBasketText);
		
		var newLink3 = document.createElement("a");
		var emptyBasketText = document.createTextNode("Empty Basket");
		newHeadB.appendChild(newLink3);
		
		newLink3.setAttribute('id', 'check_out_empty_basket_link');
		newLink3.setAttribute('href','javascript:;');
		newLink3.setAttribute('onclick','emptyBasket()');
		newLink3.appendChild(emptyBasketText);
		
		
		
	
		

	}
	
	$("#view_basket_link").fancybox({
		//Checkout div.
		
		href: '#checkout_outer_outer_wrapper'
		});
}

function closeCheckout() 
{
			$('#checkout_outer_wrapper').fadeOut(200);
	$('#shopping_basket_wrapper').delay(200).fadeOut(200);
	shoppingBasketDisp();
}
	
	



var shoppingBasket = Array();
function sessionShoppingBasketDisp()
{
var ajaxFile = "functions/shopping_basket.php";
$.post(ajaxFile, function(data){$('#shopping_basket_wrapper').html(data);
});}






function addToSessionShoppingBasket($item_code, from)
{
var success = 0;
var ajaxFile = "functions/add_to_cart.php";
if (from == "product") {

var item_code  = document.getElementById($item_code).getElementsByTagName('select')[0].value;

} else if (from == "view") {
var item_code = $item_code;
} else if (from == "product_view") {
var item_code = $item_code;
} else if (from == "basket_view") {
var item_code = $item_code;
} else if (from == "rotator_view") {
var item_code = $item_code;
} else if (from == "checkout_page_view") {
var item_code = $item_code;
}

$.post(ajaxFile, {item_code: item_code}, function(data) { 

 if (data ==1 && from == "product") {
 	success = 1;
 	sessionTotalDisp();
 	
 	
 	addToShoppingBasket($item_code, from);
 	
  } else 
  
  if (data ==1 && from == "view") {
 	success = 1;
 	sessionTotalDisp();
 	
 	
 	addOneToShoppingBasket($item_code, from);
  
  } else 
  
  if (data ==1 && from == "product_view") {
  success = 1;
 	sessionTotalDisp();
 	
 	addToShoppingBasket($item_code, from);
  
   } else 
  
  if (data ==1 && from == "basket_view") {
  success = 1;
 	sessionTotalDisp();
 	
 	addOneToShoppingBasket($item_code, from);
 
 } else if (data ==1 && from == "rotator_view") {
  success = 1;
 	sessionTotalDisp();
 	
 	addToShoppingBasket($item_code, from);
 
 } else if (data ==1 && from == "checkout_page_view") {
  success = 1;
 	sessionTotalDisp();
 	addOneToCheckoutBasket($item_code);
 	addToShoppingBasket($item_code, from);
 	} else {
 	
 	alert ("Sorry, there is not enough stock available to add another to your basket");
 	return false;
 	
 }



});


}


function addOneToCheckoutBasket($item_code) {

	//Use the $item code to target the display element so that it can be increased by 1
	var divName = "#checkout_page_quantity_" + $item_code;
	var div = document.getElementById("checkout_page_quantity_" + $item_code);
	var priceDiv = "#checkout_product_price_" + $item_code;
	var quantity = div.innerHTML;
	var price = priceDiv.innerHTML;
	var newQuantity = parseInt(quantity) + 1;
	alert (price);
	var newPrice = parseFloat(price) + (parseFloat(price) / parseInt(quantity));


	$(divName).html(newQuantity);
	$(priceDiv).html(newPrice);

}

function minusOneTFromCheckoutBasket($item_code) {

	//Use the $item code to target the display element so that it can be increased by 1
	var divName = "#checkout_page_quantity_" + $item_code;
	var div = document.getElementById("checkout_page_quantity_" + $item_code);
	
	var quantity = div.innerHTML;
	
	var newQuantity = parseInt(quantity) - 1;
	

	$(divName).html(newQuantity);

}
	
	


function removeFromSessionShoppingBasket($item_code, from)
{
var success = 0;
var ajaxFile = "functions/remove_from_cart.php";
if (from == "product") {

var item_code  = document.getElementById($item_code).getElementsByTagName('select')[0].value;
} else if (from == "view") {
var item_code = $item_code;
} else if (from == "basket_view") {
var item_code = $item_code;
} else if (from == "checkout_page_view") {
var item_code = $item_code;
}
$.post(ajaxFile, {item_code: item_code}, function(data) { 

 if (data ==1 && from == "product") {
 	success = 1;
 	sessionTotalDisp();
 	
 	
 	minusOneFromShoppingBasket($item_code, from);
 	
  } else 
  
  if (data ==1 && from == "view") {
 	success = 1;
 	sessionTotalDisp();
 	
 	
 	minusOneFromShoppingBasket($item_code, from);
 	
 	} else 
  
  if (data ==1 && from == "basket_view") {
 	success = 1;
 	sessionTotalDisp();
 	
 	
 	minusOneFromShoppingBasket($item_code, from);
  
 } else 
  
  if (data ==1 && from == "checkout_page_view") {
 	success = 1;
 	sessionTotalDisp();
 	minusOneTFromCheckoutBasket($item_code);
 	
 	minusOneFromShoppingBasket($item_code, from);
  
 } else {
 	
 	alert ("Sorry, there is not enough stock available to add another");
 	return false;
 	
 }



});


}

function sessionTotalDisp() {
	var ajaxFile = "functions/get_total_price.php";
	$.post(ajaxFile, function(data){$('#basket_total_price_display').html(data);
});}

function changeProductImage(imageId) {
	var ajaxFile = "functions/get_product_image.php";
	
	 
	$.post(ajaxFile, {imageId: imageId}, function(data) {
	$('#product_display_page_image_wrapper').fadeOut(300, function () {
$('#product_display_page_image_wrapper').fadeIn(400).html(data);
});
});

}

function getCustomerAddress () {
	var ajaxFile = "functions/get_customer_address.php";
	var addressId = document.getElementById('address_select_drop_down').value;
	
		$.post(ajaxFile, {addressId: addressId}, function(data){
			$('#customer_address_wrapper').fadeOut(10, function () {
			$('#customer_address_wrapper').fadeIn(100).html(data);
		});
			});
}
	
	
	
function checkoutLogin () {
	var ajaxFile = "functions/checkout_login.php";
	
	
		$.post(ajaxFile, function(data){
			$.fancybox({ 
				dataType: 'html',
				 
				content:data
			});
			
		});
}

function closeCheckoutLogin() {

	$('#login_outer_wrapper').fadeOut(100);
}
	
function showSimilarProduct(showId) {
	var port = "#similar_product_view_port_" + showId;
	$(port).slideUp(1000);

	var newPort = "#similar_product_view_port_5";

	$(newPort).slideDown(1000);

}	
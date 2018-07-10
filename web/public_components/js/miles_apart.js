/*********************************************
** This code pulls intit argumants from header 
*********************************************/
var this_js_script = $('script[src*=miles_apart]'); // or better regexp to get the file name..

var environment = this_js_script.attr('data-environment');   
if (typeof environment === "undefined" ) {
   var environment = 'prod';
}

//Define the base URL that will be used for all calls - depending on environment
//UPDATET HIS WHEN ON PRODUCTION SERVER
if(environment == "dev") {
    var globalBaseUrl = "http://localhost:8888/Miles-Apart/web/app_dev.php/";
} else if (environment == "test") {
    var globalBaseUrl = "http://test.miles-apart.com/app_test.php/";
} else {
    var globalBaseUrl = "http://www.miles-apart.com/";
}

//Create the onload event function to load other functions as page loaded
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

//Add the functions to be loaded on page load
addLoadEvent(basketAddFunctionFromShop);
addLoadEvent(submitProductQuestion);
addLoadEvent(basketAddFunctionFromProduct);
addLoadEvent(addBasketToWishList);
addLoadEvent(emptyBasket);
addLoadEvent(postcodeFormSubmit);
addLoadEvent(billingPostcodeFormSubmit);
addLoadEvent(shippingAddressSelectUpdateForm);
addLoadEvent(shippingBillingAddressSelectUpdateForm);
addLoadEvent(businessCustomerToggle);
addLoadEvent(gridviewToggle);


$(document).ready(function() {
 
    $("#owl-slider").owlCarousel({
        autoPlay : 5000,
        slideSpeed : 800,
        paginationSpeed : 800,
        singleItem:true,
        transitionStyle : "fade",
        pagination: false,
        navigation: false
    });

    $("#mobile-product-images").owlCarousel({
        
        navigation : false, // Show next and prev buttons
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem: true,
        pagination: true,
        autoHeight : true,
        
    });
 
});


$('ul.tabs').each(function(){
    // For each set of tabs, we want to keep track of
    // which tab is active and its associated content
    var $active, $content, $links = $(this).find('a');

    // If the location.hash matches one of the links, use that as the active tab.
    // If no match is found, use the first link as the initial active tab.
    $active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
    $active.addClass('active');

    $content = $($active[0].hash);

    // Hide the remaining content
    $links.not($active).each(function () {
        $(this.hash).hide();
    });

    // Bind the click event handler
    $(this).on('click', 'a', function(e){
        // Make the old tab inactive.
        $active.removeClass('active');
        $content.hide();

        // Update the variables with the new link and content
        $active = $(this);
        $content = $(this.hash);

        // Make the tab active.
        $active.addClass('active');
        $content.show();

        // Prevent the anchor's default click action
        e.preventDefault();
    });


});

function postcodeFormSubmit() {
    $("#shipping_find_address").click(function(e){
        var postcode = $("#milesapart_basketbundle_checkoutdelivery_address_postcode").val();
        e.preventDefault(); //prevent submit
        
        postcodeFormSubmission(postcode);
    });
}

function postcodeFormSubmission(postcode) {
    $("#shipping_address_select").slideDown();

    //Call the serverr code and return the formatted addresses from in html
    $.ajax({
        type: "POST",
        url: globalBaseUrl + "basket/checkout/shipping/get-postcode-addresses",
        dataType: 'html',
        data: { postcode : postcode },
        success: function(data){
            
            $("#shipping_address_select > option").replaceWith(data);


        }, 
        fail: function() {
            alert('failed');
        }
  });
}

function shippingAddressSelectUpdateForm() {
    $("#shipping_address_select").change(function(e) {

       
        var address = this.value;
       
        var add = address.split(",");

        var add1 = add[0].split("-");

        //Get the form name 
        var form = $('#shipping_address_select').parents("form").attr('name');

        //Fillin the form fields
        $("#" + form + "_customer_address_line_1").val(add1[0]);
        $("#" + form + "_customer_address_line_2").val(add1[1]);
        $("#" + form + "_customer_address_town").val(add[1]);
        $("#" + form + "_customer_address_county").val(add[2]);


        
    });
}

function billingPostcodeFormSubmit() {
    $("#shipping_billing_find_address").click(function(e){
        var postcode = $("#milesapart_basketbundle_checkoutdelivery_billing_address_customer_address_postcode").val();
        e.preventDefault(); //prevent submit
        
        billingPostcodeFormSubmission(postcode);
    });
}

function billingPostcodeFormSubmission(postcode) {
    $("#shipping_billing_address_select").slideDown();

    //Call the serverr code and return the formatted addresses from in html
    $.ajax({
        type: "POST",
        url: globalBaseUrl + "shipping/get-postcode-addresses",
        dataType: 'html',
        data: { postcode : postcode },
        success: function(data){
            
            $("#shipping_billing_address_select > option").replaceWith(data);


        }, 
        fail: function() {
            alert('failed');
        }
  });
}

function shippingBillingAddressSelectUpdateForm() {
    $("#shipping_billing_address_select").change(function(e) {

       
        var address = this.value;
       
        var add = address.split(",");

        var add1 = add[0].split("-");

        $("#milesapart_basketbundle_checkoutdelivery_billing_address_customer_address_line_1").val(add1[0]);
        $("#milesapart_basketbundle_checkoutdelivery_billing_address_customer_address_line_2").val(add1[1]);
        $("#milesapart_basketbundle_checkoutdelivery_billing_address_customer_address_town").val(add[1]);
        $("#milesapart_basketbundle_checkoutdelivery_billing_address_customer_address_county").val(add[2]);


        
    });
}

function submitProductQuestion() {
    $("#milesapart_publicbundle_question_submit").click(function(e) {
        var name = $("#milesapart_publicbundle_question_question_name").val();
        var email = $("#milesapart_publicbundle_question_question_email").val();
        var question = $("#milesapart_publicbundle_question_product_question_text").val();
        var productId = $("#milesapart_publicbundle_question_product").val();
        
        $.ajax({
            type: "POST",
            url: globalBaseUrl + "ask-product-question",
            dataType: "json",
            data: {name: name, email: email, question: question, productId: productId },
            beforeSend: function() {
                $('#loader').show();
                $('#ask_question_modal_content').animate({
                    opacity: 0.25,
                  }, 50);
            },
            complete: function(){
                $('#loader').hide();
                
            },
            success: function(data){
                //Add to javscript shopping cart
                swal("Done!", "Your question has been submitted.", "success");
                
                //Remove the question text in case another question is asked.
                $("#milesapart_publicbundle_question_product_question_text").val("");
                //Hide the question box
                $('#ask_question_modal').foundation('reveal', 'close');
            },
            fail: function() {
                alert('failed');
                
            }
        });

        e.preventDefault(); 

        
    });
}

function basketAddFunctionFromShop() {
    $(".add_to_basket_from_shop").unbind('click').click(function(e) {
        var basketUrl = globalBaseUrl + "basket/ajax-add";
        var product_id = $( this ).attr('id');
        basketAddFunction(basketUrl, product_id);
        e.preventDefault();  
    });
}

function basketAddFunctionFromProduct() {
    $(".add_to_basket_from_product").click(function(e) {
        var basketUrl = globalBaseUrl + "basket/ajax-add";
        var product_id = $( this ).attr('id');
        basketAddFunction(basketUrl, product_id);
        e.preventDefault();  
    });
}

function addBasketToWishList() {
    $("#add_basket_to_wish_list").click(function(e) {
        addBasketToWishListFunction();

        e.preventDefault();
        
    });
}

function addBasketToWishListFunction() {
    $.ajax({
        type: "POST",
        url: globalBaseUrl + "basket/save-to-wish-list",
        dataType: "json",
        data: {},
        success: function(data){
            //Add to javscript shopping cart
            swal("Done!", "The contents of your basket has been saved to your wish list.", "info");

            //Empty basket 
            emptyBasketFunction(false);
        },
        fail: function() {
            alert('failed');
            
        }
    });
}

function emptyBasket(showAlert) {
    $("#empty_basket").click(function(e) {
        emptyBasketFunction();
        e.preventDefault();
    });
}


function emptyBasketFunction(showAlert) {
    $.ajax({
        type: "POST",
        url: globalBaseUrl + "basket/basket-empty",
        dataType: "json",
        data: {},
        success: function(data){
            
            //Check the success 
            if(data['success'] == true) {
                //Add to javscript shopping cart
                if(showAlert != false) {
                    swal("Done!", "The contents of your basket has been emptied.", "info");
               }

                //Remove the items from the basket bar
                //Create the header
                var shopping_bar_header = "<p>Your basket is empty</h5>";
                //Overwrite the content with the header
                $("#drop").html(shopping_bar_header);

                $("#basket_button_wrapper").html("No items");
                //Update button in the basket bar to link to chackout
                $("#basket_bar_button").attr('href', '#');
            }

            
        },
        fail: function() {
            alert('failed');
            
        }
    });
}

function basketAddFunction(basketUrl, product_id) {

    //Set up the ajax function to add to server side basket.
    //Make ajax to get the supplier details for printing
    $.ajax({
        type: "POST",
        url: basketUrl,
        dataType: "json",
        data: {product_id : product_id},
        success: function(data){
            //Check if in stock
            if(data['current_stock_level'] > 0) {
                //Add to javscript shopping cart
                addToJSShoppingCart(data);

                //Check if this was the last in stock
                if(data['current_stock_level'] == 1) {
                    //Change button to disabled
                    var div = "#" + product_id;
                    $(div).addClass('disabled');
                    $(div).removeClass('add_to_basket');
                    $(div).html('Currently unavailable');

                }
            } else {
                swal("Sorry!", "This product has now sold out so no more can be added to your basket.", "warning");
            }
        },
        fail: function() {
            alert('failed'); 
        }
    });
}

function addToJSShoppingCart(data) {
    //Check if success is true.
    if(data['success'] == true) {
        
        //Check if cart table exists.
        if ($("#basket_bar_table").length) {
            //Check if product to add exists in the table.
            if ($("#product_"+data['product_id']).length) {
                
                //Update the quantity and total of the existing row in the cart table.
                //Calculate the total price
                var total_price = data['product_price'] * data['product_quantity'];
                total_price = total_price.toFixed(2);

                //Update the table data
                $("#product_"+data['product_id']+" td:nth-child(2)").html(data['product_quantity']);
                $("#product_"+data['product_id']+" td:nth-child(3)").html("£"+total_price);
                
            } else {
                
                //Add the row as it does not exist
                var newRow = "<tr id=\"product_"+data['product_id']+ "\"><td>"+ data['product_name'] + "</td><td class=\"text-center\">"+ data['product_quantity'] +"</td><td class=\"text-center\">£"+ data['product_price'] +"</td></tr>";
                
                $('#basket_bar_table tr:nth-last-child(2)').after(newRow);

            }

            //Update the totals
            $("#table_basket_quantity").html(data['basket_quantity']);
            $("#table_basket_price").html(data['basket_value']);

            var basket_qty_items_text ="";

            if(data['basket_quantity'] > 1) {
                basket_qty_items_text = "items";
            } else {
                basket_qty_items_text = "item";
            }

            //Update the popup header
            $("#popup_basket_quantity").html(data['basket_quantity']);
            basket_qty_items_text = basket_qty_items_text.substr(0,1).toUpperCase()+basket_qty_items_text.substr(1);
            $("#popup_basket_quantity_text").html(basket_qty_items_text);
            
            //Update the button  
            var button = "<strong id=\"button_basket_quantity\">"+ data['basket_quantity'] +"</strong> "+ basket_qty_items_text +", <strong id=\"button_basket_price\">"+ data['basket_value'] +"</strong>";
            $("#basket_button_wrapper").html(button);

            showShoppingCartDuration();

        } else {
            //Shopping basket does not exist so add it.
            createJSShoppingCart(data);
        }   

        //Update the mobile cart display
        $("#mobile_button_basket_quantity").html(data['basket_quantity']);
        $("#mobile_button_basket_price").html(data['basket_value']);
        $("#mobile_header_basket_label").html(data['basket_quantity']);
        showMobileBasketDuration();
        $(window).scrollTop(0);

    } 
}

function createJSShoppingCart(data) {

    //Create the header
    var shopping_bar_header = "<h5 class=\"text-center\">Your Basket Has <span id=\"popup_basket_quantity\">"+ data['basket_quantity'] +"</span> <span id=\"popup_basket_quantity_text\">Item</h5>";
    //Overwrite the content with the header
    $("#drop").html(shopping_bar_header);

    //Create the table & table header
    var table_content = "<table id=\"basket_bar_table\"><thead><tr><th>Item name</th><th>Qty</th><th>Price</th></tr></thead></table>";
    $("#drop").append(table_content);

    //Create the first row 
    var newRow = "<tr id=\"product_"+data['product_id']+ "\"><td>"+ data['product_name'] + "</td><td class=\"text-center\">"+ data['product_quantity'] +"</td><td class=\"text-center\">£"+ data['product_price'] +"</td></tr>";
    $("#basket_bar_table thead").after(newRow);

    //Create the table footer
    var table_footer = "<tr id=\"basket_total_row\"><th class=\"text-right\">Total</th><th class=\"text-center\" id=\"table_basket_quantity\">" + data['basket_quantity'] + "</th><th id=\"table_basket_price\">" + data['basket_value'] + "</th></tr>";
    $("#basket_bar_table").append(table_footer);
    
   //Create the checkout button
    var checkout_button = "<a class=\"button small large-12\" href=\"" + globalBaseUrl + "basket/checkout/start\">Checkout</a>";
    $("#drop").append(checkout_button);


    //Create the wishlist and empty button
    var wishlist_button = "<a href=\"" + globalBaseUrl + "basket/save-to-wish-list\" class=\"button secondary tiny large-6 columns text-center\" id=\"add_basket_to_wish_list\">Add to wish list</a>";
    wishlist_button = wishlist_button + "<a href=\"" + globalBaseUrl + "basket/empty\" class=\"button secondary tiny large-6 columns text-center\" id=\"empty_basket\">Empty basket</a>";
    $("#drop").append(wishlist_button);

    addBasketToWishList();
    emptyBasket();

    var basket_qty_items_text ="";

        if(data['basket_quantity'] > 1) {
            basket_qty_items_text = "items";
        } else {
            basket_qty_items_text = "item";
        }

    //Update the button  
    var button = "<strong id=\"button_basket_quantity\">"+ data['basket_quantity'] +"</strong> "+ basket_qty_items_text +", <strong id=\"button_basket_price\">"+ data['basket_value'] +"</strong>";
    $("#basket_button_wrapper").html(button);

    //Update button in the basket bar to link to chackout
    $("#empty_basket_bar_button").attr('href', globalBaseUrl + 'basket/checkout/start');

    showShoppingCartDuration();
}

function showShoppingCartDuration() {
    //$(".f-dropdown.content").fadeOut(200);
    Foundation.libs.dropdown.open($('.f-dropdown.content'), $('.dropdown-btn'));

    greyOut();

    var basketTimeout = window.setTimeout(hideBasket, 2000);
    $("#basket_contents_popup_wrapper").hover(function() {
        clearTimeout(basketTimeout);
    });
}


function hideBasket() {
  Foundation.libs.dropdown.close($('.f-dropdown.content'), $('.dropdown-btn'));
  greyIn();
}

function greyOut(){
   
    $('#grey_out').css({ opacity: 0.7, 'width':$(document).width(),'height':$(document).height()}).fadeIn(200);
    
    
}

function greyIn(){
   
    $('#grey_out').fadeOut(200);
   
    
}








//JS for toggle business customer fields on registration
function businessCustomerToggle() {
    
   
    $("#business_customer_toggle").change(function() {

        if ($('#business_customer_toggle').is(":checked")) {
            // it is checked
            $(".personal_customer_form").hide();
            $(".business_customer_representative_form").show();

            //Move over the data (form the customer form to the business one)
            

        } else {
            // it is not checked
            $(".personal_customer_form").show();
            $(".business_customer_representative_form").hide();

            //Move over the data (form the business form to the customer one)
        }
        

    });
}

//Functions for view ort options
function viewPortOptions(urlToCall) {
    formSubmission();
    changeViewPortOptions();
}

function formSubmission() {

    $("#attribute_filter").submit(function(e){
  
        e.preventDefault(); //prevent submit

        reloadViewPort();
     
    });
}

        
function reloadViewPort() {
    
    hideMobileFilters();

    atts = $("#attribute_filter").serialize();
    
    urlPath = urlToCall;

    if(atts != null) { 
        
        urlPath += "?"+atts;
    } else {
        
    }

    //var atts['specific_category'] = "{{ category.categoryslug }}";
    
     //Call the serverr code and return the formatted table from in html
        $.ajax({
            type: "POST",
            url: urlPath,
            dataType: 'html',
            data: $("#attribute_filter").serialize(),
            beforeSend: function() {
                $('#loader').show();
                $('#display_area').animate({
                    opacity: 0.25,
                  }, 50);
            },
            complete: function(){
                $('#loader').hide();
                $('#display_area').animate({
                    opacity: 1,
                  }, 50);
            },
            success: function(data){
                $("#product_display_area_wrapper").html(data);
             
                //Create URL path 
                myvar = getURLParameter('query');
                
                //Need to check if there is already a '?' in the URL - this needs to be removed.
                var currentPath = window.location.href;

                if(currentPath.includes("?")) {
                    //remove '?' and everything afetr it.
                    var cut = currentPath.indexOf("?");
                    var currentPath = currentPath.substr(0, cut);
                
                    if(myvar != false || myvar != "") {
                        
                        var urlPath = currentPath +"?query="+myvar+"&" + $.trim(decodeURIComponent($("#attribute_filter").serialize()));
                    } else {

                        
                        var urlPath = currentPath +"?" + $.trim(decodeURIComponent($("#attribute_filter").serialize()));
                    }
                } else {
                    var urlPath = currentPath;
                }
                window.history.pushState({"html":'html',"pageTitle":'title'},"", urlPath);
                
                if (Foundation.utils.is_large_up()) {
                } else {
                    makeGridview();
                }
            }, 

            fail: function() {
                alert('failed');
            }
        });
    

}

//Regex function for creating the URL when using the product filter
function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [null, ''])[1].replace(/\+/g, '%20')) || null;
}

//JS for changing the view port options and updating the display
function changeViewPortOptions() {

    //Set up success variable
    var success = false;
    
    //Catch the perpage being changed
    $("#view_port_products_per_page").change(function(e) {
        success = updateViewPortPerPageSession($("#view_port_products_per_page").val());
        e.preventDefault(); 
    });

    //Catch the sort by being changed
    $("#view_port_sort_by").change(function(e) {
        success = updateViewPortOrderBySession($("#view_port_sort_by").val());
        e.preventDefault(); 
    }); 
}

//JS for changing the view port per page session
function updateViewPortPerPageSession(perPage) {
    
    //Set success variable to false
    var success = false;
   
    //Make the AJAX call to update on the server
    $.ajax({
        type: "POST",
        url: globalBaseUrl + 'settings/per-page/' + perPage,
        dataType: "json",
        data: {},
        success: function(data){
            //Return success true
            reloadViewPort();
            success = true; 
        },
        fail: function() {
            //Return success false
            success = false;
        }
    });
  
    //Return success variable
    return success;
}

//JS for changing the view port per page session
function updateViewPortOrderBySession(orderBy) {
    
    //Catch the viewport options link being clicked 
    var success = false;
    
    //Make the AJAX call to update on the server
    $.ajax({
        type: "POST",
        url: globalBaseUrl + 'settings/order-by/' + orderBy,
        dataType: "json",
        data: {},
        success: function(data){
            //Return success true
            reloadViewPort();
            success = true; 
        },
        fail: function() {
            //Return success false
            success = false;
        }
    });
  
    //Return success variable
    return success;
}

//Toggle topbar menu
$('.menuTog').click(function(evt) {
    $('.toggle-topbar').click();
});

function showMobileBasket() {
    //If basket is not showing, show it
    $('#mobile_basket_outer_wrapper').slideDown('fast');
    //Update the top position of the search bar 
    $('#mobile_search_wrapper').animate({'margin-top': '135px',}, 200); 
}

function hideMobileBasket() {
    $('#mobile_basket_outer_wrapper').slideUp('fast');
    //Update the top position of the search bar 
    $('#mobile_search_wrapper').animate({'margin-top': '70px',}, 200);
}

function mobileBasketToggle() {
    if($('#mobile_basket_outer_wrapper').css('display') != 'none') {
        hideMobileBasket();
    } else if($('#mobile_basket_outer_wrapper').css('display') == 'none') {
        showMobileBasket();
    }
}

function showMobileBasketDuration() {
    showMobileBasket();
    window.setTimeout(hideMobileBasket, 2000);
}

function showMobileFilters() {
    //If basket is showing, hide it
    if($('#product_filters').css('display') != 'none') {

        //Hide the filters
        $('#product_filters').slideUp('fast');

        //Change text to show filters
        $('#show_filters_button').html('Show Filters');
        
    } else if($('#product_filters').css('display') == 'none') {
        
        //If basket is not showing, show it
        //First, make it hidden
        $('#product_filters').css('display', 'none');
        
        //Then remove the show for large up class
        $('#product_filters').removeClass('show-for-large-up');

        //Then make it display
        $('#product_filters').slideDown('fast');

        //Change text to hide filters
        $('#show_filters_button').html('Hide Filters');
        
    }
}

//Function to hide the mobile filters after the filter has been applied
function hideMobileFilters() {
    $('#product_filters').addClass('show-for-large-up');
}

//Function to show the extra attributes hidden on load
function loadMoreAttributeFeatures(key) {
    $( "#" + key + "_div" ).toggle( "fast", function() {
        //Toggle complete, hide the show more link
        $( "#" + key + "_link" ).hide();
    });    
}

function gridviewToggle() {
    $("a.switcher").bind("click", function(e){
        e.preventDefault();
        
        var theid = $(this).attr("id");
        var theproducts = $("ul#display_area");
        var classNames = $(this).attr('class').split(' ');
        
        if($(this).hasClass("active-button")) {
            // if currently clicked button has the active-button class
            // then we do nothing!
            return false;
        } else {
            // otherwise we are clicking on the inactive button
            // and in the process of switching views!

            //CODE TO MAKE GRIDVIEW
            if(theid == "gridview") {
                
                //Call the make gridview function
                makeGridview();

                //Ajax to update view select session
                $.ajax({
                    type: "POST",
                    url: globalBaseUrl + "settings/view_type/gridview",
                    dataType: "json",
                    data: {},
                    success: function(data){
                        success = true;
                    },
                    fail: function() {
                        success = false;   
                    }
                });  
            
            //CODE TO MAKE LISTVIEW
            } else if(theid == "listview") {
               
                //Call the make listview function
                makeListview();

                //Ajax to update view select session
                $.ajax({
                    type: "POST",
                    url: globalBaseUrl + "settings/view_type/listview",
                    dataType: "json",
                    data: {},
                    success: function(data){ 
                        success = true;
                    },
                    fail: function() {
                        success = false;  
                    }
                });
            }
        }
    });
}

function makeGridview() {
    var theproducts = $("ul#display_area");

    $(this).addClass("active-button");
    $("#listview").removeClass("active-button");

    // remove the list class and change to grid
    theproducts.removeClass("list");
    theproducts.addClass("grid");
    theproducts.addClass("row");

    //Add the divs to split the content (to use Foundation)
   // $("div#products > div").removeClass("clearfix");
    $("ul#display_area > li").addClass("small-6 medium-3 columns left");
    
    $(".prod_disp_wrapper").removeClass("search_result_div");
    $(".prod_disp_wrapper").addClass("thumbnail");

    $(".sub_name").addClass("thumbnail_product_sub_name");

    $(".row_wrapper").removeClass("row");

    $(".prod_left").removeClass("large-2");
    $(".prod_left").removeClass("columns");

    $(".prod_center").removeClass("large-8");
    $(".prod_center").removeClass("columns");
    $(".prod_center").addClass("text-center");

    $(".prod_right").removeClass("large-2");
    $(".prod_right").removeClass("columns");
    $(".product_thumbnail_image").removeClass("search_results_thumb");
    $(".product_description").addClass("hidden");
    $(".more-info").addClass("hidden");
    //$(".shop_price").removeClass("search_results_price");
    $(".shop_price").addClass("thumbnail_price");
}

function makeListview() {
    var theproducts = $("ul#display_area");
    //Change the state of the button
    $(this).addClass("active-button");
    $("#gridview").removeClass("active-button");
        
    //?
    $("#gridview").children("img").attr("src","images/grid-view.png");
    //?    
    var theimg = $(this).children("img");
    theimg.attr("src","images/list-view-active.png");
        
    // remove the grid view and change to list
    theproducts.removeClass("grid")
    theproducts.addClass("list");
    theproducts.removeClass("row");

    //Add the divs to split the content (to use Foundation)
    $("ul#display_area > li").addClass("clearfix");
    $("ul#display_area > li").removeClass("small-6 medium-3 columns left");
    

    $(".prod_disp_wrapper").addClass("search_result_div");
    $(".prod_disp_wrapper").removeClass("thumbnail");

    $(".row_wrapper").addClass("row");
    $(".sub_name").removeClass("thumbnail_product_sub_name");
    

    $(".prod_left").addClass("large-2");
    $(".prod_left").addClass("columns");

    $(".prod_center").addClass("large-8");
    $(".prod_center").addClass("columns");
    $(".prod_center").removeClass("text-center");

    $(".prod_right").addClass("large-2");
    $(".prod_right").addClass("columns");
    $(".product_thumbnail_image").addClass("search_results_thumb");
    $(".product_description").removeClass("hidden");
    $(".more-info").removeClass("hidden");
    $(".shop_price").addClass("search_results_price");
    $(".shop_price").removeClass("thumbnail_price");
}
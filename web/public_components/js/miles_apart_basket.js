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
addLoadEvent(checkDefaultAddresses);

/*
 * Function to handle shipping existing page
 */
 $("#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing").change(function() {
    if ($('#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing').is(":checked")) {
        // it is checked
        $("#billing_address_hidden").hide();
        //Move over the data (form the customer form to the business one)
    } else {
        // it is not checked
        $("#billing_address_hidden").show();
        //Move over the data (form the business form to the customer one)
    }
});

$(".add_new_address_link").click(function() {
    toggleNewDeliveryAddressDisplay();
    return false;
});

$(".add_new_billing_address_link").click(function() {
    toggleNewBillingAddressDisplay();
    
    return false;
});

//Function to toggle new delivery address form 
function toggleNewDeliveryAddressDisplay() {

    //If new address form is currently hidden, show it
    if ($('#new_delivery_address').css("display") == "none") {
        $("#new_delivery_address").show();
        $("#existing_addresses").hide();

        //Create the toggle link
        var link = "Please enter your address in the form below or <a title=\"Toggle\" href=\"javascript:;\" onclick=\"toggleNewDeliveryAddressDisplay()\">select an existing address.<a>";
        $("#delivery_instruction_text").html(link);

        //Set the hidden form input value so address is cleared
        $('#milesapart_basketbundle_checkoutdelivery_delivery_address_id').val(null);

        //If any addresses are selected, deselect them 
        $( ".delivery_address_select" ).each(function() {
            if($( this ).hasClass( "success" )) {
                $( this ).removeClass( "success" );
                $(this).text("Select address");
            }
        });
    //If the form is currently showing, hide it
    } else {
        $("#new_delivery_address").hide();
        $("#existing_addresses").show();

        //Create the toggle link
        var link = "Please select a delivery address from below or <a title=\"Toggle\" href=\"javascript:;\" onclick=\"toggleNewDeliveryAddressDisplay()\">add a new address.<a>";
        $("#delivery_instruction_text").html(link);
    }

    return false;   
}

//Function to toggle new billing address form
function toggleNewBillingAddressDisplay() {
    
    //If new address form is currently hidden, show it
    if ($('#new_billing_address').css("display") == "none") {
        $("#new_billing_address").show();
        $("#existing_billing_addresses").hide();

        //Create the toggle link
        var link = "Please enter your billing address in the form below or <a title=\"Toggle\" href=\"javascript:;\" onclick=\"toggleNewBillingAddressDisplay()\">select an existing address.<a>";
        $("#billing_instruction_text").html(link);

        //Set the hidden form input value so address is cleared
        $('#milesapart_basketbundle_checkoutdelivery_billing_address_id').val(null);
        
        //If any addresses are selected, deselect them 
        $( ".billing_address_select" ).each(function() {
            if($( this ).hasClass( "success" )) {
                $( this ).removeClass( "success" );
                $(this).text("Select address");
            }
        });

    //If the form is currently showing, hide it
    } else {
        $("#new_billing_address").hide();
        $("#existing_billing_addresses").show();

        //Create the toggle link
        var link = "Please select a billing address from below or <a title=\"Toggle\" href=\"javascript:;\" onclick=\"toggleNewBillingAddressDisplay()\">add a new address.<a>";
        $("#billing_instruction_text").html(link);

    }

    return false;      
}

//Function to select the delivery address when clicked
function selectDeliveryAddress(addressId) {
    //Check for delivery&billing address set
    if($(".delivery_and_billing_address_select").length > 0) {

        $(".delivery_and_billing_address_select").each(function() {
            //Get the link id
            if($( this ).hasClass( "success" )) {
                $( this ).removeClass( "success" );
                $( this ).removeClass( "delivery_and_billing_address_select" );
                $(this).text("Select address");
                var oldId = $(this).attr("id").substr(22,3);
            
                //Update the Billing address to show this one 
                $("#billing_address_hidden").show();
                $("#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing").prop('checked', false);

                //Set this as the billing address
                selectBillingAddress(oldId);
            }
        });
    } else {
        //Have to check that no other addresses are selected
        $( ".delivery_address_select" ).each(function() {
            if($( this ).hasClass( "success" )) {
                $( this ).removeClass( "success" );
                $(this).text("Select address");
            }
        });
    }

    //Update the button
    var link = "#delivery_address_link_"+addressId;
    $(link).addClass("success");
    $(link).text("Delivery address");

    //Set the hidden form input value so address will be carried to db
    $('#milesapart_basketbundle_checkoutdelivery_delivery_address_id').val(addressId);
}

//Function to select the billing address when clicked
function selectBillingAddress(addressId) {
    //Check for delivery&billing address set
    $(".delivery_and_billing_address_select").each(function() {
        //Get the link id
        if($( this ).hasClass( "success" )) {
           $( this ).removeClass( "delivery_and_billing_address_select" );
            $(this).text("Delivery address");
        }
        //Update the Billing address to show this one 
        //Set this as the billing address

    });

    //Have to check that no other addresses are selected
    $( ".billing_address_select" ).each(function() {
        if($( this ).hasClass( "success" )) {
            $( this ).removeClass( "success" );
            $(this).text("Select address");
        }
    });

    //Update the button
    var link = "#billing_address_link_"+addressId;
    $(link).addClass("success");
    $(link).text("Billing address");

    //Set the hidden form input value so address will be carried to db
    $('#milesapart_basketbundle_checkoutdelivery_billing_address_id').val(addressId);
}

//Need to send the selected address through to the payment page (?)


//Script to update the order table shipping cahrages and grand total
$("input[name='milesapart_basketbundle_checkoutdelivery[delivery_option]']").change(function() {

    //Get the shipping cost
    var shippingCost = $("label[for='"+$(this).attr("id")+"']").text()
    
    //Insert the shipping cost into the table
    $("#shipping_charges_table_row :nth-child(3)").text(shippingCost);
    
    //Insert the grand total into the table
    var total =  $.trim($("#subtotal").text());
    total = total.substring(1);
    
    if(shippingCost == "Free") {
        shipping = 0;
    } else {
        shipping = shippingCost.substring(1);
    }

    var grandTotal = parseFloat(shipping) + parseFloat(total);

    //Show the shipping cost and grand total rows
    $("#grand_total_table_row :nth-child(3)").text("£"+grandTotal.toFixed(2));
    $(".hidden_table_row").css('display', 'table-row');
});

/*
 * Function to handle shipping new page
 */
$("#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing").change(function() {

    if ($('#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing').is(":checked")) {
        // it is checked
        $("#billing_address_hidden").hide();
    
        //Move over the data (form the customer form to the business one)
    
    } else {
        // it is not checked
        $("#billing_address_hidden").show();
    
        //Move over the data (form the business form to the customer one)
    }
});

//Script to update the order table shipping cahrages and grand total
$("input[name='milesapart_basketbundle_checkoutdelivery[delivery_option]']").change(function() {

        //Get the shipping cost
        var shippingCost = $("label[for='"+$(this).attr("id")+"']").text()
        

        //Insert the shipping cost into the table
        $("#shipping_charges_table_row :nth-child(3)").text(shippingCost);
        
        //Insert the grand total into the table
        var total =  $.trim($("#subtotal").text());
        total = total.substring(1);
        
        if(shippingCost == "Free") {
            shipping = 0;
        } else {
            shipping = shippingCost.substring(1);
        }

        var grandTotal = parseFloat(shipping) + parseFloat(total);

        var grandTotal = Math.round((grandTotal + 0.00001) * 100) / 100
        
        //Show the shipping cost and grand total rows
        $("#grand_total_table_row :nth-child(3)").text("£"+grandTotal);
        $(".hidden_table_row").css('display', 'table-row');
});

 /*
 * Shared functions         
 */
//Start of function to make "Your Order" sticky

//Function to make the "Your Order" div sticky
var StickyElement = function(node){

    var element = document.getElementById('sticky')
    var width = element.offsetWidth;
    var position = element.getBoundingClientRect();

    var doc = $(document), 
    fixed = false,
    anchor = node.find('.sticky-anchor'),
    content = node.find('.sticky-content');
  
    var onScroll = function(e){
    var docTop = doc.scrollTop(),
    anchorTop = anchor.offset().top;
    
    if(docTop > anchorTop){
      if(!fixed){
        anchor.height(content.outerHeight());
        content.addClass('fixed');        
        fixed = true;
        $(".fixed").css("width", width+"px");
        $(".fixed").css({left: position.x});
      }
    }  else   {
      if(fixed){
        anchor.height(0);
        content.removeClass('fixed'); 
        fixed = false;
      }
    }
  };
  
  $(window).on('scroll', onScroll);
};


//Call the get sticky function
var demo = new StickyElement($('#sticky'));

//Update the position if the viewport
$(window).resize(function () {
    delete demo;
    var demo = new StickyElement($('#sticky'));
});

//Called on existing customer address select checkout
function checkDefaultAddresses() {
    var defaultBillingAddressId = false;
    var defaultDeliveryAddressId = false;
    
    //Check if defaults are set
    if($(".default_delivery_address").length > 0) {
        $(".default_delivery_address").each(function(index) {

            defaultDeliveryAddressId = $( this ).attr('id').substr(17,3);
        });
    }

    if($(".default_billing_address").length > 0) {
        $(".default_billing_address").each(function(index) {

            defaultBillingAddressId = $( this ).attr('id').substr(16,3);
        });
    }

    //If no defaults are set, set up page and leave the script 
    if(!defaultDeliveryAddressId && !defaultBillingAddressId) {
        $("#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing").prop('checked', true);
        $("#billing_address_hidden").hide();
        return;
    }

    //Check if they match
    if(defaultDeliveryAddressId == defaultBillingAddressId) {
        //Hide the billing address area dn tick the xhecck box to say they are the same
        alert(defaultDeliveryAddressId+defaultBillingAddressId+"match");
        $( "#delivery_address_link_"+ defaultDeliveryAddressId).addClass("success");
         $( "#delivery_address_link_"+ defaultDeliveryAddressId).addClass("delivery_and_billing_address_select");
        $( "#delivery_address_link_"+ defaultDeliveryAddressId).text("Delivery & Billing address");


        //Tick the billing is same as delivery address tick box
        $("#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing").prop('checked', true);
        //Hide the billing address area
        $("#billing_address_hidden").hide();

        //Set the hidden form input value so address will be carried to db
        $('#milesapart_basketbundle_checkoutdelivery_delivery_address_id').val(defaultDeliveryAddressId);
        $('#milesapart_basketbundle_checkoutdelivery_billing_address_id').val(defaultBillingAddressId);

    //They don't match
    } else {

        //If defaultDeliveryId is set, show the selected
        if(defaultDeliveryAddressId) {
            selectDeliveryAddress(defaultDeliveryAddressId);
            $('#milesapart_basketbundle_checkoutdelivery_delivery_address_id').val(defaultDeliveryAddressId);
        }

        //If billing address is set, show the selected
        if(defaultBillingAddressId) {
            selectBillingAddress(defaultBillingAddressId);
            $('#milesapart_basketbundle_checkoutdelivery_billing_address_id').val(defaultBillingAddressId);
        } else {
            $("#milesapart_basketbundle_checkoutdelivery_delivery_address_customer_address_is_billing").prop('checked', true);
            $("#billing_address_hidden").hide();
        }
    }
}
function errorAlert(){
 
var userAgentStr = navigator.userAgent;
 
if (userAgentStr.indexOf("STBrowser") > -1)
  {
    //error alert sound
    window.location = "swipetrack://sound?name=error";
 
    //vibrate device once
    window.location = "swipetrack://vibrate?count=1";
  }
 
return true;
}

function onScanAppBarCodeData(bar)
{
//'bar' is the retrieved Barcode
//The below function will set the form field
//named 'barcode' to the value of 'bar'
document.getElementById('form_product_barcode').value = bar;
  
//You can add additional functionality like submitting the form
$( "form:first" ).submit();
  

  
return true;
}
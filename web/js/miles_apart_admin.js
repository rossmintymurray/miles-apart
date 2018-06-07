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
addLoadEvent(initialiseTooltips);
addLoadEvent(initialiseFormCollections);
addLoadEvent(initialiseFormFocus);
//addLoadEvent(newCategoryChainedSelect);
 /*******************************************
 *
 * Create add form functions
 *
 *******************************************/ 
function initialiseFormCollections() {
    $('.btn-add').click(function(event) {
        var collectionHolder = $('#' + $(this).attr('data-target'));
        var prototype = collectionHolder.attr('data-prototype');
        var form = prototype.replace(/__name__/g, collectionHolder.children().length);

        collectionHolder.append(form);

        return false;
    });
    $(document).on("click", ".btn-remove", function(event) {
        var name = $(this).attr('data-related');

        $('*[data-content="'+name+'"]').remove();

        //return false;
    });
}
 

function initialiseFormFocus() {
     $(function(){
        $(document).on('focus','input[type=text]',function(){ this.select(); });
    });
}



//Initialse tooltips
function initialiseTooltips() {
    $('.sitewide-tooltip').tooltip({placement : 'right'});
}


 /*******************************************
 *
 * Create the function for Dymo label printing 
 *
 *******************************************/
 // called when the document completly loaded
function printAdd()
    {
    	//Set each field
    	var printName = document.getElementById('print_name').innerHTML;
    	var printAddress1 = document.getElementById('print_address_1').innerHTML;
        if (document.getElementById('print_address_2')) {
        	var printAddress2 = document.getElementById('print_address_2').innerHTML;
        } else {
        	var printAddress2 = null;
        }
        var printTown = document.getElementById('print_town').innerHTML;
        var printCounty = document.getElementById('print_county').innerHTML;
        var printPostcode = document.getElementById('print_postcode').innerHTML;
        var printCountry = document.getElementById('print_country').innerHTML;
        var printButton = document.getElementById('printButton');

        //Colate fields to address for printing
		var addressText = printName+'\n'+printAddress1+'\n';

		//Add second line of address if it exists.
		if (printAddress2 != null) {
			addressText = addressText + printAddress2+'\n'
		}

		//Add address fields
		addressText = addressText +printTown+'\n'+printCounty+'\n'+printPostcode+'\n';

		//If country is not UK add country.
		if (printCountry != "UK" || (printCountry != "UK" && printCountry != null)) {
			addressText = addressText + printCountry;
		}

		
                
        // prints the label
     
        try {
		    // open label
		    var labelXml = '<?xml version="1.0" encoding="utf-8"?>\
		    <DieCutLabel Version="8.0" Units="twips">\
		        <PaperOrientation>Landscape</PaperOrientation>\
		        <Id>Address</Id>\
		        <PaperName>99012 Large Address</PaperName>\
		        <DrawCommands/>\
		        <ObjectInfo>\
		            <TextObject>\
		                <Name>Text</Name>\
		                <ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
		                <BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
		                <LinkedObjectName></LinkedObjectName>\
		                <Rotation>Rotation0</Rotation>\
		                <IsMirrored>False</IsMirrored>\
		                <IsVariable>True</IsVariable>\
		                <HorizontalAlignment>Left</HorizontalAlignment>\
		                <VerticalAlignment>Middle</VerticalAlignment>\
		                <TextFitMode>ShrinkToFit</TextFitMode>\
		                <UseFullFontHeight>True</UseFullFontHeight>\
		                <Verticalized>False</Verticalized>\
		                <StyledText/>\
		            </TextObject>\
		            <Bounds X="332" Y="50" Width="4455" Height="2260" />\
		        </ObjectInfo>\
		    </DieCutLabel>';

		    //Create the label object
            var label = dymo.label.framework.openLabelXml(labelXml);

            // set label text
            label.setObjectText("Text", addressText);
            
            // select printer to print on
            // for simplicity sake just use the first LabelWriter printer
            var printers = dymo.label.framework.getPrinters();
            if (printers.length == 0)
                throw "No DYMO printers are installed. Install DYMO printers.";

            var printerName = "";
            for (var i = 0; i < printers.length; ++i)
            {
                var printer = printers[i];
                if (printer.printerType == "LabelWriterPrinter")
                {
                    printerName = printer.name;
                    break;
                }
            }
            
            if (printerName == "")
                throw "No LabelWriter printers found. Install LabelWriter printer";

            // finally print the label
            label.print(printerName);
        }
        catch(e)
        {
            alert(e.message || e);
        }
        
    
};
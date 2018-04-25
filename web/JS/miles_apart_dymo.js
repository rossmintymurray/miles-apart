/*******************************************
*
* Create the functions for Dymo label printing 
*
*******************************************/

/*******************************************
* This is the function for printing SUPPLIER ADDRESS
*******************************************/
function printAdd(supplier_id) {
	dymo.label.framework.init(printAddPrint(supplier_id));
}

function printAddPrint(supplier_id)
{
	//Make ajax call to get the supplier details for printing
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "get-supplier-address",
		dataType: 'json',
		data: { supplier_id : supplier_id  },
	  
	  	success: function(data){
			//Set the address data up each field at a time
			var printName = data[0][0].supplier_name;
			var printAddress1 = data[0][0].supplier_address_1;

			//Add second line of address only if it exists
			if (data[0][0].supplier_address_2) {
				var printAddress2 = data[0][0].supplier_address_2;
			} else {
				var printAddress2 = null;
			}

			//Add the remaining address lines
			var printTown = data[0][0].supplier_town;
			var printCounty = data[0][0].supplier_county;
			var printPostcode = data[0][0].supplier_postcode;
			var printCountry = data[0][0].supplier_country;

			//Colate fields to address for printing
			var addressText = printName+'\n'+printAddress1+'\n';

			//Add second line of address if it exists.
			if (printAddress2 != null) {
				addressText = addressText + printAddress2+'\n'
			}

			//Add rest of the address fields
			addressText = addressText +printTown+'\n'+printCounty+'\n'+printPostcode+'\n';

			//If country is not UK, add country.
			if (printCountry != "UK" || (printCountry != "UK" && printCountry != null)) {
			 	addressText = addressText + printCountry;
			}
							  
			//Set the Dymo template for the label
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
				if (printers.length == 0) {
					throw "No DYMO printers are installed. Install DYMO printers.";
					var printerName = "";
				}

				for (var i = 0; i < printers.length; ++i) {
				  	var printer = printers[i];
				  	if (printer.printerType == "LabelWriterPrinter") {
						printerName = printer.name;
						break;
				  	}
				}
					 
				if (printerName == "") {
					throw "No LabelWriter printers found. Install LabelWriter printer";
				}
				
				// finally print the label
				label.print(printerName);
			}
			catch(e) {
				alert(e.message || e);
			}
		}, 
		fail: function() {
		 	alert('failed');
		}
	});
}

/*******************************************
* This is the function for printing SEASONAL BOX BARCODES
*******************************************/
function printSeasonalBoxLabelShared(barcode) {
	dymo.label.framework.init(printSeasonalBoxLabelSharedPrint(barcode));
}

function printSeasonalBoxLabelSharedPrint(barcode) {
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
				<BarcodeObject>\
					<Name>Barcode</Name>\
					<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>False</IsVariable>\
					<Text>Barcode</Text>\
					<Type>Code39</Type>\
					<Size>Medium</Size>\
					<TextPosition>None</TextPosition>\
					<TextFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					<CheckSumFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					<TextEmbedding>None</TextEmbedding>\
					<ECLevel>0</ECLevel>\
					<HorizontalAlignment>Center</HorizontalAlignment>\
					<QuietZonesPadding Left="0" Top="0" Right="0" Bottom="0" />\
				</BarcodeObject>\
				<Bounds X="332" Y="70" Width="4455" Height="900" />\
			</ObjectInfo>\
			<ObjectInfo>\
				<TextObject>\
					<Name>Text</Name>\
					<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>True</IsVariable>\
					<HorizontalAlignment>Center</HorizontalAlignment>\
					<VerticalAlignment>Middle</VerticalAlignment>\
					<TextFitMode>AlwaysFit</TextFitMode>\
					<UseFullFontHeight>True</UseFullFontHeight>\
					<Verticalized>False</Verticalized>\
					<StyledText/>\
				</TextObject>\
				<Bounds X="332" Y="900" Width="4455" Height="1000" />\
			</ObjectInfo>\
		</DieCutLabel>';

		//Create the label object
		var label = dymo.label.framework.openLabelXml(labelXml);

		// set label text
		label.setObjectText("Barcode", barcode);
		label.setObjectText("Text", barcode);
					 
		// select printer to print on
		// for simplicity sake just use the first LabelWriter printer
		var printers = dymo.label.framework.getPrinters();
		if (printers.length == 0) {
		  	throw "No DYMO printers are installed. Install DYMO printers.";
			var printerName = "";
		}

		for (var i = 0; i < printers.length; ++i) {
		  	var printer = printers[i];
		  	
		  	if (printer.printerType == "LabelWriterPrinter") {
				if(printer.name == "DYMO - Westbury") {
					printerName = printer.name;
					break;
			 	}
		  	}
		}

		if (printerName == "") {
		  	throw "No LabelWriter printers found. Install LabelWriter printer";
		}
		
		// finally print the label
		label.print(printerName);
	}
	catch(e) {
		alert(e.message || e);
	}
}

/*******************************************
* This is the function for printing STOCK LOCATION SHELF BARCODES - LARGE
*******************************************/
function printStockLocationShelfBarcode(barcode) {
 	dymo.label.framework.init(printStockLocationShelfBarcodePrint(barcode));
}

function printStockLocationShelfBarcodePrint(barcode) {
	// prints the label
	try {
		// open label
		var labelXml = '<?xml version="1.0" encoding="utf-8"?>\
			<DieCutLabel Version="8.0" Units="twips">\
				<PaperOrientation>Landscape</PaperOrientation>\
				<Id>Address</Id>\
				<PaperName>99014 Shipping</PaperName>\
				<DrawCommands/>\
				<ObjectInfo>\
				  	<BarcodeObject>\
					 	<Name>Barcode</Name>\
					 	<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					 	<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					 	<LinkedObjectName></LinkedObjectName>\
					 	<Rotation>Rotation0</Rotation>\
					 	<IsMirrored>False</IsMirrored>\
					 	<IsVariable>False</IsVariable>\
					 	<Text>Barcode</Text>\
					 	<Type>Code39</Type>\
					 	<Size>Medium</Size>\
					 	<TextPosition>None</TextPosition>\
					 	<TextFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					 	<CheckSumFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					 	<TextEmbedding>None</TextEmbedding>\
					 	<ECLevel>0</ECLevel>\
					 	<HorizontalAlignment>Center</HorizontalAlignment>\
					 	<QuietZonesPadding Left="0" Top="0" Right="0" Bottom="0" />\
					</BarcodeObject>\
				  	<Bounds X="152" Y="70" Width="5455" Height="1900" />\
				</ObjectInfo>\
				<ObjectInfo>\
				  	<TextObject>\
						<Name>Text</Name>\
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
						<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
						<LinkedObjectName></LinkedObjectName>\
						<Rotation>Rotation0</Rotation>\
						<IsMirrored>False</IsMirrored>\
						<IsVariable>True</IsVariable>\
						<HorizontalAlignment>Center</HorizontalAlignment>\
						<VerticalAlignment>Middle</VerticalAlignment>\
						<TextFitMode>AlwaysFit</TextFitMode>\
						<UseFullFontHeight>True</UseFullFontHeight>\
						<Verticalized>False</Verticalized>\
						<StyledText/>\
				  	</TextObject>\
				  	<Bounds X="152" Y="1900" Width="5455" Height="1000" />\
				</ObjectInfo>\
			</DieCutLabel>';

		//Create the label object
		var label = dymo.label.framework.openLabelXml(labelXml);

		// set label text
		label.setObjectText("Barcode", barcode);
		label.setObjectText("Text", barcode);

		// select printer to print on
		// for simplicity sake just use the first LabelWriter printer
		var printers = dymo.label.framework.getPrinters();
		if (printers.length == 0) {
		  	throw "No DYMO printers are installed. Install DYMO printers.";
			var printerName = "";
		}

		for (var i = 0; i < printers.length; ++i) {
		  	var printer = printers[i];
		  	
		  	if (printer.printerType == "LabelWriterPrinter") {
				if(printer.name == "DYMO - Westbury") {
					printerName = printer.name;
					break;
			 	}
		  	}
		}

		if (printerName == "") {
		  	throw "No LabelWriter printers found. Install LabelWriter printer";
		}

		// finally print the label
		label.print(printerName);
	}
	catch(e) {
		alert(e.message || e);
	}
}

/*******************************************
* This is the function for printing STOCK LOCATION SHELF BARCODES - SMALL
*******************************************/
function printStockLocationShelfSmallBarcode(barcode) {
 	dymo.label.framework.init(printStockLocationShelfSmallBarcodePrint(barcode));
}

function printStockLocationShelfSmallBarcodePrint(barcode) {
	// prints the label
	try {
		// open label
		var labelXml = '<?xml version="1.0" encoding="utf-8"?>\
			<DieCutLabel Version="8.0" Units="twips">\
				<PaperOrientation>Landscape</PaperOrientation>\
				<Id>Address</Id>\
				<PaperName>99014 Shipping</PaperName>\
				<DrawCommands/>\
				<ObjectInfo>\
					<BarcodeObject>\
					 	<Name>Barcode</Name>\
					 	<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					 	<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					 	<LinkedObjectName></LinkedObjectName>\
					 	<Rotation>Rotation0</Rotation>\
					 	<IsMirrored>False</IsMirrored>\
					 	<IsVariable>False</IsVariable>\
					 	<Text>Barcode</Text>\
					 	<Type>Code39</Type>\
					 	<Size>Medium</Size>\
					 	<TextPosition>None</TextPosition>\
					 	<TextFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					 	<CheckSumFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					 	<TextEmbedding>None</TextEmbedding>\
					 	<ECLevel>0</ECLevel>\
					 	<HorizontalAlignment>Center</HorizontalAlignment>\
					 	<QuietZonesPadding Left="0" Top="0" Right="0" Bottom="0" />\
					</BarcodeObject>\
					<Bounds X="52" Y="70" Width="5455" Height="800" />\
				</ObjectInfo>\
				<ObjectInfo>\
					<TextObject>\
						<Name>Text</Name>\
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
						<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
						<LinkedObjectName></LinkedObjectName>\
						<Rotation>Rotation0</Rotation>\
						<IsMirrored>False</IsMirrored>\
						<IsVariable>True</IsVariable>\
						<HorizontalAlignment>Center</HorizontalAlignment>\
						<VerticalAlignment>Middle</VerticalAlignment>\
						<TextFitMode>AlwaysFit</TextFitMode>\
						<UseFullFontHeight>True</UseFullFontHeight>\
						<Verticalized>False</Verticalized>\
						<StyledText/>\
					</TextObject>\
					<Bounds X="152" Y="900" Width="5455" Height="800" />\
				</ObjectInfo>\
			</DieCutLabel>';

		//Create the label object
		var label = dymo.label.framework.openLabelXml(labelXml);

		// set label text
		label.setObjectText("Barcode", barcode);
		label.setObjectText("Text", barcode);

		// select printer to print on
		// for simplicity sake just use the first LabelWriter printer
		var printers = dymo.label.framework.getPrinters();
		if (printers.length == 0) {
			throw "No DYMO printers are installed. Install DYMO printers.";
			var printerName = "";
		}

		for (var i = 0; i < printers.length; ++i) {
			var printer = printers[i];
			if (printer.printerType == "LabelWriterPrinter") {
				printerName = printer.name;
				break;
			}
		}

		if (printerName == "") {
			throw "No LabelWriter printers found. Install LabelWriter printer";
		}

		// finally print the label
		label.print(printerName);
	}
	catch(e) {
		alert(e.message || e);
	}
}

/*******************************************
* This is the function for printing PRICE LABEL MEDIUM FULL
*******************************************/
function printPriceLabelMediumFull() {

	//Get all outstanding med prices to be printed from DB
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "get-outstanding-medium-price-label",
		dataType: 'json',
		data: {  },
		success: function(data){ 
			//Iterate over the returned products
			for(i=0;i < data['products'].length; i++) {
				//For each returned product, call printIndividualPriceLabelMediumFull passing the reqd. data.
				printIndividualPriceLabelMediumFull(data['products'][i]['product_name'], data['products'][i]['product_price'], data['products'][i]['product_supplier_code'], data['products'][i]['product_barcode'], data['products'][i]['supplier_name']);
			}
		}, 
		fail: function() {
		  	alert('failed');
		}
	});
}

//Ititialise the Dymo print for each price label medium
function printIndividualPriceLabelMediumFull(product_name, product_price, product_supplier_code, product_barcode, supplier_name) {
 	dymo.label.framework.init(printIndividualPriceLabelMediumFullPrint(product_name, product_price, product_supplier_code, product_barcode, supplier_name));
}

//Dymo print for each price label medium
function printIndividualPriceLabelMediumFullPrint(product_name, product_price, product_supplier_code, product_barcode, supplier_name) {
		
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
					<Name>Supplier</Name>\
					<ForeColor Alpha="255" Red="70" Green="70" Blue="70" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>True</IsVariable>\
					<HorizontalAlignment>Left</HorizontalAlignment>\
					<VerticalAlignment>Middle</VerticalAlignment>\
					<TextFitMode>AlwaysFit</TextFitMode>\
					<UseFullFontHeight>True</UseFullFontHeight>\
					<Verticalized>False</Verticalized>\
					<StyledText/>\
				</TextObject>\
				<Bounds X="300" Y="100" Width="5455" Height="280" />\
			</ObjectInfo>\
			<ObjectInfo>\
				<TextObject>\
					<Name>ProdName</Name>\
					<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>True</IsVariable>\
					<HorizontalAlignment>Left</HorizontalAlignment>\
					<VerticalAlignment>Middle</VerticalAlignment>\
					<TextFitMode>AlwaysFit</TextFitMode>\
					<UseFullFontHeight>True</UseFullFontHeight>\
					<Verticalized>False</Verticalized>\
					<StyledText/>\
				</TextObject>\
				<Bounds X="300" Y="300" Width="5455" Height="500" />\
			</ObjectInfo>\
			<ObjectInfo>\
				<BarcodeObject>\
					<Name>Barcode</Name>\
					<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>False</IsVariable>\
					<Text>Barcode</Text>\
					<Type>Ean13</Type>\
					<Size>Small</Size>\
					<TextPosition>None</TextPosition>\
					<TextFont Family="Arial" Size="4" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					<CheckSumFont Family="Arial" Size="4" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
					<TextEmbedding>None</TextEmbedding>\
					<ECLevel>0</ECLevel>\
					<HorizontalAlignment>Center</HorizontalAlignment>\
					<QuietZonesPadding Left="0" Top="0" Right="0" Bottom="0" />\
				</BarcodeObject>\
				<Bounds X="300" Y="1000" Width="2900" Height="450" />\
			</ObjectInfo>\
			<ObjectInfo>\
				<TextObject>\
					<Name>Price</Name>\
					<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>True</IsVariable>\
					<HorizontalAlignment>Center</HorizontalAlignment>\
					<VerticalAlignment>Middle</VerticalAlignment>\
					<TextFitMode>AlwaysFit</TextFitMode>\
					<UseFullFontHeight>True</UseFullFontHeight>\
					<Verticalized>False</Verticalized>\
					<StyledText/>\
				</TextObject>\
				<Bounds X="2052" Y="900" Width="3500" Height="800" />\
			</ObjectInfo>\
			<ObjectInfo>\
				<TextObject>\
					<Name>PriceEach</Name>\
					<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>True</IsVariable>\
					<HorizontalAlignment>Center</HorizontalAlignment>\
					<VerticalAlignment>Middle</VerticalAlignment>\
					<TextFitMode>AlwaysFit</TextFitMode>\
					<UseFullFontHeight>True</UseFullFontHeight>\
					<Verticalized>False</Verticalized>\
					<StyledText/>\
				</TextObject>\
				<Bounds X="2000" Y="3000" Width="500" Height="150" />\
			</ObjectInfo>\
			<ObjectInfo>\
				<TextObject>\
					<Name>SupplierCode</Name>\
					<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
					<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
					<LinkedObjectName></LinkedObjectName>\
					<Rotation>Rotation0</Rotation>\
					<IsMirrored>False</IsMirrored>\
					<IsVariable>True</IsVariable>\
					<HorizontalAlignment>Center</HorizontalAlignment>\
					<VerticalAlignment>Middle</VerticalAlignment>\
					<TextFitMode>AlwaysFit</TextFitMode>\
					<UseFullFontHeight>True</UseFullFontHeight>\
					<Verticalized>False</Verticalized>\
					<StyledText/>\
				</TextObject>\
				<Bounds X="300" Y="3000" Width="3500" Height="150" />\
			</ObjectInfo>\
		</DieCutLabel>';

		//Create the label object
		var label = dymo.label.framework.openLabelXml(labelXml);

		// set label text
		label.setObjectText("Supplier", supplier_name);
		label.setObjectText("Barcode", product_barcode);
		label.setObjectText("ProdName", product_name);
		label.setObjectText("Price", product_price);
		label.setObjectText("SupplierCode", product_supplier_code);
		label.setObjectText("PriceEach", "each");


		// select printer to print on
		// for simplicity sake just use the first LabelWriter printer
		var printers = dymo.label.framework.getPrinters();
		if (printers.length == 0) {
			throw "No DYMO printers are installed. Install DYMO printers.";
		}
		 
		var printerName = "";
		for (var i = 0; i < printers.length; ++i) {
		 
			var printer = printers[i];
			if (printer.printerType == "LabelWriterPrinter") {
				printerName = printer.name;
				break;
		  	}
		}
		 
		if (printerName == "") {
			throw "No LabelWriter printers found. Install LabelWriter printer";
		}
		// finally print the label
		label.print(printerName);
	}
	catch(e) {
		alert(e.message || e);
	}
}

/*******************************************
* This is the function for printing HOOK LABEL
*******************************************/
function printHookLabel() {
	//Get all outstanding hook labels to be printed
	$.ajax({
		type: "POST",
		url: globalBaseUrl + "get-outstanding-hook-label",
		dataType: 'json',
		data: {  },
		success: function(data){
			//Iterate over the returned products
			for(i=0;i < data['products'].length; i++) {
				//For each returned product, call printIndividualPriceLabelMediumFull passing the product_id.
				printIndividualHookLabel(data['products'][i]['product_name'], data['products'][i]['product_price'], data['products'][i]['product_supplier_code'], data['products'][i]['product_barcode'], data['products'][i]['supplier_name']);
			}
	  	}, 
		fail: function() {
		  	alert('failed');
		}
	});
}

//Ititialise the Dymo print for each hook label
function printIndividualHookLabel(product_name, product_price, product_supplier_code, product_barcode, supplier_name) {
 	dymo.label.framework.init(printIndividualHookLabelPrint(product_name, product_price, product_supplier_code, product_barcode, supplier_name));
}

//Dymo print for each hook label 
function printIndividualHookLabelPrint(product_name, product_price, product_supplier_code, product_barcode, supplier_name) {
	// prints the label
	try {
		//Create the label object
		//var label = dymo.label.framework.openLabelXml(labelXml);
		var label = dymo.label.framework.openLabelFile(globalBaseUrl + "web/labels/HookLabel.label");

		// set label text
		label.setObjectText("Supplier", supplier_name);
		label.setObjectText("Barcode", product_barcode);
		label.setObjectText("ProdName", product_name);
		label.setObjectText("Price", product_price);
		label.setObjectText("SupplierCode", product_supplier_code);

		// select printer to print on
		// for simplicity sake just use the first LabelWriter printer
		var printers = dymo.label.framework.getPrinters();
		if (printers.length == 0) {
			throw "No DYMO printers are installed. Install DYMO printers.";
			var printerName = "";
		}
		
		for (var i = 0; i < printers.length; ++i) {
			var printer = printers[i];
			if (printer.printerType == "LabelWriterPrinter") {
				if(printer.name == "DYMO - Shared") {
					printerName = printer.name;
					break;
				}
			}
		}

		if (printerName == "") {
			throw "No LabelWriter printers found. Install LabelWriter printer";
		}

		// finally print the label
		label.print(printerName);
	}
	catch(e) {
		alert(e.message || e);
	}		  
}
var itemData = '[{"price": "20", "amazonID": "B002BW7QCU", "name": "Air Mattresses"}, {"price": "20", "amazonID": "B00XCLFZLS", "name": "Baby Food"}, {"price": "10", "amazonID": "B001U2HRL", "name": "Baby Formula"}, {"price": "10", "amazonID": "B00FW6TNJQ", "name": "Baby Items"}, {"price": "30", "amazonID": "B008KJQMA0", "name": "Baby Wipes"}, {"price": "30", "amazonID": "B00NLLUMOE", "name": "Bed Linens"}, {"price": "20", "amazonID": "B00J7B8T5Q", "name": "Blankets"}, {"price": "30", "amazonID": "B01MYWUDP7", "name": "Canes"}, {"price": "10", "amazonID": "B07434ZKFM", "name": "Charging Cables"}, {"price": "10", "amazonID": "B00HSC9F2C", "name": "Cleaning Supplies"}, {"price": "20", "amazonID": "B00KBZOR9O", "name": "Clothing"}, {"price": "10", "amazonID": "B00TFXH3F8", "name": "Diapers"}, {"price": "20", "amazonID": "B01ADWNVGE", "name": "Food Items"}, {"price": "5", "amazonID": "B00I4R6STS", "name": "Gloves"}, {"price": "20", "amazonID": "B072FL4V84", "name": "Granola Bars"}, {"price": "20", "amazonID": "B000HVVCO0", "name": "Medical Supplies"}, {"price": "10", "amazonID": "B01CLHQZOY", "name": "Non-perishable Food"}, {"price": "30", "amazonID": "B00CFCSVQG", "name": "Packing Supplies"}, {"price": "10", "amazonID": "B019XEY5GS", "name": "Paper Towels"}, {"price": "10", "amazonID": "B00SV4T6O8", "name": "Pasta"}, {"price": "30", "amazonID": "B00BD76MRY", "name": "Pet Items"}, {"price": "10", "amazonID": "B06XKK1N41", "name": "Pillow Cases"}, {"price": "20", "amazonID": "B01E9IPB5C", "name": "Pillows"}, {"price": "10", "amazonID": "B01DN8TPG0", "name": "Sharpies"}, {"price": "10", "amazonID": "B001NKDAKS", "name": "Sheetrock Cutters"}, {"price": "30", "amazonID": "B073S7SZ1L", "name": "Shoes"}, {"price": "10", "amazonID": "B00E4I4XS4", "name": "Socks"}, {"price": "40", "amazonID": "B00C6OV63S", "name": "Suitcases"}, {"price": "20", "amazonID": "B01GTNVWYO", "name": "Toilet Paper"}, {"price": "20", "amazonID": "B00INVCHCC", "name": "Toiletries"}, {"price": "20", "amazonID": "B010S5VXKC", "name": "Towels"}, {"price": "10", "amazonID": "B00ASBOP9S", "name": "Trash Bags"}, {"price": "10", "amazonID": "B01MPYJK69", "name": "Underwear"}, {"price": "10", "amazonID": "B00LLKWVL4", "name": "Water"}, {"price": "100", "amazonID": "B0013Z1Z00", "name": "Wheelchairs"}]';

items = JSON.parse(itemData);

//setting shelter data by pulling the random shelter and its need from the API
function setShelter(data) {
	var primaryIndex = Math.floor(Math.random() * data.needs.length);
	var primaryName = data.needs[primaryIndex]
	data.needs.splice(primaryIndex);
	$("#shelterName").text(data.name);
	$("#shippingAddress").text("SHIP TO: " + data.address);

	// making the default need primaryName
	$("#firstprice").text(primaryName);

	setPrices(primaryName);

	// otherwise filling the dropdown with needs
	data.needs.forEach(function(shelterNeed) {
		$("#needlist").append('<li><a class="needItem" tabindex="-1">' + shelterNeed + '</a></li>');
	});
}

function setPrice(item) {
	items.forEach(function(element) {
		if(item === element["name"]) {
			console.log(item);
		}
	});
}

function addToCart() {

	//getting the price
	var price = "";
	$(".price").each(function() {
		if ($(this).hasClass("active")) {
			price = $(this).text();
		}	
	});
		
	price = parseInt(price.substr(1));	
	console.log("price=" + price);

	//getting the item that's active in the dropdown
	var selectedItem = $(".defaultprice").text();
	console.log("item=" + selectedItem);
	var selectedAmazonId = "";
	var itemPrice;

	items.forEach(function(element) {
		if(selectedItem === element["name"]) {	
			console.log("Found item");
			selectedAmazonId = element["amazonID"];
			itemPrice = parseInt(element["price"]);
		}
	});	

	var quantity = parseInt(price / itemPrice);

	console.log(selectedAmazonId);
	console.log(quantity);

	//linking the selected item to an item in the item data

	$.getJSON("add.php", {"quantity":quantity, "amazonID":selectedAmazonId}, function(data) {
		$.ajax({
     			url: 'index.html',
     			data: {},
     			success: function(){
				window.open(data.url[0], "_blank");
     			},
     			async: false
    		});
	});
}

function setPrices(selectedItem) {
	//getting prices on item
	items.forEach(function(item) {
		if (item["name"] === selectedItem) {
			console.log(item);
			$("#mainPrice").text("$" + item["price"]);
			$("#doublePrice").text("$" + (2 * parseInt(item["price"])));
			$("#triplePrice").text("$" + (3 * parseInt(item["price"])));
		}
	});	
}

$('.price').click(function(){
	console.log("changing active");
	$('.price').removeClass('active');
	$(this).addClass('active');
});

$.getJSON("http://www.collegehaxcess.com/houstonian/api.php", function (data) {
	setShelter(data);
});

$(document).on("click", ".needItem", function() {
	//setting the default price to the name
	$(".defaultprice:first-child").text($(this).text());
	$(".defaultprice:first-child").val($(this).text());
	$("#firstprice").val($(this).text());
	setPrices($(this).text());

});

$("li").click(function() {
	console.log("here");
});

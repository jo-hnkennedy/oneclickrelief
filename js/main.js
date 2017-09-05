items = []

$.getJSON("https://api.harveyneeds.org/api/v1/products", function(data) {
	var blockedCategories = ["Books", "Appliances", "Gift Cards", "Electronics"];
	data.products.forEach(function(item) {
		if (! blockedCategories.includes(item.category_general)) {
			items.push(item);
		}
	});
	fillNeeds(items);
});

console.log(items);

/**
 * Shuffles array in place.
 * @param {Array} a items The array containing the items.
 */
function shuffle(a) {
    var j, x, i;
    for (i = a.length; i; i--) {
        j = Math.floor(Math.random() * i);
        x = a[i - 1];
        a[i - 1] = a[j];
        a[j] = x;
    }
	return a;
}

function getPrice(asin) {
	return parseInt($.ajax({ 
      		url: 'get_price.php', 
		data: {"ASIN": asin},	
      		async: false
   	}).responseText);
}

function fillNeeds(items) {

	var randomItem = items[Math.floor(Math.random()*items.length)];

	console.log("randomItem: ");
	setPrices(getPrice(randomItem.asin));

	$("#firstprice").text(randomItem.category_general);

	// otherwise filling the dropdown with needs
	console.log("filling dropdown");
	general_categories = [];
	items.forEach(function(item) {
		if (general_categories.includes(item.category_general)) {	
		}
		else {
			general_categories.push(item.category_general);
		}
	});
	general_categories.forEach(function(category) {
		$("#needlist").append('<li><a class="needItem dropdown-item" tabindex="-1">' + category + '</a></li>');
	});

	var clipBoard = new Clipboard('#copy_button');
	clipBoard.on('success', function(e) {
	e.clearSelection();
	showTooltip(e.trigger, 'Copied!');
	});

	clipBoard.on('error', function(e) {
	showTooltip(e.trigger, fallbackMessage(e.action));
	});

	addToCart();
}

function addToCart() {
	console.log("Setting cartURL");

	//getting the price
	var price = "";
	$(".price").each(function() {
		if ($(this).hasClass("active")) {
		    price = $(this).text();
		}
	});

	price = parseInt(price.substr(1));

	//getting the item that's active in the dropdown
	var selectedItem = $(".defaultprice").text();
	console.log("item=" + selectedItem);
	var selectedAmazonId = "";
	var itemPrice;

	randomItems = shuffle(items);

	for (var i = 0; i < randomItems.length; i++) {
		var element = randomItems[i];
		if (selectedItem === element.category_general || selectedItem === element.category_specific) {
			console.log("Found item: " + element.amazon_title);
			selectedAmazonId = element.asin;
			itemPrice = getPrice(selectedAmazonId); 
			console.log("itemPrice: " + itemPrice);
			if (itemPrice < 60) {
				break;
			}
			else {
				console.log("Item price too big, trying again");
			}
		}
	}

	var quantity = parseInt(price / itemPrice);

	if (Number.isNaN(quantity) || quantity === 0) {
		console.log("quantity is NaN");
		quantity = 1;
	} 

	console.log(selectedAmazonId);
	console.log(quantity);

	//linking the selected item to an item in the item data

	$.getJSON("add.php", { "quantity": quantity, "amazonID": selectedAmazonId }, function(data) {
		console.log(data);
		$("#cartURL").attr("href", data.url[0]);
	});
}

function setPrices(firstPrice) {
	if (Number.isNaN(firstPrice)) {
		firstPrice = 10;
	}
	$("#mainPrice").text("$" + firstPrice);
	$("#doublePrice").text("$" + (2 * firstPrice));
	$("#triplePrice").text("$" + (3 * firstPrice));
}

$('.price').click(function() {
    console.log("changing active");
    $('.price').removeClass('active');
    $(this).addClass('active');
});

$(document).on("click", ".needItem", function() {
    //setting the default price to the name
	console.log("setting default price to " + $(this).text());
	$(".defaultprice:first-child").text($(this).text());
	$(".defaultprice:first-child").val($(this).text());
	$("#firstprice").val($(this).text());

	randomItems = shuffle(items);

	console.log("shuffling");
	var selectedItem = $(".defaultprice").text();
	for (var i = 0; i < randomItems.length; i++) {
		var element = randomItems[i];
		if (selectedItem === element.category_general || selectedItem === element.category_specific) {
			console.log("adding element " + element.amazon_title);
			setPrices(getPrice(element.asin));
			addToCart();
			break;
		}
	}
	
});

$("li").click(function() {
    console.log("here");
});

$(document).on("click", "#cartURL", function() {
    addToCart();
});

var btns = document.querySelectorAll('#copy_button');
for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener('mouseleave', clearTooltip);
    btns[i].addEventListener('blur', clearTooltip);
}

function clearTooltip(e) {
    e.currentTarget.setAttribute('class', 'btn');
    e.currentTarget.removeAttribute('aria-label');
}

function showTooltip(elem, msg) {
    elem.setAttribute('class', 'btn tooltipped tooltipped-s tooltipped-no-delay');
    elem.setAttribute('aria-label', msg);
}

function fallbackMessage(action) {
    var actionMsg = '';
    var actionKey = (action === 'cut' ? 'X' : 'C');
    if (/iPhone|iPad/i.test(navigator.userAgent)) { actionMsg = 'No support :('; } else if (/Mac/i.test(navigator.userAgent)) { actionMsg = 'Press ⌘-' + actionKey + ' to ' + action; } else { actionMsg = 'Press Ctrl-' + actionKey + ' to ' + action; }
    return actionMsg;
}

function addEvent() {

    //getting the price
    var price = "";
    $(".price").each(function() {
        if ($(this).hasClass("active")) {
            price = $(this).text();
        }
    });

    price = parseInt(price.substr(1));

    //getting the item that's active in the dropdown
    var selectedItem = $(".defaultprice").text();
    console.log("item=" + selectedItem);
    var selectedAmazonId = "";
    var itemPrice;

    items.forEach(function(element) {
        if (selectedItem === element["name"]) {
            console.log("Found item");
            selectedAmazonId = element["amazonID"];
            itemPrice = parseInt(element["price"]);
        }
    });

    var quantity = parseInt(price / itemPrice);

    console.log(selectedAmazonId);
    console.log(quantity);

    //linking the selected item to an item in the item data

    $.getJSON("add_event.php", { "quantity": quantity, "asin": selectedAmazonId }, function(data) {});
}

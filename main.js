window.onload = function () {
	// event handlers for buttons
	document.querySelector("#DeleteButton").addEventListener("click", deleteItem);
	document.querySelector("#AddButton").addEventListener("click", addItem);
	document.querySelector(".addBtn").addEventListener("click", sendAdd);
	document
		.querySelector("#UpdateButton")
		.addEventListener("click", populateTable);
	document.querySelector("#UpdateButton").addEventListener("click", updateItem);
	document.querySelector(".upBtn").addEventListener("click", sendUpdate);

	// event handler for selections on the table
	document.querySelector("#dbTable").addEventListener("click", handleRowClick);
	document.querySelector("#LoadButton").addEventListener("click", getAllItems);
};

function sendUpdate() {
	let id = Number(document.querySelector("#id").value);
	let make = document.querySelector("#make").value;
	let model = document.querySelector("#model").value;
	let year = Number(document.querySelector("#year").value);
	let size = Number(document.querySelector("#cc").value);
	let price = Number(document.querySelector("#price").value);

	let obj = {
		id: id,
		make: make,
		model: model,
		year: year,
		size: size,
		price: price,
	};

	let payload = JSON.stringify(obj);

	let url = "MyApp/entities/" + id;
	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4) {
			let resp = xhr.responseText;
			//console.log(resp);
			let obj = JSON.parse(resp);
			if (obj.data === 1 && xhr.status === 201) {
				alert("Item Updated.");
			} else if (xhr.status === 400) {
				alert("Dirt Bike violates ".obj.error);
			} else if (xhr.status === 409) {
				alert("Dirt Bike violates ".obj.error);
			} else if (obj.data === null && xhr.status === 500) {
				alert("Server Error!");
			}
			getAllItems();
		}
	};
	xhr.open("PUT", url, true); //Updated
	xhr.send(payload);
	clearInputs();
}

function updateItem() {
	hideBtns(false);
	document.querySelector('label[for="id"]').classList.remove("hidden1");
	document.querySelector("input#id").classList.remove("hidden1");
}

function populateTable() {
	let row = document.querySelector(".selected");
	let id = Number(row.querySelectorAll("td")[0].innerHTML);
	let make = row.querySelectorAll("td")[1].innerHTML;
	let model = row.querySelectorAll("td")[2].innerHTML;
	let year = Number(row.querySelectorAll("td")[3].innerHTML);
	let size = Number(row.querySelectorAll("td")[4].innerHTML);
	let price = Number(row.querySelectorAll("td")[5].innerHTML);

	document.querySelector("#id").value = id;
	document.querySelector("#make").value = make;
	document.querySelector("#model").value = model;
	document.querySelector("#year").value = year;
	document.querySelector("#cc").value = size;
	document.querySelector("#price").value = price;
}

function sendAdd() {
	//let id = Number(document.querySelector("#id").value);
	let id = 1;
	let make = document.querySelector("#make").value;
	let model = document.querySelector("#model").value;
	let year = Number(document.querySelector("#year").value);
	let size = Number(document.querySelector("#cc").value);
	let price = Number(document.querySelector("#price").value);
	//console.log(id);

	let obj = {
		id: id, //Dummy data db auto increments
		make: make,
		model: model,
		year: year,
		size: size,
		price: price,
	};

	let payload = JSON.stringify(obj);

	let url = "MyApp/entities/" + id;
	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4) {
			let resp = xhr.responseText;
			//console.log(resp);
			let obj = JSON.parse(resp);
			if (obj.data === 1 && xhr.status === 201) {
				alert("Item added.");
			} else if (obj.data === 0 && xhr.status === 409) {
				alert("Item already exists.");
			} else if (obj.data === 1 && xhr.status === 400) {
				alert("Server rejected add(Bad Data)!");
			} else if (obj.data === null) {
				alert("Server Error!");
			}
			getAllItems();
		}
	};
	xhr.open("POST", url, true);
	xhr.send(payload);
	clearInputs();
}

//Gets
function addItem() {
	hideBtns(true); //Shows add btn
	document.querySelector('label[for="id"]').classList.add("hidden1");
	document.querySelector("input#id").classList.add("hidden1");
}

function clearInputs() {
	document.querySelector("#id").value = "";
	document.querySelector("#make").value = "";
	document.querySelector("#model").value = "";
	document.querySelector("#year").value = "";
	document.querySelector("#cc").value = "";
	document.querySelector("#price").value = "";
}

//True shows Add, False shows update btn
function hideBtns(bool) {
	let btnUp = document.querySelector(".upBtn");
	let btnAdd = document.querySelector(".addBtn");
	if (bool) {
		btnAdd.classList.remove("hidden1");
		btnUp.classList.add("hidden1");
	} else {
		btnAdd.classList.add("hidden1");
		btnUp.classList.remove("hidden1");
	}
}

//Gets dbID from table nd sends in GET request to API to delete item from db, if success refreshes table
// and displays success as a popup
function deleteItem() {
	let row = document.querySelector(".selected");
	let id = Number(row.querySelectorAll("td")[0].innerHTML);

	let url = "MyApp/entities/" + id;
	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4) {
			let resp = xhr.responseText;
			//console.log(resp);
			let obj = JSON.parse(resp);

			if (obj.data === 1 && xhr.status === 200) {
				alert("Item deleted.");
			} else if (obj.data === 0 && xhr.status === 404) {
				alert("Item does not exist.");
			} else if (obj.data === null) {
				alert("Server Error!");
			}
			getAllItems();
		}
	};
	xhr.open("DELETE", url, true); //Verb sent here
	xhr.send();
	clearInputs();
}

//Makes an ajax call to getAllDB to get data payload in json format
function getAllItems() {
	//let url = "api/getAllDB.php"; // file name or server-side process name
	let url = "MyApp/entities";
	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState === XMLHttpRequest.DONE) {
			if (xhr.status === 200) {
				let resp = xhr.responseText;
				//console.log(resp);
				if (resp.search("ERROR") >= 0) {
					alert("oh no, something is wrong with the GET ...");
				} else {
					buildTable(xhr.responseText);
					setButttonState(false);
					document.querySelector("#AddButton").removeAttribute("disabled");
				}
			} else if (xhr.status === 405) {
				alert("individual GETs not allowed");
			} else if (xhr.status === 500) {
				alert("Server Error");
			}
		}
	};
	xhr.open("GET", url, true);
	xhr.send();
}

//Test is a string that is JSON objects format
function buildTable(text) {
	let data = JSON.parse(text); // get JS Objects {data: Array(24), error: null}
	let arr = data.data;
	//console.log(arr);
	let html =
		"<table><tr><th>ID</th><th>Make</th><th>Model</th><th>Year</th><th>Size</th><th>MSRP</th></tr>";
	for (let i = 0; i < arr.length; i++) {
		let row = arr[i];
		html += "<tr>";
		html += "<td>" + row.dbID + "</td>";
		html += "<td>" + row.make + "</td>";
		html += "<td>" + row.model + "</td>";
		html += "<td>" + row.year + "</td>";
		html += "<td>" + row.size + "</td>";
		html += "<td>" + row.price + "</td>";
		html += "</tr>";
	}
	html += "</table>";
	let theTable = document.querySelector("#dbTable");
	theTable.innerHTML = html;
}

//Adds class to clicked row
function handleRowClick(evt) {
	clearSelection();
	let parent = evt.target.parentElement;
	if (parent.tagName === "TR") {
		evt.target.parentElement.classList.add("selected");
		setButttonState(true);
	}
}

//If true sent in enables buttons if false disables them
function setButttonState(bool) {
	if (bool) {
		document.querySelector("#DeleteButton").removeAttribute("disabled");
		document.querySelector("#UpdateButton").removeAttribute("disabled");
	} else {
		document
			.querySelector("#DeleteButton")
			.setAttribute("disabled", "disabled");
		document
			.querySelector("#UpdateButton")
			.setAttribute("disabled", "disabled");
	}
}

//Removes selected class from all rows
function clearSelection() {
	let rows = document.querySelectorAll("tr");
	for (let i = 0; i < rows.length; i++) {
		rows[i].classList.remove("selected");
	}
}

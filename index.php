<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>RESTful CRUD Webpage</title>
    <script src="main.js"></script>
    <style>
        .btnCont button {
            margin: 7px;
            padding: 3px;
            font-size: 1.05rem;
        }

        .btnCont {
            background-color: beige;
            border: thin grey dotted;
            padding: 10px;
            width: fit-content;
            padding: 15px;
            height: fit-content;
        }

        table {
            margin-top: 1rem;
        }

        table,
        td,
        th {
            border: solid thin black;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 10px;
        }

        th {
            background-color: #555;
            color: white;
            font-weight: bold;
        }

        td:not(:nth-child(3)),
        th:not(:nth-child(3)) {
            text-align: center;
        }

        tr:not(:first-child):not(.selected):hover {
            background-color: #eee;
            cursor: pointer;
        }

        .selected {
            background-color: lightseagreen;
        }

        .editCont {
            padding: 30px;
            border: thin dashed gray;
            width: 250px;
            background-color: lightcyan;
        }

        .editCont h2 {
            text-align: center;
        }

        .container {
            display: grid;
            grid-template-columns: 6fr 6fr;
        }

        .hidden1 {
            display: none;
        }

        .leftCont {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .subBtn {
            margin-top: 10px;
            font-size: 1.2rem;
            display: flex;
            justify-content: center;
        }

        .addBtn {
            margin-right: 25px;
        }

        .upBtn {
            margin-left: 25px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="leftCont">
            <h1>Dirt Bike Database Manager</h1>
            <div class="btnCont">
                <button id="LoadButton" class="btn-primary">Load Items</button>
                <div id="ButtonPanel">
                    <button id="AddButton" class="btn-primary" disabled>Add</button>
                    <button id="DeleteButton" class="btn-primary" disabled>
                        Delete
                    </button>
                    <button id="UpdateButton" class="btn-primary" disabled>
                        Update
                    </button>
                </div>
            </div>
        </div>

        <div class="editCont">
            <h2>Dirt Bike Info</h4>
            <label for="id">Dirt Bike ID</label><br>
            <input type="text" name="id" id="id"><br>
            <label for='make'>Make</label><br>
            <input type='text' name='make' id='make'><br>
            <label for='model'>Model</label><br>
            <input type='text' name='model' id='model'><br>
            <label for='year'>Manufacture Year</label><br>
            <input type='number' max='2025' min='1985' name='year' id='year'><br>
            <label for='cc'>CC</label><br>
            <input type='number' max='1000' min='49' name='cc' id='cc'><br>
            <label for='price'>MSRP</label><br>
            <input type='number' step='0.01' max='20000' min='1' name='price' id='price'><br>
            <div class="subBtn"><button class="addBtn hidden1">Add</button><button class="upBtn hidden1">Update</button></div>
        </div>

        <div id="dbTable">
            <!-- to be filled in by JS -->
        </div>
    </div>
</body>

</html>
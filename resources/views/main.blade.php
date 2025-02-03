<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket + CRUD</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        let ws = new WebSocket("ws://localhost:8080");

        ws.onopen = function () {
            console.log("Подключено к WebSocket серверу");
        };

        ws.onmessage = function (event) {
            console.log("Получено сообщение:", event.data);
            let messageBox = document.getElementById("messages");
            if (messageBox) {
                messageBox.innerHTML += `<p>${event.data}</p>`;
            }
        };

        ws.onerror = function (error) {
            console.error("Ошибка WebSocket:", error);
        };

        ws.onclose = function () {
            console.log("Соединение закрыто");
        };

        async function createItem() {
            let name = document.getElementById("product-name").value.trim();
            let price = document.getElementById("product-price").value.trim();
            let stock = document.getElementById("product-stock").value.trim();

            if (!name || !price || !stock) {
                alert("Заполните все поля!");
                return;
            }

            try {
                let response = await fetch('/api/products', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, price, stock })
                });

                if (!response.ok) throw new Error(`Ошибка: ${response.statusText}`);
                let data = await response.json();

                ws.send(`Создан товар: ${data.name} (ID: ${data.id})`);
                document.getElementById("product-name").value = "";
                document.getElementById("product-price").value = "";
                document.getElementById("product-stock").value = "";
                loadItems();
            } catch (error) {
                console.error("Ошибка при создании товара:", error);
            }
        }

        async function loadItems() {
            let response = await fetch('/api/products');
            let data = await response.json();
            let list = document.getElementById("product-list");
            if (data.length === 0) {
                list.innerHTML = "<li>Нет товаров</li>";
            } else {
                list.innerHTML = data.map(item => `
                <li>
                    ${item.name} - ${item.price}₽ - ${item.stock} шт.
                    <button onclick="editItem(${item.id}, '${item.name}', ${item.price}, ${item.stock})">✏️</button>
                    <button onclick="deleteItem(${item.id})">❌</button>
                </li>
            `).join('');
            }
        }

        async function updateItem(id) {
            let name = document.getElementById("update-name").value.trim();
            let price = document.getElementById("update-price").value.trim();
            let stock = document.getElementById("update-stock").value.trim();

            if (!name || !price || !stock) {
                alert("Заполните все поля!");
                return;
            }

            try {
                await fetch(`/api/products/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, price, stock })
                });
                ws.send(`Товар (ID: ${id}) обновлен`);
                loadItems();
                clearUpdateForm();
                hideUpdateForm();
            } catch (error) {
                console.error("Ошибка при обновлении товара:", error);
            }
        }

        async function deleteItem(id) {
            await fetch(`/api/products/${id}`, { method: 'DELETE' });
            ws.send(`Товар (ID: ${id}) удален`);
            loadItems();
        }

        function editItem(id, name, price, stock) {
            document.getElementById("update-name").value = name;
            document.getElementById("update-price").value = price;
            document.getElementById("update-stock").value = stock;
            document.getElementById("update-button").onclick = function() { updateItem(id); };
            showUpdateForm();
        }

        function showUpdateForm() {
            document.getElementById("update-form").style.display = "block";
        }

        function hideUpdateForm() {
            document.getElementById("update-form").style.display = "none";
        }

        function clearUpdateForm() {
            document.getElementById("update-name").value = '';
            document.getElementById("update-price").value = '';
            document.getElementById("update-stock").value = '';
            document.getElementById("update-button").onclick = null;
        }

        window.onload = function() {
            loadItems();
        };
    </script>
</head>
<body>
@if(auth()->check())
    <p>Привет, {{ auth()->user()->name }}!</p>
@else
    <script>window.location.href = "{{ route('login') }}";</script>
@endif

<h1>Товарный лист</h1>

<h2>Сообщения:</h2>
<div id="messages"></div>

<h2>Список товаров:</h2>
<ul id="product-list"></ul>
<div id="update-form" style="display:none;">
    <input type="text" id="update-name" placeholder="Название товара">
    <input type="number" id="update-price" placeholder="Цена товара">
    <input type="number" id="update-stock" placeholder="Количество товара">
    <button id="update-button">Сохранить изменения</button>
</div>


<h2>Добавить товар:</h2>
<input type="text" id="product-name" placeholder="Введите название товара">
<input type="number" id="product-price" placeholder="Цена товара">
<input type="number" id="product-stock" placeholder="Количество товара">
<button type="button" class="btn btn-secondary" onclick="createItem()">Создать товар</button>

</body>
</html>

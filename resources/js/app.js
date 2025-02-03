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

window.createItem = async function () {
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
};

window.loadItems = async function () {
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
};

window.updateItem = async function (id) {
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
};

window.deleteItem = async function (id) {
    await fetch(`/api/products/${id}`, { method: 'DELETE' });
    ws.send(`Товар (ID: ${id}) удален`);
    loadItems();
};

window.editItem = function (id, name, price, stock) {
    document.getElementById("update-name").value = name;
    document.getElementById("update-price").value = price;
    document.getElementById("update-stock").value = stock;
    document.getElementById("update-button").onclick = function() { updateItem(id); };
    showUpdateForm();
};

window.showUpdateForm = function () {
    document.getElementById("update-form").style.display = "block";
};

window.hideUpdateForm = function () {
    document.getElementById("update-form").style.display = "none";
};

window.clearUpdateForm = function () {
    document.getElementById("update-name").value = '';
    document.getElementById("update-price").value = '';
    document.getElementById("update-stock").value = '';
    document.getElementById("update-button").onclick = null;
};

window.onload = function() {
    loadItems();
};

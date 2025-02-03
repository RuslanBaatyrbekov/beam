<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket + CRUD</title>
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
@vite('resources/js/app.js')
</body>
</html>

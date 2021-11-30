<h1>Оформление заказа</h1>
<p>Вернуться в <a href='/catalog'>каталог</a></p>
<form action="/save_order" method="post" autocomplete="off">
    <div>
        <label>
            Заказчик:
            <input type="text" name="customer" size="50" required/>
        </label>
    </div>
    <div>
        <label>
            Email заказчика:
            <input type="email" name="email" size="50" required/>
        </label>
    </div>
    <div>
        <label>
            Телефон для связи:
            <input type="tel" name="phone" size="50" required/>
        </label>
    </div>
    <div>
        <label>
            Адрес доставки:
            <input type="text" name="address" size="50" required/>
        </label>
    </div>
    <div>
        <button type="submit">Заказать</button>
    </div>
</form>
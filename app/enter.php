<h1>Вход в админку</h1>
<p>Вернуться в <a href='/catalog'>каталог</a></p>
<form action="/login" method="post" autocomplete="off">
    <div>
        <label>
            Логин:
            <input type="text" name="login" size="25" required>
        </label>
    </div>
    <div>
        <label>
            Пароль:
            <input type="password" name="password" size="25" required>
        </label>
    </div>
    <div>
        <button type="submit">Войти</button>
    </div>
</form>
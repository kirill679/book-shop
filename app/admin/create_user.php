<h1>Добавить пользователя</h1>
<p><a href='/admin'>Назад в админку</a></p>
<form action="save_user" method="post" autocomplete="off">
    <div>
        <label>
            Логин:
            <input type="text" name="login" size="25" placeholder="Введите логин" required>
        </label>
    </div>
    <div>
        <label>
            Пароль:
            <input type="password" name="password" size="25" placeholder="Введите пароль" required>
        </label>
    </div>
    <div>
        <label>
            Email:
            <input type="email" name="email" size="25" placeholder="Введите email" required>
        </label>
    </div>
    <div>
        <button type="submit">Создать</button>
    </div>
</form>
<h1>Добавить товар в каталог</h1>
<p><a href='/admin'>Назад в админку</a></p>
<form action="save_item_to_catalog" method="post" autocomplete="off">
    <div>
        <label>
            Название:
            <input type="text" name="title" size="50" placeholder="Введите название" required>
        </label>
    </div>
    <div>
        <label>
            Автор:
            <input type="text" name="author" size="50" placeholder="Введите автора" required>
        </label>
    </div>
    <div>
        <label>
            Год издания:
            <input type="number" name="pub_year" size="50" maxlength="4" placeholder="Введите год издания" min="0"
                   max="9999" required>
        </label>
    </div>
    <div>
        <label>
            Цена (руб.):
            <input type="number" name="price" size="50" maxlength="6" placeholder="Введите цену" min="0" max="999999"
                   required>
        </label>
    </div>
    <div>
        <button type="submit">Добавить</button>
    </div>
</form>

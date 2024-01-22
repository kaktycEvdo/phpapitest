jQuery(($) => {

    // Показать html форму при нажатии кнопки «создать товар»
    $(document).on("click", ".create-product-button", () => {

        // Загрузка списка категорий
        $.getJSON("http://localhost/api/category/read.php", (data) => {
            // Перебор возвращаемого списка данных и создание списка выбора
            let categories_options_html=`<select name="category_id" class="form-control">`;

            $.each(data.records, (key, val) => {
                categories_options_html+=`<option value="` + val.id + `">` + val.name + `</option>`;
            });

            categories_options_html += `</select>`;
            let create_product_html=`

            <!-- Кнопка для показа всех товаров -->
            <div id="read-products" class="btn btn-primary pull-right m-b-15px read-products-button">
                <span class="glyphicon glyphicon-list"></span> Все товары
            </div>
            <!-- html форма «Создание товара» -->
            <form id="create-product-form" action="#" method="post" border="0">
                <table class="table table-hover table-responsive table-bordered">

                    <tr>
                        <td>Название</td>
                        <td><input type="text" name="name" class="form-control" required /></td>
                    </tr>

                    <tr>
                        <td>Цена</td>
                        <td><input type="number" min="1" name="price" class="form-control" required /></td>
                    </tr>

                    <tr>
                        <td>Описание</td>
                        <td><textarea name="description" class="form-control" required></textarea></td>
                    </tr>

                    <!-- Список выбора категории -->
                    <tr>
                        <td>Категория</td>
                        <td>` + categories_options_html + `</td>
                    </tr>

                    <!-- Кнопка отправки формы -->
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus"></span> Создать товар
                            </button>
                        </td>
                    </tr>

                </table>
            </form>`;
        });
        // Вставка html в «page-content» нашего приложения
        $("#page-content").html(create_product_html);

        // Изменяем тайтл
        changePageTitle("Создание товара");
    });

    // Будет работать, если создана форма товара
    $(document).on("submit", "#create-product-form", function () {
        
        // Получение данных формы
        let form_data=JSON.stringify($(this).serializeObject());

        // Отправка данных формы в API
        $.ajax({
            url: "http://localhost/api/product/create.php",
            type : "POST",
            contentType : "application/json",
            data : form_data,
            success : result => {

                // Товар был создан, вернуться к списку товаров
                showProducts();
            },
            error: (xhr, resp, text) => {

                // Вывести ошибку в консоль
                console.log(xhr, resp, text);
            }
        });
        
        return false;
    });
});
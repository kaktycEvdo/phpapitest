jQuery($ => {

    // Обрабатываем нажатие кнопки «Просмотр товара»
    $(document).on("click", ".read-one-product-button", function () {

        // Получаем ID товара
        const id = $(this).attr("data-id");
        // Чтение записи товара на основе данного идентификатора
        $.getJSON("http://localhost/api/product/read_one.php?id=" + id, data => {
            
            // Начало HTML
            let read_one_product_html = `
                
            <!-- При нажатии будем отображать список товаров -->
            <div id="read-products" class="btn btn-primary pull-right m-b-15px read-products-button">
                <span class="glyphicon glyphicon-list"></span> Все товары
            </div>
            <!-- Полные данные о товаре будут показаны в этой таблице -->
            <table class="table table-bordered table-hover">

                <tr>
                    <td class="w-30-pct">Название</td>
                    <td class="w-70-pct">` + data.name + `</td>
                </tr>

                <tr>
                    <td>Цена</td>
                    <td>` + data.price + `</td>
                </tr>

                <tr>
                    <td>Описание</td>
                    <td>` + data.description + `</td>
                </tr>

                <tr>
                    <td>Категория</td>
                    <td>` + data.category_name + `</td>
                </tr>

            </table>`;
        });
        // Вставка HTML в «page-content» нашего приложения
        $("#page-content").html(read_one_product_html);

        // Изменяем заголовок страницы
        changePageTitle("Просмотр товара");
    });

});
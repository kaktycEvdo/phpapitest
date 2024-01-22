jQuery($ => {

    // Показать список товаров при первой загрузке
    showProducts();

    // Когда была нажата кнопка «Все товары»
    $(document).on("click", ".read-products-button", function () {
        showProducts();
    });

    // Когда была нажата кнопка пагинации
    $(document).on("click", ".pagination li", function () {

        // Получаем JSON URL
        const json_url = $(this).find("a").attr("data-page");

        // Покажем список товаров с пагинацией
        showProducts(json_url);
    });

});

// Функция для отображения списка товаров
function showProducts(json_url = "http://localhost/phpapitest/api/product/read_paging.php") {

    // Получаем список товаров из API
    $.getJSON(json_url, function (data) {

        // HTML для перечисления товаров
        readProductsTemplate(data, "");

        // Изменим заголовок страницы
        changePageTitle("Все товары");
    });
}
jQuery($ => {

    // Когда была нажата кнопка «Поиск товаров»
    $(document).on("submit", "#search-product-form", function () {

        // Получаем ключевые слова для поиска
        const keywords = $(this).find("input[name='keywords']").val();

        // Получаем данные из API на основе поисковых ключевых слов
        $.getJSON("http://localhost/phpapitest/api/product/search.php?s=" + keywords, data => {

            // Шаблон в products.js
            readProductsTemplate(data, keywords);

            // Изменяем title
            changePageTitle("Поиск товаров: " + keywords);

        });

        // Предотвращаем перезагрузку всей страницы
        return false;
    });

});
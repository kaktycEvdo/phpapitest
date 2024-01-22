<?php

// установим HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов для соединения с БД и файл с объектом Category
include_once "../config/database.php";
include_once "../objects/admin.php";
include_once "../objects/cook.php";
include_once "../objects/waiter.php";

// создание подключения к базе данных
$database = new Database();
$db = $database->getConnection();

// инициализация объектов
$admins = new Admin($db);
$cooks = new Cook($db);
$waiters = new Waiter($db);

// получаем категории
$admins_all = $admins->read();
$cooks_all = $cooks->read();
$waiters_all = $waiters->read();
$a_num = $admins_all->rowCount();
$c_num = $cooks_all->rowCount();
$w_num = $waiters_all->rowCount();

// проверяем, найдено ли больше 0 записей
if ($num > 0) {
    $login = isset($_GET["login"]) ? $_GET["login"] : "";
    $password = isset($_GET["password"]) ? $_GET["password"] : "";

    if($admins->search($login, $password))

    // получим содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлекаем строку
        extract($row);
        $category_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );
        array_push($categories_arr["records"], $category_item);
    }
    // код ответа - 200 OK
    http_response_code(200);

    // покажем данные категорий в формате json
    echo json_encode($categories_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что категории не найдены
    echo json_encode(array("message" => "Категории не найдены"), JSON_UNESCAPED_UNICODE);
}
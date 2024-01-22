<?php

class Waiter
{
    // подключение к базе данных и таблице "admin"
    private $conn;
    private $table_name = "admin";

    // свойства объекта
    public $id;
    public $name;
    public $status;
    public $group_id;

    // конструктор для соединения с базой данных
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения админов
    function read()
    {
        // выбираем все записи
        $query = "SELECT
            a.id, a.name, a.status, g.group_name
        FROM
            " . $this->table_name . " a
            JOIN
                groups g
                    ON a.group_id = a.id
        ORDER BY
            a.name";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // выполняем запрос
        $stmt->execute();
        return $stmt;
    }

    // метод для создания элементов не требуется, но оставлю на будущее себе.
    function create()
    {
        // запрос для вставки (создания) записей
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id=:id, name=:name, status=:status, group_id=:group_id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->group_id = htmlspecialchars(strip_tags($this->group_id));

        // привязка значений
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":group_id", $this->group_id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // метод для получения конкретного пользователя по ID
    function readOne()
    {
        // запрос для чтения одной записи (товара)
        $query = "SELECT
                a.name, a.id, a.status, a.group_id, g.group_name
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    groups g
                        ON p.group_id = g.id
            WHERE
                p.id = ?
            LIMIT
                0,1";
                
        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // привязываем id товара, который будет получен
        $stmt->bindParam(1, $this->id);

        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта
        $this->id = $row["id"];
        $this->name = $row["name"];
        $this->status = $row["status"];
        $this->group_id = $row["group_id"];
        $this->category_name = $row["category_name"];
    }

    // метод для поиска товаров
    function search($keywords)
    {
        // поиск записей (товаров) по "названию товара", "описанию товара", "названию категории"
        $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            WHERE
                p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?
            ORDER BY
                p.created DESC";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        // привязка
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        // выполняем запрос
        $stmt->execute();

        return $stmt;
    }

    // получение товаров с пагинацией
    public function readPaging($from_record_num, $records_per_page)
    {
        // выборка
        $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            ORDER BY p.created DESC
            LIMIT ?, ?";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // свяжем значения переменных
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        // выполняем запрос
        $stmt->execute();

        // вернём значения из базы данных
        return $stmt;
    }

    // данный метод возвращает кол-во товаров
    public function count()
    {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["total_rows"];
    }
}
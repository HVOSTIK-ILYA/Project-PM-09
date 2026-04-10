<?php
// Подключение к БД
$link = mysqli_connect('localhost', 'root', '', 'energy_db');
mysqli_set_charset($link, "utf8");

// Проверяем, отправлена ли форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Получаем данные из формы
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $password = $_POST['password']; // Не экранируем, будем хешировать
    $last_name = mysqli_real_escape_string($link, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($link, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($link, $_POST['middle_name']);
    $phone = mysqli_real_escape_string($link, $_POST['phone']);
    
    // Генерируем номер лицевого счета (например, на основе времени)
    $account_number = 'LS' . time();
    
    // Хешируем пароль — НИКОГДА не храните пароли в открытом виде!
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // По умолчанию даем роль "Пользователь" (предположим, её ID = 2)
    $role_id = 2;
    
    // SQL запрос на вставку
    $sql = "INSERT INTO users (username, email, password_hash, last_name, first_name, middle_name, phone, account_number, role_id) 
            VALUES ('$username', '$email', '$password_hash', '$last_name', '$first_name', '$middle_name', '$phone', '$account_number', $role_id)";
    
    // Выполняем запрос
    if (mysqli_query($link, $sql)) {
        echo "<p style='color: green;'>Регистрация успешна!</p>";
    } else {
        // Если ошибка — выводим её
        echo "<p style='color: red;'>Ошибка: " . mysqli_error($link) . "</p>";
    }
}
?>

<form method="POST" action="register.php">
    <h2>Регистрация</h2>
    
    <label>Логин:</label>
    <input type="text" name="username" required>
    
    <label>Email:</label>
    <input type="email" name="email" required>
    
    <label>Пароль:</label>
    <input type="password" name="password" required>
    
    <label>Фамилия:</label>
    <input type="text" name="last_name" required>
    
    <label>Имя:</label>
    <input type="text" name="first_name" required>
    
    <label>Отчество:</label>
    <input type="text" name="middle_name">
    
    <label>Телефон:</label>
    <input type="text" name="phone" required>
    
    <button type="submit">Зарегистрироваться</button>
</form>
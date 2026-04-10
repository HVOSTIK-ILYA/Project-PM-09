<?php
// Файл: register_ajax.php
header('Content-Type: application/json');

require_once 'includes/db_connect.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $password = $_POST['password'];
    $account_number = isset($_POST['account_number']) ? mysqli_real_escape_string($link, $_POST['account_number']) : '';
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($link, $_POST['phone']) : '';
    
    // Проверка на существующего пользователя
    $check_sql = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = mysqli_query($link, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $response['message'] = 'Пользователь с таким логином или email уже существует';
    } else {
        // Хешируем пароль
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $role_id = 2;
        
        $sql = "INSERT INTO users (username, email, password_hash, last_name, first_name, phone, account_number, role_id) 
                VALUES ('$username', '$email', '$password_hash', '', '', '$phone', '$account_number', $role_id)";
        
        if (mysqli_query($link, $sql)) {
            $response['success'] = true;
            $response['message'] = 'Регистрация успешна!';
        } else {
            $response['message'] = 'Ошибка базы данных: ' . mysqli_error($link);
        }
    }
}

echo json_encode($response);
?>
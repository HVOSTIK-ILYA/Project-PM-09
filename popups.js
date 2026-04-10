// Файл: js/popups.js

// Функция открытия попапа
function openPopup(type) {
    document.getElementById('overlay').classList.add('active');
    document.getElementById(type + 'Popup').classList.add('active');
}

// Функция закрытия всех попапов
function closeAllPopups() {
    document.getElementById('overlay').classList.remove('active');
    
    // Закрываем все попапы
    var popups = document.getElementsByClassName('popup');
    for (var i = 0; i < popups.length; i++) {
        popups[i].classList.remove('active');
    }
}

// Переход из меню выбора к попапу регистрации или входа (без закрытия оверлея)
function openPopupFromChoice(type) {
    var choicePopup = document.getElementById('authChoicePopup');
    if (choicePopup) choicePopup.classList.remove('active');
    var targetPopup = document.getElementById(type + 'Popup');
    if (targetPopup) targetPopup.classList.add('active');
}

// Ждём полной загрузки страницы, чтобы скрипт нашёл все элементы
document.addEventListener('DOMContentLoaded', function() {
    
    // Отправка формы регистрации без перезагрузки страницы (AJAX)
    var registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Отменяем обычную отправку формы
            
            var formData = new FormData(this);
            
            fetch('register_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var resultDiv = document.getElementById('registerResult');
                if (data.success) {
                    resultDiv.innerHTML = '<div class="success-message">' + data.message + '</div>';
                    // Очищаем форму
                    document.getElementById('registerForm').reset();
                    // Через 2 секунды закрываем попап
                    setTimeout(closeAllPopups, 2000);
                } else {
                    resultDiv.innerHTML = '<div class="error-message">' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
        });
    }

    // Отправка формы входа без перезагрузки страницы
    var loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            
            fetch('login_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                var resultDiv = document.getElementById('loginResult');
                if (data.success) {
                    resultDiv.innerHTML = '<div class="success-message">' + data.message + '</div>';
                    document.getElementById('loginForm').reset();
                    setTimeout(closeAllPopups, 2000);
                    // Обновляем страницу после успешного входа
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    resultDiv.innerHTML = '<div class="error-message">' + data.message + '</div>';
                }
            });
        });
    }

    // Закрытие по ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllPopups();
        }
    });
});
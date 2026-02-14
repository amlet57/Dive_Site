<?php
// admin/index.php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель | DiveGear</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --dg-primary: #0a3142;
            --dg-secondary: #1f6e8a;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            background: white;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 450px;
            margin: 0 auto;
        }
        
        .login-logo {
            font-size: 3rem;
            color: var(--dg-secondary);
            margin-bottom: 1rem;
        }
        
        .btn-login {
            background: linear-gradient(145deg, var(--dg-secondary), var(--dg-primary));
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(31,110,138,0.3);
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .demo-credentials {
            background: #e2f0f5;
            border-radius: 15px;
            padding: 1.2rem;
            margin-top: 2rem;
            border-left: 4px solid var(--dg-secondary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="text-center">
                        <div class="login-logo">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h2 class="fw-bold mb-1">DiveGear Admin</h2>
                        <p class="text-muted mb-4">Вход в систему управления</p>
                    </div>
                    
                    <?php if (isset($_GET['error'])): ?>
                    <div class="error-message">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Неверный логин или пароль
                        <?php unset($_GET['error']); ?>           
                    </div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="POST">
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 rounded-start-pill px-4">
                                    <i class="bi bi-person-fill"></i>
                                </span>
                                <input type="text" class="form-control rounded-end-pill" name="username" placeholder="Логин" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 rounded-start-pill px-4">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input type="password" class="form-control rounded-end-pill" name="password" placeholder="Пароль" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Войти
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="../index.html" class="text-decoration-none small">
                            <i class="bi bi-arrow-left me-1"></i>Вернуться на сайт
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
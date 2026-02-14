<?php
session_start();
require_once 'config/database.php';
$settings = getSettings($pdo);
$orderNumber = null;
$success = false;
$error = false;

function sanitize_input($v) {
    return htmlspecialchars(strip_tags(trim((string)$v)));
}

$form_errors = [];
$form_values = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clothing_type = sanitize_input($_POST['clothing_type'] ?? '');
    $size = sanitize_input($_POST['size'] ?? '');
    $quantity = filter_var($_POST['quantity'] ?? 0, FILTER_VALIDATE_INT);
    $customer_name = sanitize_input($_POST['customer_name'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $address = sanitize_input($_POST['address'] ?? '');
    $comment = sanitize_input($_POST['comment'] ?? '');

    $form_values = [
        'clothing_type' => $clothing_type,
        'size' => $size,
        'quantity' => $quantity,
        'customer_name' => $customer_name,
        'phone' => $phone,
        'address' => $address,
        'comment' => $comment
    ];

    if ($clothing_type === '') {
        $form_errors[] = 'Не выбран вид спецодежды';
    }
    if ($size === '') {
        $form_errors[] = 'Не указан размер';
    }
    if ($quantity === false || $quantity < 1 || $quantity > 1000) {
        $form_errors[] = 'Количество должно быть от 1 до 1000';
    }
    if ($customer_name === '' || mb_strlen($customer_name) < 5) {
        $form_errors[] = 'Введите корректное ФИО';
    }
    if ($phone === '' || !preg_match('/^\+7\s?\(?\d{3}\)?\s?\d{3}-\d{2}-\d{2}$/', $phone)) {
        $form_errors[] = 'Введите корректный номер телефона';
    }
    if ($address === '' || mb_strlen($address) < 10) {
        $form_errors[] = 'Укажите полный адрес доставки';
    }

    if (empty($form_errors)) {
        try {
            $pdo->beginTransaction();
            $order_number = generateOrderNumber();
            $stmt = $pdo->prepare("SELECT base_price FROM clothing_types WHERE name = ? LIMIT 1");
            $stmt->execute([$clothing_type]);
            $price = $stmt->fetchColumn();
            $total_price = $price ? ($price * $quantity) : null;

            $ins = $pdo->prepare("INSERT INTO orders (order_number, clothing_type, size, quantity, customer_name, customer_phone, delivery_address, comment, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $ins->execute([$order_number, $clothing_type, $size, $quantity, $customer_name, $phone, $address, $comment ?: null, $total_price]);

            $pdo->commit();
            $success = true;
            $orderNumber = $order_number;
            $form_values = [];
        } catch (PDOException $e) {
            $pdo->rollBack();
            $form_errors[] = 'Ошибка при сохранении заказа';
            error_log('Order save error: ' . $e->getMessage());
        }
    }
}
?>
    <!-- SEO Основное -->
    <title>DiveGear — производитель спецодежды и аксессуаров для дайвинга</title>
    <meta name="description" content="DiveGear: производство гидрокостюмов, ботов, перчаток и сумок для дайвинга. Индивидуальный пошив, неопрен премиум-класса, доставка по РФ.">
    <meta name="keywords" content="спецодежда для дайвинга, гидрокостюм купить, производитель снаряжения, неопрен, аксессуары дайвера">
    <meta name="author" content="DiveGear">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Соцсети -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="DiveGear — снаряжение для профессионалов подводного мира">
    <meta property="og:description" content="Собственное производство в РФ. Костюмы из японского неопрена.">
    <meta property="og:image" content="https://divegear.ru/assets/images/og-preview.jpg">
    <meta property="og:url" content="https://divegear.ru">
    <meta property="og:site_name" content="DiveGear">
    
    <!-- Favicon (разместите в корне .ico, .png) -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Кастомные стили -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Микроразметка Schema.org для организации -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "DiveGear",
        "url": "https://divegear.ru",
        "logo": "https://divegear.ru/assets/images/logo.png",
        "description": "Производство спецодежды и аксессуаров для дайвинга",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Москва, РФ"
        }
    }
    </script>
</head>
<body>

 
<nav class="navbar navbar-expand-lg navbar-dark navbar-dive sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.html">
            <i class="bi bi-water"></i> DiveGear
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.html">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">О компании</a></li>
                <li class="nav-item"><a class="nav-link" href="order.php">Услуги</a></li>
                <li class="nav-item"><a class="nav-link" href="portfolio.html">Портфолио</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.html">Контакты</a></li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <!-- Hero секция -->
    <section class="order-hero text-center">
        <div class="container">
            <span class="badge bg-white text-dark px-4 py-2 rounded-pill mb-3">
                <i class="bi bi-box-seam me-2"></i>ИНДИВИДУАЛЬНЫЙ ЗАКАЗ
            </span>
            <h1 class="display-4 fw-bold mb-4">Заказ спецодежды и снаряжения</h1>
            <p class="lead mx-auto" style="max-width: 700px;">Заполните форму — мы подберем идеальный размер и подготовим коммерческое предложение в течение 2 часов</p>
        </div>
    </section>

    <div class="container">
        <!-- Сообщение об успешном заказе (показывается после отправки) -->
        <div id="successMessage" style="<?php echo $success ? 'display:block;' : 'display:none;'; ?>" class="alert-success mb-5">
            <i class="bi bi-check-circle-fill fs-1"></i>
            <h3 class="mt-3 fw-bold">Заказ успешно оформлен!</h3>
            <p class="mb-0 fs-5">Номер вашего заказа: <strong id="orderNumberDisplay"><?php echo $orderNumber ? $orderNumber : ''; ?></strong></p>
            <p class="text-muted mt-2">Мы свяжемся с вами в ближайшее время для подтверждения.</p>
            <button onclick="resetForm()" class="btn btn-outline-dark rounded-pill px-5 mt-3">Оформить еще заказ</button>
        </div>

        <?php if (!empty($form_errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($form_errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="row" id="orderForm">
            <div class="col-lg-8 mx-auto">
                <div class="order-form-container">
                    <h2 class="fw-bold mb-4" style="color: var(--dg-primary);">Оформить заказ</h2>
                    
                    <form id="orderFormElement" method="POST" action="order.php" class="needs-validation" novalidate>
                        
                        <div class="mb-4">
                            <label for="clothing_type" class="form-label">
                                <i class="bi bi-backpack me-2"></i>Вид спецодежды *
                            </label>
                            <select class="form-select" id="clothing_type" name="clothing_type" required>
                                <option value="" disabled <?php echo empty($form_values['clothing_type']) ? 'selected' : ''; ?>>Выберите тип изделия</option>
                                <optgroup label="Гидрокостюмы">
                                    <option value="Сухой гидрокостюм «Арктика»" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Сухой гидрокостюм «Арктика»') ? 'selected' : ''; ?>>Сухой гидрокостюм «Арктика»</option>
                                    <option value="Мокрый гидрокостюм 3 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Мокрый гидрокостюм 3 мм') ? 'selected' : ''; ?>>Мокрый гидрокостюм 3 мм</option>
                                    <option value="Мокрый гидрокостюм 5 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Мокрый гидрокостюм 5 мм') ? 'selected' : ''; ?>>Мокрый гидрокостюм 5 мм</option>
                                    <option value="Мокрый гидрокостюм 7 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Мокрый гидрокостюм 7 мм') ? 'selected' : ''; ?>>Мокрый гидрокостюм 7 мм</option>
                                </optgroup>
                                <optgroup label="Термобелье">
                                    <option value="Термобелье дайвера" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Термобелье дайвера') ? 'selected' : ''; ?>>Термобелье дайвера</option>
                                </optgroup>
                                <optgroup label="Перчатки">
                                    <option value="Перчатки неопреновые 3 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Перчатки неопреновые 3 мм') ? 'selected' : ''; ?>>Перчатки неопреновые 3 мм</option>
                                    <option value="Перчатки неопреновые 5 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Перчатки неопреновые 5 мм') ? 'selected' : ''; ?>>Перчатки неопреновые 5 мм</option>
                                    <option value="Перчатки неопреновые 7 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Перчатки неопреновые 7 мм') ? 'selected' : ''; ?>>Перчатки неопреновые 7 мм</option>
                                </optgroup>
                                <optgroup label="Обувь">
                                    <option value="Ботинки неопреновые 3 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Ботинки неопреновые 3 мм') ? 'selected' : ''; ?>>Ботинки неопреновые 3 мм</option>
                                    <option value="Ботинки неопреновые 5 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Ботинки неопреновые 5 мм') ? 'selected' : ''; ?>>Ботинки неопреновые 5 мм</option>
                                    <option value="Ботинки неопреновые 7 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Ботинки неопреновые 7 мм') ? 'selected' : ''; ?>>Ботинки неопреновые 7 мм</option>
                                    <option value="Носки под ласты 2 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Носки под ласты 2 мм') ? 'selected' : ''; ?>>Носки под ласты 2 мм</option>
                                    <option value="Носки под ласты 3 мм" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Носки под ласты 3 мм') ? 'selected' : ''; ?>>Носки под ласты 3 мм</option>
                                </optgroup>
                                <optgroup label="Аксессуары">
                                    <option value="Сумка-туба «Нерпа» 40л" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Сумка-туба «Нерпа» 40л') ? 'selected' : ''; ?>>Сумка-туба «Нерпа» 40л</option>
                                    <option value="Сумка для ласт" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Сумка для ласт') ? 'selected' : ''; ?>>Сумка для ласт</option>
                                    <option value="Чехол для регата" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Чехол для регата') ? 'selected' : ''; ?>>Чехол для регата</option>
                                    <option value="Коврик неопреновый" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Коврик неопреновый') ? 'selected' : ''; ?>>Коврик неопреновый</option>
                                </optgroup>
                                <optgroup label="Кастом">
                                    <option value="Кастомный пошив (индивидуальный заказ)" <?php echo (isset($form_values['clothing_type']) && $form_values['clothing_type'] === 'Кастомный пошив (индивидуальный заказ)') ? 'selected' : ''; ?>>Кастомный пошив (индивидуальный заказ)</option>
                                </optgroup>
                            </select>
                            <div class="invalid-feedback">Пожалуйста, выберите вид спецодежды</div>
                        </div>

                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="size" class="form-label">
                                    <i class="bi bi-rulers me-2"></i>Предполагаемый размер *
                                </label>
                                <select class="form-select" id="size" name="size" required>
                                    <option value="" disabled <?php echo empty($form_values['size']) ? 'selected' : ''; ?>>Выберите размер</option>
                                    <option value="XS (44-46)" <?php echo (isset($form_values['size']) && $form_values['size'] === 'XS (44-46)') ? 'selected' : ''; ?>>XS (44-46)</option>
                                    <option value="S (48-50)" <?php echo (isset($form_values['size']) && $form_values['size'] === 'S (48-50)') ? 'selected' : ''; ?>>S (48-50)</option>
                                    <option value="M (52-54)" <?php echo (isset($form_values['size']) && $form_values['size'] === 'M (52-54)') ? 'selected' : ''; ?>>M (52-54)</option>
                                    <option value="L (56-58)" <?php echo (isset($form_values['size']) && $form_values['size'] === 'L (56-58)') ? 'selected' : ''; ?>>L (56-58)</option>
                                    <option value="XL (60-62)" <?php echo (isset($form_values['size']) && $form_values['size'] === 'XL (60-62)') ? 'selected' : ''; ?>>XL (60-62)</option>
                                    <option value="XXL (64-66)" <?php echo (isset($form_values['size']) && $form_values['size'] === 'XXL (64-66)') ? 'selected' : ''; ?>>XXL (64-66)</option>
                                    <option value="Индивидуальный" <?php echo (isset($form_values['size']) && $form_values['size'] === 'Индивидуальный') ? 'selected' : ''; ?>>Индивидуальный (требуются мерки)</option>
                                    <option value="Уточнить" <?php echo (isset($form_values['size']) && $form_values['size'] === 'Уточнить') ? 'selected' : ''; ?>>Требуется помощь в подборе</option>
                                </select>
                                <div class="invalid-feedback">Укажите размер</div>
                            </div>
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">
                                    <i class="bi bi-boxes me-2"></i>Количество (штук) *
                                </label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="1000" value="<?php echo isset($form_values['quantity']) ? (int)$form_values['quantity'] : 1; ?>" required>
                                <div class="invalid-feedback">Укажите количество от 1 до 1000</div>
                            </div>
                        </div>

                        
                        <div class="mb-4">
                            <label for="customer_name" class="form-label">
                                <i class="bi bi-person-circle me-2"></i>ФИО Заказчика *
                            </label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Иванов Иван Иванович" value="<?php echo htmlspecialchars($form_values['customer_name'] ?? ''); ?>" required>
                            <div class="invalid-feedback">Введите ваше полное имя</div>
                        </div>

                        
                        <div class="mb-4">
                            <label for="phone" class="form-label">
                                <i class="bi bi-telephone-fill me-2"></i>Телефон *
                            </label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="+7 (___) ___-__-__" value="<?php echo htmlspecialchars($form_values['phone'] ?? ''); ?>" required>
                            <div class="invalid-feedback">Введите корректный номер телефона</div>
                            <div class="form-text">Для оперативной связи и подтверждения заказа</div>
                        </div>

                        
                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt-fill me-2"></i>Адрес доставки *
                            </label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Город, улица, дом, квартира / пункт выдачи СДЭК" required><?php echo htmlspecialchars($form_values['address'] ?? ''); ?></textarea>
                            <div class="invalid-feedback">Укажите адрес доставки</div>
                        </div>

                        
                        <div class="mb-5">
                            <label for="comment" class="form-label">
                                <i class="bi bi-chat-text me-2"></i>Комментарий к заказу
                            </label>
                            <textarea class="form-control" id="comment" name="comment" rows="2" placeholder="Дополнительные пожелания, цвет, материалы, срочность..."><?php echo htmlspecialchars($form_values['comment'] ?? ''); ?></textarea>
                        </div>

                        
                        <button type="submit" class="btn-order">
                            <i class="bi bi-check-circle me-2"></i>Оформить заказ
                        </button>

                        <p class="text-muted text-center mt-4 mb-0 small">
                            <i class="bi bi-shield-lock"></i> Нажимая кнопку, вы соглашаетесь с политикой обработки персональных данных
                        </p>
                    </form>

                    <!-- Подсказка по размерам -->
                    <div class="size-guide">
                        <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Как подобрать размер?</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="bi bi-arrow-right-circle me-2 text-primary"></i> Рост: XS (до 165), S (165-172), M (172-182), L (182-188), XL (188-195)</li>
                                    <li class="mt-2"><i class="bi bi-arrow-right-circle me-2 text-primary"></i> Грудь: XS (86-92), S (94-100), M (102-108), L (110-116), XL (118-124)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="bi bi-arrow-right-circle me-2 text-primary"></i> Талия: XS (70-76), S (78-84), M (86-92), L (94-102), XL (104-112)</li>
                                    <li class="mt-2"><i class="bi bi-arrow-right-circle me-2 text-primary"></i> Не подходит? Выберите "Индивидуальный"</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Бейдж входа в админку -->
    <div class="admin-badge">
        <a href="admin/login.php">
            <i class="bi bi-shield-lock-fill"></i>
            <span>Администратор</span>
        </a>
    </div>
</main>

<!-- Футер (общий) -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="text-white"><i class="bi bi-water"></i> DiveGear</h5>
                <p>Производство спецодежды для дайвинга с 2010 года. Сертифицировано ISO 9001.</p>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="text-white">Навигация</h6>
                <ul class="list-unstyled">
                    <li><a href="about.html">О нас</a></li>
                    <li><a href="services.html">Услуги</a></li>
                    <li><a href="portfolio.html">Проекты</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="text-white">Контакты</h6>
                <ul class="list-unstyled">
                    <li><i class="bi bi-geo-alt"></i> г. Москва, ул. Промышленная, 12</li>
                    <li><i class="bi bi-telephone"></i> +7 (495) 123-45-67</li>
                    <li><i class="bi bi-envelope"></i> info@divegear.ru</li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="text-white">Мы в соцсетях</h6>
                <a href="#" class="me-2"><i class="bi bi-telegram fs-4"></i></a>
                <a href="#"><i class="bi bi-vk fs-4"></i></a>
            </div>
        </div>
        <hr class="bg-secondary">
        <div class="text-center pt-3">
            <p class="small mb-0">© 2025 DiveGear. Все права защищены. Копирование материалов запрещено.</p>
        </div>
    </div>
</footer>

<script>
    document.getElementById('phone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
        e.target.value = !x[2] ? x[1] : '+7 (' + x[2] + ') ' + x[3] + (x[4] ? '-' + x[4] : '') + (x[5] ? '-' + x[5] : '');
    });

    function generateOrderNumber() {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const random = Math.random().toString(36).substring(2, 8).toUpperCase();
        return `DG-${year}${month}${day}-${random}`;
    }

    function saveOrder(orderData) {
        let orders = JSON.parse(localStorage.getItem('divegear_orders')) || [];
        orders.push(orderData);
        localStorage.setItem('divegear_orders', JSON.stringify(orders));
    }

    function resetForm() {
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('orderForm').style.display = 'block';
        document.getElementById('orderFormElement').reset();
        document.getElementById('orderFormElement').classList.remove('was-validated');
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require_once '../config/database.php';

if (!isAdminAuthenticated()) {
    header('Location: index.php');
    exit();
}

$stats = [];

$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$stats['total_orders'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'new'");
$stats['new_orders'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'processing'");
$stats['processing_orders'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'");
$stats['completed_orders'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages WHERE status = 'new'");
$stats['new_messages'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");
$recentOrders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления | DiveGear Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --dg-primary: #0a3142;
            --dg-secondary: #1f6e8a;
            --dg-accent: #3a9bbf;
        }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f7fb;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--dg-primary) 0%, #0e2a38 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
        }
        
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            margin: 0.2rem 0;
            border-radius: 0 30px 30px 0;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.8rem;
            width: 24px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            transition: transform 0.3s;
            border: 1px solid rgba(31,110,138,0.1);
            display: flex;
            align-items: center;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(31,110,138,0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(145deg, #eef7fb, #e0ecf2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dg-secondary);
            font-size: 1.8rem;
            margin-right: 1rem;
        }
        
        .badge-new { background: #ffc107; color: #000; padding: 0.5rem 1rem; border-radius: 50px; }
        .badge-processing { background: #17a2b8; color: white; padding: 0.5rem 1rem; border-radius: 50px; }
        .badge-completed { background: #28a745; color: white; padding: 0.5rem 1rem; border-radius: 50px; }
        .badge-cancelled { background: #dc3545; color: white; padding: 0.5rem 1rem; border-radius: 50px; }
        
        .table-container {
            background: white;
            border-radius: 25px;
            padding: 1.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            margin-top: 2rem;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="text-white mb-1"><i class="bi bi-water"></i> DiveGear</h4>
            <p class="text-white-50 small mb-0">Панель управления</p>
        </div>
        
        <div class="mt-3">
            <div class="px-3 mb-3">
                <div class="d-flex align-items-center text-white-50">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span><?php echo htmlspecialchars($_SESSION['admin_name'] ?? $_SESSION['admin_username']); ?></span>
                </div>
            </div>
            
            <nav class="nav flex-column">
                <a class="nav-link active" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>Дашборд
                </a>
                <a class="nav-link" href="logout.php">
                    <i class="bi bi-box-arrow-right"></i>Выход
                </a>
            </nav>
        </div>
    </div>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: var(--dg-primary);">Дашборд</h2>
            <div>
                <span class="text-muted me-3">
                    <i class="bi bi-calendar3"></i> 
                    <?php echo date('d.m.Y H:i'); ?>
                </span>
                <a href="logout.php" class="logout-btn">
                    <i class="bi bi-box-arrow-right me-2"></i>Выйти
                </a>
            </div>
        </div>
        
        <!-- Статистика -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Всего заказов</h6>
                        <h2 class="fw-bold mb-0"><?php echo $stats['total_orders']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Новые</h6>
                        <h2 class="fw-bold mb-0"><?php echo $stats['new_orders']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">В обработке</h6>
                        <h2 class="fw-bold mb-0"><?php echo $stats['processing_orders']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Выполнено</h6>
                        <h2 class="fw-bold mb-0"><?php echo $stats['completed_orders']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Последние заказы -->
        <div class="table-container">
            <h5 class="fw-bold mb-4">Последние заказы</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>№ заказа</th>
                            <th>Дата</th>
                            <th>Клиент</th>
                            <th>Товар</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['clothing_type']); ?></td>
                            <td>
                                <?php if ($order['total_price']): ?>
                                <?php echo number_format($order['total_price'], 0, '', ' '); ?> ₽
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusClass = [
                                    'new' => 'badge-new',
                                    'processing' => 'badge-processing',
                                    'completed' => 'badge-completed',
                                    'cancelled' => 'badge-cancelled'
                                ][$order['status']] ?? 'badge-secondary';
                                $statusText = [
                                    'new' => 'Новый',
                                    'processing' => 'В обработке',
                                    'completed' => 'Выполнен',
                                    'cancelled' => 'Отменен'
                                ][$order['status']] ?? $order['status'];
                                ?>
                                <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </td>
                            <td>
                                <a href="order_view.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">Нет заказов</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="orders.php" class="btn btn-outline-primary rounded-pill px-4">
                    Все заказы <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
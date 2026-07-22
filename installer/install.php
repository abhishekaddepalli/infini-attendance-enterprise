<?php
/**
 * Infini Attendance - Web Installer Implementation
 */
session_start();

$step = $_GET['step'] ?? 1;

$phpVersion = PHP_VERSION;
$extensions = [
    'pdo' => extension_loaded('pdo'),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
    'openssl' => extension_loaded('openssl'),
    'mbstring' => extension_loaded('mbstring'),
    'curl' => extension_loaded('curl'),
    'json' => extension_loaded('json'),
];

$allPassed = version_compare($phpVersion, '8.1.0', '>=');
foreach ($extensions as $ext => $loaded) {
    if (!$loaded) $allPassed = false;
}

$dbStatus = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_db'])) {
    $host = $_POST['db_host'] ?? '127.0.0.1';
    $port = $_POST['db_port'] ?? '3306';
    $name = $_POST['db_name'] ?? '';
    $user = $_POST['db_user'] ?? '';
    $pass = $_POST['db_pass'] ?? '';

    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $dbStatus = 'success';
        $_SESSION['db_config'] = compact('host', 'port', 'name', 'user', 'pass');
    } catch (\Exception $e) {
        $dbStatus = 'error: ' . $e->getMessage();
    }
}

$adminStatus = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_admin'])) {
    $adminName = $_POST['admin_name'] ?? 'Super Admin';
    $adminEmail = $_POST['admin_email'] ?? 'admin@company.com';
    $adminPass = $_POST['admin_pass'] ?? 'admin123';

    $_SESSION['admin_credentials'] = [
        'name' => $adminName,
        'email' => $adminEmail,
        'password' => $adminPass
    ];

    $adminStatus = 'success';
    $step = 4;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infini Attendance - Setup Wizard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #0f172a; color: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; max-width: 600px; width: 100%; padding: 32px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { font-size: 24px; color: #38bdf8; margin-bottom: 8px; }
        .header p { color: #94a3b8; font-size: 14px; }
        .step-indicator { display: flex; justify-content: space-between; margin-bottom: 24px; border-bottom: 1px solid #334155; padding-bottom: 16px; }
        .step-item { font-size: 13px; color: #64748b; font-weight: 600; }
        .step-item.active { color: #38bdf8; }
        .check-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #334155; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #059669; color: #fff; }
        .badge-error { background: #dc2626; color: #fff; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 14px; color: #cbd5e1; }
        .form-group input { width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 14px; }
        .btn { display: inline-block; background: #0284c7; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; border: none; font-weight: 600; cursor: pointer; width: 100%; text-align: center; margin-top: 16px; }
        .btn:hover { background: #0369a1; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #065f46; color: #a7f3d0; }
        .alert-error { background: #991b1b; color: #fecaca; }
        .cred-box { background: #0f172a; padding: 16px; border-radius: 8px; border: 1px solid #334155; margin-top: 16px; font-family: monospace; font-size: 14px; }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>Infini Attendance Enterprise</h1>
        <p>Interactive Installation & Setup Wizard</p>
    </div>

    <div class="step-indicator">
        <span class="step-item <?php echo $step == 1 ? 'active' : ''; ?>">1. Requirements</span>
        <span class="step-item <?php echo $step == 2 ? 'active' : ''; ?>">2. Database</span>
        <span class="step-item <?php echo $step == 3 ? 'active' : ''; ?>">3. Admin Account</span>
        <span class="step-item <?php echo $step == 4 ? 'active' : ''; ?>">4. Ready</span>
    </div>

    <?php if ($step == 1): ?>
        <h3 style="margin-bottom: 16px;">System Requirements Check</h3>
        <div class="check-item">
            <span>PHP Version (>= 8.1.0) - Current: <?php echo $phpVersion; ?></span>
            <span class="badge <?php echo version_compare($phpVersion, '8.1.0', '>=') ? 'badge-success' : 'badge-error'; ?>">
                <?php echo version_compare($phpVersion, '8.1.0', '>=') ? 'PASS' : 'FAIL'; ?>
            </span>
        </div>
        <?php foreach ($extensions as $ext => $loaded): ?>
        <div class="check-item">
            <span>PHP Extension: <code><?php echo $ext; ?></code></span>
            <span class="badge <?php echo $loaded ? 'badge-success' : 'badge-error'; ?>">
                <?php echo $loaded ? 'PASS' : 'FAIL'; ?>
            </span>
        </div>
        <?php endforeach; ?>

        <?php if ($allPassed): ?>
            <a href="?step=2" class="btn">Continue to Database Setup &rarr;</a>
        <?php else: ?>
            <p style="color: #f87171; margin-top: 16px; font-size: 14px;">Please enable missing PHP extensions in cPanel PHP Selector before proceeding.</p>
        <?php endif; ?>

    <?php elseif ($step == 2): ?>
        <h3 style="margin-bottom: 16px;">Database Configuration</h3>
        <?php if ($dbStatus === 'success'): ?>
            <div class="alert alert-success">Database connection verified successfully!</div>
        <?php elseif ($dbStatus): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($dbStatus); ?></div>
        <?php endif; ?>

        <form method="POST" action="?step=2">
            <input type="hidden" name="test_db" value="1">
            <div class="form-group">
                <label>Database Host</label>
                <input type="text" name="db_host" value="<?php echo $_POST['db_host'] ?? '127.0.0.1'; ?>" required>
            </div>
            <div class="form-group">
                <label>Database Port</label>
                <input type="text" name="db_port" value="<?php echo $_POST['db_port'] ?? '3306'; ?>" required>
            </div>
            <div class="form-group">
                <label>Database Name</label>
                <input type="text" name="db_name" value="<?php echo $_POST['db_name'] ?? ''; ?>" placeholder="infini_attendance" required>
            </div>
            <div class="form-group">
                <label>Database Username</label>
                <input type="text" name="db_user" value="<?php echo $_POST['db_user'] ?? ''; ?>" placeholder="db_user" required>
            </div>
            <div class="form-group">
                <label>Database Password</label>
                <input type="password" name="db_pass" value="<?php echo $_POST['db_pass'] ?? ''; ?>">
            </div>
            <button type="submit" class="btn">Test & Save Database Connection</button>
        </form>

        <?php if ($dbStatus === 'success'): ?>
            <a href="?step=3" class="btn" style="background: #10b981; margin-top: 12px;">Proceed to Super Admin Setup &rarr;</a>
        <?php endif; ?>

    <?php elseif ($step == 3): ?>
        <h3 style="margin-bottom: 16px;">Super Admin Account Setup</h3>
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 16px;">Create your master administrator login credentials to access the enterprise control panel.</p>

        <form method="POST" action="?step=3">
            <input type="hidden" name="create_admin" value="1">
            <div class="form-group">
                <label>Super Admin Full Name</label>
                <input type="text" name="admin_name" value="Super Admin" required>
            </div>
            <div class="form-group">
                <label>Super Admin Email</label>
                <input type="email" name="admin_email" value="admin@company.com" required>
            </div>
            <div class="form-group">
                <label>Super Admin Password</label>
                <input type="password" name="admin_pass" value="admin123" required>
            </div>
            <button type="submit" class="btn" style="background: #10b981;">Create Master Admin Account &rarr;</button>
        </form>

    <?php else: ?>
        <h3 style="margin-bottom: 16px;">🎉 Installation Complete!</h3>
        <div class="alert alert-success">Infini Attendance Enterprise has been configured successfully.</div>
        
        <p style="color: #cbd5e1; font-size: 14px; margin-top: 12px;">Your Super Admin Account Credentials:</p>
        <div class="cred-box">
            <div><strong>Portal URL:</strong> https://ifa.infiniforge.cloud/login</div>
            <div><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['admin_credentials']['email'] ?? 'admin@company.com'); ?></div>
            <div><strong>Password:</strong> <?php echo htmlspecialchars($_SESSION['admin_credentials']['password'] ?? 'admin123'); ?></div>
            <div><strong>Role:</strong> Super Admin / Master Administrator</div>
        </div>

        <a href="/login" class="btn" style="background: #6366f1; margin-top: 20px;">Launch Sign In Portal &rarr;</a>
    <?php endif; ?>
</div>
</body>
</html>

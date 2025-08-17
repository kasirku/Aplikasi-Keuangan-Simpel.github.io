<?php
session_start();

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: navigation.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config/database.php';
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                
                header('Location: navigation.php');
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan sistem!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Keuangan Simpel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .logo {
            margin-bottom: 30px;
        }

        .logo i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 15px;
        }

        .logo h1 {
            color: #333;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .logo p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-error {
            background: #fee;
            color: #c53030;
            border: 1px solid #fed7d7;
        }

        .alert-success {
            background: #f0fff4;
            color: #2f855a;
            border: 1px solid #c6f6d5;
        }

        .footer-links {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e1e5e9;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #764ba2;
        }

        .demo-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }

        .demo-info h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .demo-info p {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 5px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .logo h1 {
                font-size: 1.5rem;
            }
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="logo">
            <i class="fas fa-wallet"></i>
            <h1>Aplikasi Keuangan</h1>
            <p>Login untuk melanjutkan</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Masukkan username" required 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        

        <div class="footer-links">
            <a href="#" id="prank-register-btn"><i class="fas fa-user-plus"></i> Daftar</a>
            <a href="https://wa.me/6282137175042?text=Halo%20Admin%2C%20saya%20lupa%20password%20akun%20aplikasi%20keuangan%20saya." target="_blank"><i class="fab fa-whatsapp"></i> Lupa Password?</a>
        </div>

        <div class="copyright" style="margin-top: 20px; font-size: 0.8rem; color: #666;">
            &copy; 2025 Aplikasi Keuangan Simpel - Dibuat oleh arbain studio dan masih dalam masa percobaan
        </div>

        <div id="prank-message" style="display:none; margin-top: 20px; padding: 15px; border-radius: 10px; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <p style="color: #333; font-weight: 600; margin-bottom: 15px;">Pastikan Anda Memiliki Saldo Di Rekening Untuk Melanjutkan.</p>
            <button id="prank-continue-btn" class="btn-login" style="width: auto; padding: 10px 20px; font-size: 1rem;">Lanjut</button>
        </div>
        <div id="prank-image" style="display:none; text-align: center; margin-top: 20px; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <p style="font-size: 2rem; font-weight: bold; color: #dc3545;">TAPI BOONG...</p>
            <button id="prank-reset-btn" class="btn-login" style="width: auto; padding: 10px 20px; font-size: 1rem; margin-top: 20px;">Kembali Login</button>
        </div>
    </div>

    <script>
        // Add some interactive effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Prank script
        document.getElementById('prank-register-btn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('prank-message').style.display = 'block';
            document.querySelector('.footer-links').style.display = 'none';
        });

        document.getElementById('prank-continue-btn').addEventListener('click', function() {
            document.getElementById('prank-message').style.display = 'none';
            document.getElementById('prank-image').style.display = 'block';
        });

        document.getElementById('prank-reset-btn').addEventListener('click', function() {
            document.getElementById('prank-image').style.display = 'none';
            document.querySelector('.footer-links').style.display = 'block';
        });
    </script>
</body>
</html>

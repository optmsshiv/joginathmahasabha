<?php
require_once __DIR__ . '/includes/auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    if ($user && $pass) {
        if (loginAdmin($user, $pass)) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login — Jogi/Nath MahaSabha</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --saffron:#E8660A;--deep:#1C1209;--mid:#3D2609;
  --warm-bg:#FDF8F2;--white:#fff;
  --border:#EDE0CC;--text:#2C1A08;--muted:#9E8870;
  --font-d:'Cormorant Garamond',serif;--font-b:'DM Sans',sans-serif;
}
body{font-family:var(--font-b);background:var(--deep);min-height:100vh;
     display:flex;align-items:center;justify-content:center;padding:20px;position:relative;overflow:hidden;}
body::before{
  content:'';position:absolute;top:-80px;right:-80px;width:400px;height:400px;
  background-image:
    radial-gradient(circle,transparent 49px,var(--saffron) 50px,var(--saffron) 50.5px,transparent 51px),
    radial-gradient(circle,transparent 99px,var(--saffron) 100px,var(--saffron) 100.5px,transparent 101px),
    radial-gradient(circle,transparent 149px,var(--saffron) 150px,var(--saffron) 150.5px,transparent 151px);
  background-repeat:no-repeat;background-position:center;background-size:400px 400px;
  opacity:0.07;pointer-events:none;animation:spin 80s linear infinite;
}
@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}

.login-card{
  background:var(--white);border-radius:20px;
  width:100%;max-width:420px;overflow:hidden;
  box-shadow:0 24px 64px rgba(0,0,0,0.4);
  animation:slideUp .5s ease both;
}
@keyframes slideUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}

.card-head{
  background:linear-gradient(135deg,var(--deep) 0%,var(--mid) 100%);
  padding:32px 32px 28px;text-align:center;
}
.card-head .logo-circle{
  width:72px;height:72px;border-radius:50%;
  background:rgba(232,102,10,.15);border:2px solid rgba(232,102,10,.3);
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 14px;font-size:28px;
}
.card-head h1{font-family:var(--font-d);font-size:26px;font-weight:600;color:#F5E6C8;margin:0 0 6px;}
.card-head p{font-size:12px;color:rgba(245,230,200,.55);letter-spacing:.08em;text-transform:uppercase;}

.card-body{padding:32px;}

.alert-error{
  background:#FEF2F2;border:1px solid #FECACA;border-radius:10px;
  padding:12px 16px;margin-bottom:20px;
  color:#B91C1C;font-size:13px;display:flex;align-items:center;gap:8px;
}

.form-group{margin-bottom:18px;}
.form-group label{display:block;font-size:12px;font-weight:500;color:var(--muted);
  text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px;}
.input-wrap{position:relative;}
.input-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);
  color:var(--muted);font-size:14px;}
.form-group input{
  width:100%;padding:12px 14px 12px 40px;
  border:1px solid var(--border);border-radius:10px;
  font-family:var(--font-b);font-size:14px;color:var(--text);
  background:var(--warm-bg);transition:border .2s,box-shadow .2s;outline:none;
}
.form-group input:focus{border-color:var(--saffron);box-shadow:0 0 0 3px rgba(232,102,10,.1);}

.btn-login{
  width:100%;padding:14px;border:none;border-radius:10px;
  background:var(--saffron);color:var(--white);
  font-family:var(--font-b);font-size:14px;font-weight:500;
  cursor:pointer;transition:background .2s,transform .15s;
  letter-spacing:.03em;
}
.btn-login:hover{background:#C9530A;transform:translateY(-1px);}
.btn-login:active{transform:translateY(0);}

.back-link{display:block;text-align:center;margin-top:18px;font-size:13px;
  color:var(--muted);text-decoration:none;transition:color .2s;}
.back-link:hover{color:var(--saffron);}
</style>
</head>
<body>
<div class="login-card">
  <div class="card-head">
    <div class="logo-circle">🪔</div>
    <h1>Admin Panel</h1>
    <p>Jogi/Nath MahaSabha</p>
  </div>
  <div class="card-body">
    <?php if ($error): ?>
    <div class="alert-error"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
      <div class="form-group">
        <label>Username</label>
        <div class="input-wrap">
          <i class="fas fa-user"></i>
          <input type="text" name="username" placeholder="Enter username"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
        </div>
      </div>
      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" placeholder="Enter password" required>
        </div>
      </div>
      <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt"></i> &nbsp;Login</button>
    </form>

    <a href="../index.html" class="back-link"><i class="fas fa-arrow-left"></i> Back to Website</a>
  </div>
</div>
</body>
</html>
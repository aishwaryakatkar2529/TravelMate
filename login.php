<?php
include 'config.php';
$error = '';
if($_POST){
  $email = $conn->real_escape_string($_POST['email']);
  $pass  = MD5($_POST['password']);
  $res   = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$pass'");
  if($res->num_rows > 0){
    $u = $res->fetch_assoc();
    $_SESSION['user_id']   = $u['id'];
    $_SESSION['user_name'] = $u['name'];
    $_SESSION['role']      = $u['role'];
    header("Location: " . ($u['role']=='admin' ? 'admin/dashboard.php' : 'index.php'));
    exit;
  } else {
    $error = "Invalid email or password!";
  }
}
?>
<!DOCTYPE html>
<html><head>
<title>Login — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  body{margin:0;font-family:'DM Sans',sans-serif;background:#0d1117;color:#e6edf3;display:flex;justify-content:center;align-items:center;min-height:100vh;}
  .box{background:#161b22;padding:48px 40px;border-radius:12px;border:1px solid #21262d;width:380px;}
  h2{font-family:'Playfair Display',serif;font-size:1.8rem;color:#C9A84C;margin-bottom:8px;}
  p.sub{color:#8b949e;font-size:0.9rem;margin-bottom:30px;}
  input{width:100%;padding:12px 16px;background:#0d1117;border:1px solid #30363d;color:#e6edf3;border-radius:6px;font-size:0.95rem;margin-bottom:16px;outline:none;font-family:'DM Sans',sans-serif;}
  input:focus{border-color:#C9A84C;}
  button{width:100%;padding:13px;background:#C9A84C;color:#000;border:none;border-radius:6px;font-weight:700;font-size:1rem;cursor:pointer;}
  .err{background:#2d1b1b;color:#f85149;border:1px solid #f85149;padding:10px;border-radius:6px;margin-bottom:16px;font-size:0.88rem;}
  a{color:#C9A84C;}
  .link{text-align:center;margin-top:18px;font-size:0.88rem;color:#8b949e;}
</style>
</head><body>
<div class="box">
  <h2>Welcome Back</h2>
  <p class="sub">Login to access your bookings</p>
  <?php if($error): ?><div class="err"><?= $error ?></div><?php endif; ?>
  <form method="POST">
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login →</button>
  </form>
  <div class="link">New here? <a href="register.php">Create account</a></div>
  <div class="link" style="margin-top:8px;"><a href="index.php">← Back to Home</a></div>
</div>
</body></html>

<?php
include 'config.php';
$error = $success = '';
if($_POST){
  $name  = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);
  $pass  = MD5($_POST['password']);
  $check = $conn->query("SELECT id FROM users WHERE email='$email'");
  if($check->num_rows > 0){
    $error = "Email already registered!";
  } else {
    $conn->query("INSERT INTO users (name,email,password) VALUES ('$name','$email','$pass')");
    $success = "Registered! <a href='login.php'>Login now</a>";
  }
}
?>
<!DOCTYPE html>
<html><head>
<title>Register — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  body{margin:0;font-family:'DM Sans',sans-serif;background:#0d1117;color:#e6edf3;display:flex;justify-content:center;align-items:center;min-height:100vh;}
  .box{background:#161b22;padding:48px 40px;border-radius:12px;border:1px solid #21262d;width:380px;}
  h2{font-family:'Playfair Display',serif;font-size:1.8rem;color:#C9A84C;margin-bottom:8px;}
  p.sub{color:#8b949e;font-size:0.9rem;margin-bottom:30px;}
  input{width:100%;padding:12px 16px;background:#0d1117;border:1px solid #30363d;color:#e6edf3;border-radius:6px;font-size:0.95rem;margin-bottom:16px;outline:none;font-family:'DM Sans',sans-serif;}
  input:focus{border-color:#C9A84C;}
  button{width:100%;padding:13px;background:#C9A84C;color:#000;border:none;border-radius:6px;font-weight:700;font-size:1rem;cursor:pointer;}
  .msg{padding:10px;border-radius:6px;margin-bottom:16px;font-size:0.88rem;}
  .err{background:#2d1b1b;color:#f85149;border:1px solid #f85149;}
  .suc{background:#1b2d1f;color:#3fb950;border:1px solid #3fb950;}
  a{color:#C9A84C;}
  .link{text-align:center;margin-top:18px;font-size:0.88rem;color:#8b949e;}
</style>
</head><body>
<div class="box">
  <h2>Create Account</h2>
  <p class="sub">Join Travel Mate to book your dream trips</p>
  <?php if($error): ?><div class="msg err"><?= $error ?></div><?php endif; ?>
  <?php if($success): ?><div class="msg suc"><?= $success ?></div><?php endif; ?>
  <form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password (min 6 chars)" required minlength="6">
    <button type="submit">Register →</button>
  </form>
  <div class="link">Already have an account? <a href="login.php">Login</a></div>
  <div class="link" style="margin-top:8px;"><a href="index.php">← Back to Home</a></div>
</div>
</body></html>

<?php
include '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){ header("Location: ../login.php"); exit; }
if(isset($_GET['delete'])){
  $did = intval($_GET['delete']);
  $conn->query("DELETE FROM users WHERE id=$did AND role='user'");
  header("Location: users.php"); exit;
}
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html><head>
<title>Users — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  :root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);display:flex;}
  .sidebar{width:230px;min-height:100vh;background:#0a0e14;border-right:1px solid #21262d;padding:28px 0;flex-shrink:0;}
  .sidebar .logo{font-family:'Playfair Display',serif;color:var(--gold);font-size:1.3rem;padding:0 24px 28px;display:block;border-bottom:1px solid #21262d;margin-bottom:16px;}
  .sidebar a{display:block;padding:12px 24px;color:var(--muted);text-decoration:none;font-size:0.9rem;}
  .sidebar a:hover,.sidebar a.active{color:#fff;background:#161b22;border-left:3px solid var(--gold);}
  .main{flex:1;padding:36px 40px;}
  h1{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;margin-bottom:28px;}
  h1 span{color:var(--gold);}
  table{width:100%;border-collapse:collapse;background:var(--card);border-radius:10px;overflow:hidden;border:1px solid #21262d;}
  th{padding:14px 16px;text-align:left;background:#0d1117;color:var(--muted);font-size:0.78rem;letter-spacing:0.05em;}
  td{padding:13px 16px;border-top:1px solid #21262d;font-size:0.88rem;color:#c9d1d9;}
  .role{padding:3px 10px;border-radius:20px;font-size:0.76rem;}
  .role.admin{background:#1a2a40;color:#58a6ff;}
  .role.user{background:#1b2d1f;color:#3fb950;}
  .del-btn{color:#f85149;text-decoration:none;font-size:0.83rem;border:1px solid #f85149;padding:4px 12px;border-radius:4px;}
</style>
</head><body>
<div class="sidebar">
  <span class="logo">✈ Admin</span>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="packages.php">🗺️ Packages</a>
  <a href="add-package.php">➕ Add Package</a>
  <a href="bookings.php">📋 Bookings</a>
  <a href="users.php" class="active">👥 Users</a>
  <a href="../index.php">🌐 View Site</a>
  <a href="../logout.php">🚪 Logout</a>
</div>
<div class="main">
  <h1>Manage <span>Users</span></h1>
  <table>
    <tr><th>ID</th><th>NAME</th><th>EMAIL</th><th>ROLE</th><th>JOINED</th><th>ACTION</th></tr>
    <?php while($u = $users->fetch_assoc()): ?>
    <tr>
      <td>#<?= $u['id'] ?></td>
      <td><?= htmlspecialchars($u['name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><span class="role <?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
      <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
      <td>
        <?php if($u['role'] != 'admin'): ?>
          <a href="?delete=<?= $u['id'] ?>" class="del-btn" onclick="return confirm('Delete this user?')">Delete</a>
        <?php else: ?> — <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body></html>

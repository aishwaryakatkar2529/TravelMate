<?php
include '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
  header("Location: ../login.php"); exit;
}
$total_pkg  = $conn->query("SELECT COUNT(*) as c FROM packages")->fetch_assoc()['c'];
$total_user = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='user'")->fetch_assoc()['c'];
$total_book = $conn->query("SELECT COUNT(*) as c FROM bookings")->fetch_assoc()['c'];
$revenue    = $conn->query("SELECT SUM(total_price) as r FROM bookings WHERE status!='cancelled'")->fetch_assoc()['r'];
$recent     = $conn->query("SELECT b.*,u.name as uname,p.title FROM bookings b JOIN users u ON b.user_id=u.id JOIN packages p ON b.package_id=p.id ORDER BY b.created_at DESC LIMIT 8");
?>
<!DOCTYPE html>
<html><head>
<title>Admin Dashboard — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  :root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);display:flex;}
  .sidebar{width:230px;min-height:100vh;background:#0a0e14;border-right:1px solid #21262d;padding:28px 0;flex-shrink:0;position:sticky;top:0;}
  .sidebar .logo{font-family:'Playfair Display',serif;color:var(--gold);font-size:1.3rem;padding:0 24px 28px;display:block;border-bottom:1px solid #21262d;margin-bottom:16px;}
  .sidebar a{display:flex;align-items:center;gap:10px;padding:12px 24px;color:var(--muted);text-decoration:none;font-size:0.9rem;transition:all 0.2s;}
  .sidebar a:hover,.sidebar a.active{color:#fff;background:#161b22;border-left:3px solid var(--gold);}
  .main{flex:1;padding:36px 40px;}
  h1{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;margin-bottom:28px;}
  h1 span{color:var(--gold);}
  .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:40px;}
  .stat-card{background:var(--card);border-radius:10px;border:1px solid #21262d;padding:24px 20px;}
  .stat-icon{font-size:1.8rem;margin-bottom:10px;}
  .stat-val{font-size:1.9rem;font-weight:700;color:var(--gold);}
  .stat-label{color:var(--muted);font-size:0.82rem;margin-top:4px;}
  table{width:100%;border-collapse:collapse;background:var(--card);border-radius:10px;overflow:hidden;border:1px solid #21262d;}
  th{padding:14px 16px;text-align:left;background:#0d1117;color:var(--muted);font-size:0.8rem;letter-spacing:0.05em;}
  td{padding:14px 16px;border-top:1px solid #21262d;font-size:0.88rem;color:#c9d1d9;}
  .status{padding:3px 12px;border-radius:20px;font-size:0.76rem;font-weight:600;}
  .status.pending{background:#2d2316;color:#e3b341;}
  .status.confirmed{background:#1b2d1f;color:#3fb950;}
  .status.cancelled{background:#2d1b1b;color:#f85149;}
  h2{font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;margin-bottom:16px;}
</style>
</head><body>
<div class="sidebar">
  <span class="logo">✈ Admin</span>
  <a href="dashboard.php" class="active">📊 Dashboard</a>
  <a href="packages.php">🗺️ Packages</a>
  <a href="add-package.php">➕ Add Package</a>
  <a href="bookings.php">📋 Bookings</a>
  <a href="users.php">👥 Users</a>
  <a href="../index.php">🌐 View Site</a>
  <a href="../logout.php">🚪 Logout</a>
</div>
<div class="main">
  <h1>Admin <span>Dashboard</span></h1>
  <div class="stats">
    <div class="stat-card"><div class="stat-icon">🗺️</div><div class="stat-val"><?= $total_pkg ?></div><div class="stat-label">Total Packages</div></div>
    <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-val"><?= $total_user ?></div><div class="stat-label">Registered Users</div></div>
    <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-val"><?= $total_book ?></div><div class="stat-label">Total Bookings</div></div>
    <div class="stat-card"><div class="stat-icon">💰</div><div class="stat-val">₹<?= number_format($revenue ?? 0) ?></div><div class="stat-label">Total Revenue</div></div>
  </div>
  <h2>Recent Bookings</h2>
  <table>
    <tr><th>USER</th><th>PACKAGE</th><th>DATE</th><th>PERSONS</th><th>TOTAL</th><th>STATUS</th></tr>
    <?php while($b = $recent->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($b['uname']) ?></td>
      <td><?= htmlspecialchars($b['title']) ?></td>
      <td><?= $b['travel_date'] ?></td>
      <td><?= $b['persons'] ?></td>
      <td>₹<?= number_format($b['total_price']) ?></td>
      <td><span class="status <?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body></html>

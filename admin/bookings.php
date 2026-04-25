<?php
include '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){ header("Location: ../login.php"); exit; }
if(isset($_GET['status']) && isset($_GET['id'])){
  $bid = intval($_GET['id']);
  $st  = $conn->real_escape_string($_GET['status']);
  $conn->query("UPDATE bookings SET status='$st' WHERE id=$bid");
  header("Location: bookings.php"); exit;
}
$bookings = $conn->query("SELECT b.*,u.name as uname,u.email,p.title FROM bookings b JOIN users u ON b.user_id=u.id JOIN packages p ON b.package_id=p.id ORDER BY b.created_at DESC");
?>
<!DOCTYPE html>
<html><head>
<title>Bookings — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  :root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);display:flex;}
  .sidebar{width:230px;min-height:100vh;background:#0a0e14;border-right:1px solid #21262d;padding:28px 0;flex-shrink:0;}
  .sidebar .logo{font-family:'Playfair Display',serif;color:var(--gold);font-size:1.3rem;padding:0 24px 28px;display:block;border-bottom:1px solid #21262d;margin-bottom:16px;}
  .sidebar a{display:block;padding:12px 24px;color:var(--muted);text-decoration:none;font-size:0.9rem;}
  .sidebar a:hover,.sidebar a.active{color:#fff;background:#161b22;border-left:3px solid var(--gold);}
  .main{flex:1;padding:36px 40px;overflow-x:auto;}
  h1{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;margin-bottom:28px;}
  h1 span{color:var(--gold);}
  table{width:100%;border-collapse:collapse;background:var(--card);border-radius:10px;overflow:hidden;border:1px solid #21262d;min-width:900px;}
  th{padding:14px 16px;text-align:left;background:#0d1117;color:var(--muted);font-size:0.78rem;letter-spacing:0.05em;}
  td{padding:13px 16px;border-top:1px solid #21262d;font-size:0.86rem;color:#c9d1d9;}
  .status{padding:3px 10px;border-radius:20px;font-size:0.76rem;font-weight:600;}
  .status.pending{background:#2d2316;color:#e3b341;}
  .status.confirmed{background:#1b2d1f;color:#3fb950;}
  .status.cancelled{background:#2d1b1b;color:#f85149;}
  select{background:#0d1117;color:var(--text);border:1px solid #30363d;padding:4px 8px;border-radius:4px;font-size:0.82rem;}
</style>
</head><body>
<div class="sidebar">
  <span class="logo">✈ Admin</span>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="packages.php">🗺️ Packages</a>
  <a href="add-package.php">➕ Add Package</a>
  <a href="bookings.php" class="active">📋 Bookings</a>
  <a href="users.php">👥 Users</a>
  <a href="../index.php">🌐 View Site</a>
  <a href="../logout.php">🚪 Logout</a>
</div>
<div class="main">
  <h1>Manage <span>Bookings</span></h1>
  <table>
    <tr><th>USER</th><th>EMAIL</th><th>PACKAGE</th><th>TRAVEL DATE</th><th>PERSONS</th><th>TOTAL</th><th>STATUS</th><th>ACTION</th></tr>
    <?php while($b = $bookings->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($b['uname']) ?></td>
      <td><?= htmlspecialchars($b['email']) ?></td>
      <td><?= htmlspecialchars($b['title']) ?></td>
      <td><?= $b['travel_date'] ?></td>
      <td><?= $b['persons'] ?></td>
      <td>₹<?= number_format($b['total_price']) ?></td>
      <td><span class="status <?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
      <td>
        <select onchange="location='?id=<?= $b['id'] ?>&status='+this.value">
          <option value="pending"   <?= $b['status']=='pending'?'selected':'' ?>>Pending</option>
          <option value="confirmed" <?= $b['status']=='confirmed'?'selected':'' ?>>Confirmed</option>
          <option value="cancelled" <?= $b['status']=='cancelled'?'selected':'' ?>>Cancelled</option>
        </select>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body></html>

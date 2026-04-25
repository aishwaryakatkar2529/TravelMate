<?php
include '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){ header("Location: ../login.php"); exit; }
if(isset($_GET['delete'])){
  $did = intval($_GET['delete']);
  $conn->query("DELETE FROM packages WHERE id=$did");
  header("Location: packages.php"); exit;
}
$pkgs = $conn->query("SELECT * FROM packages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html><head>
<title>Manage Packages — Admin</title>
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
  h1{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;margin-bottom:8px;}
  h1 span{color:var(--gold);}
  .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;}
  .add-btn{background:var(--gold);color:#000;padding:10px 22px;border-radius:6px;text-decoration:none;font-weight:700;font-size:0.88rem;}
  table{width:100%;border-collapse:collapse;background:var(--card);border-radius:10px;overflow:hidden;border:1px solid #21262d;}
  th{padding:14px 16px;text-align:left;background:#0d1117;color:var(--muted);font-size:0.78rem;letter-spacing:0.05em;}
  td{padding:14px 16px;border-top:1px solid #21262d;font-size:0.88rem;color:#c9d1d9;}
  td img{width:60px;height:42px;object-fit:cover;border-radius:4px;}
  .edit-btn{color:var(--gold);text-decoration:none;margin-right:12px;font-size:0.83rem;border:1px solid var(--gold);padding:4px 12px;border-radius:4px;}
  .del-btn{color:#f85149;text-decoration:none;font-size:0.83rem;border:1px solid #f85149;padding:4px 12px;border-radius:4px;}
</style>
</head><body>
<div class="sidebar">
  <span class="logo">✈ Admin</span>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="packages.php" class="active">🗺️ Packages</a>
  <a href="add-package.php">➕ Add Package</a>
  <a href="bookings.php">📋 Bookings</a>
  <a href="users.php">👥 Users</a>
  <a href="../index.php">🌐 View Site</a>
  <a href="../logout.php">🚪 Logout</a>
</div>
<div class="main">
  <div class="top">
    <h1>Manage <span>Packages</span></h1>
    <a href="add-package.php" class="add-btn">+ Add Package</a>
  </div>
  <table>
    <tr><th>PHOTO</th><th>TITLE</th><th>DESTINATION</th><th>DURATION</th><th>PRICE</th><th>ACTIONS</th></tr>
    <?php while($p = $pkgs->fetch_assoc()):
      $img = $p['photo'] ? '../uploads/packages/'.$p['photo'] : 'https://source.unsplash.com/120x80/?travel';
    ?>
    <tr>
      <td><img src="<?= htmlspecialchars($img) ?>" alt=""></td>
      <td><?= htmlspecialchars($p['title']) ?></td>
      <td><?= htmlspecialchars($p['destination']) ?></td>
      <td><?= htmlspecialchars($p['duration']) ?></td>
      <td>₹<?= number_format($p['price']) ?></td>
      <td>
        <a href="edit-package.php?id=<?= $p['id'] ?>" class="edit-btn">Edit</a>
        <a href="?delete=<?= $p['id'] ?>" class="del-btn" onclick="return confirm('Delete this package?')">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
</body></html>

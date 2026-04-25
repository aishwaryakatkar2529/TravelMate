<?php
include 'config.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
$uid = $_SESSION['user_id'];

// Cancel booking
if(isset($_GET['cancel'])){
  $bid = intval($_GET['cancel']);
  $conn->query("UPDATE bookings SET status='cancelled' WHERE id=$bid AND user_id=$uid");
  header("Location: my-bookings.php"); exit;
}

$bookings = $conn->query("
  SELECT b.*, p.title, p.destination, p.duration, p.photo
  FROM bookings b
  JOIN packages p ON b.package_id = p.id
  WHERE b.user_id = $uid
  ORDER BY b.created_at DESC
");
?>
<!DOCTYPE html>
<html><head>
<title>My Bookings — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  :root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
  nav{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:rgba(13,17,23,0.95);border-bottom:1px solid #21262d;}
  .logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--gold);text-decoration:none;}
  .nav-links a{color:var(--text);text-decoration:none;margin-left:24px;font-size:0.9rem;}
  .container{max-width:960px;margin:0 auto;padding:48px 24px;}
  h1{font-family:'Playfair Display',serif;font-size:2rem;color:#fff;margin-bottom:32px;}
  h1 span{color:var(--gold);}
  .booking-card{background:var(--card);border-radius:10px;border:1px solid #21262d;margin-bottom:20px;display:flex;overflow:hidden;}
  .booking-card img{width:160px;height:120px;object-fit:cover;flex-shrink:0;}
  .bc-body{padding:20px 24px;flex:1;}
  .bc-title{font-family:'Playfair Display',serif;font-size:1.1rem;color:#fff;margin-bottom:6px;}
  .bc-meta{color:var(--muted);font-size:0.83rem;margin-bottom:10px;}
  .bc-meta span{margin-right:16px;}
  .bc-footer{display:flex;justify-content:space-between;align-items:center;margin-top:10px;}
  .status{padding:4px 14px;border-radius:20px;font-size:0.78rem;font-weight:600;}
  .status.pending{background:#2d2316;color:#e3b341;border:1px solid #e3b341;}
  .status.confirmed{background:#1b2d1f;color:#3fb950;border:1px solid #3fb950;}
  .status.cancelled{background:#2d1b1b;color:#f85149;border:1px solid #f85149;}
  .price-info{color:var(--gold);font-weight:700;}
  .cancel-btn{color:#f85149;text-decoration:none;font-size:0.83rem;border:1px solid #f85149;padding:4px 12px;border-radius:4px;}
  .empty{text-align:center;padding:60px;color:var(--muted);}
  .empty a{color:var(--gold);}
</style>
</head><body>
<nav>
  <a href="index.php" class="logo">✈ Travel Mate</a>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="packages.php">Packages</a>
    <a href="logout.php">Logout (<?= $_SESSION['user_name'] ?>)</a>
  </div>
</nav>

<div class="container">
  <h1>My <span>Bookings</span></h1>
  <?php if($bookings->num_rows == 0): ?>
    <div class="empty">
      <p style="font-size:3rem;margin-bottom:12px;">🗺️</p>
      <p>No bookings yet! <a href="packages.php">Explore packages</a></p>
    </div>
  <?php else: ?>
    <?php while($b = $bookings->fetch_assoc()):
      $img = $b['photo'] ? UPLOAD_URL.$b['photo'] : 'https://picsum.photos/seed/'.$b['package_id'].'/300/200';
    ?>
    <div class="booking-card">
      <img src="<?= htmlspecialchars($img) ?>" alt="">
      <div class="bc-body">
        <div class="bc-title"><?= htmlspecialchars($b['title']) ?></div>
        <div class="bc-meta">
          <span>📍 <?= htmlspecialchars($b['destination']) ?></span>
          <span>📅 <?= $b['travel_date'] ?></span>
          <span>👥 <?= $b['persons'] ?> person(s)</span>
          <span>⏱ <?= htmlspecialchars($b['duration']) ?></span>
        </div>
        <div class="bc-footer">
          <div>
            <span class="status <?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span>
            &nbsp;&nbsp;<span class="price-info">Total: ₹<?= number_format($b['total_price']) ?></span>
          </div>
          <?php if($b['status'] == 'pending'): ?>
            <a href="?cancel=<?= $b['id'] ?>" class="cancel-btn" onclick="return confirm('Cancel this booking?')">Cancel</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
</body></html>

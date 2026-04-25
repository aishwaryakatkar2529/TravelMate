<?php include 'config.php'; ?>
<!DOCTYPE html>
<html><head>
<title>Tour Packages — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  :root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
  nav{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:rgba(13,17,23,0.95);border-bottom:1px solid #21262d;position:sticky;top:0;z-index:10;}
  .logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--gold);text-decoration:none;}
  .nav-links a{color:var(--text);text-decoration:none;margin-left:24px;font-size:0.9rem;}
  .nav-links a:hover{color:var(--gold);}
  .btn-nav{background:var(--gold);color:#000;padding:7px 20px;border-radius:4px;font-weight:600;}
  .page-header{padding:60px 60px 30px;background:linear-gradient(180deg,#161b22 0%,var(--dark) 100%);}
  .page-header h1{font-family:'Playfair Display',serif;font-size:2.4rem;color:#fff;}
  .page-header h1 span{color:var(--gold);}
  .page-header p{color:var(--muted);margin-top:8px;}
  .container{padding:40px 60px 80px;}
  .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;}
  .card{background:var(--card);border-radius:10px;overflow:hidden;border:1px solid #21262d;transition:transform 0.25s,border-color 0.25s;}
  .card:hover{transform:translateY(-6px);border-color:var(--gold);}
  .card img{width:100%;height:200px;object-fit:cover;}
  .card-body{padding:20px;}
  .card-body h3{font-family:'Playfair Display',serif;font-size:1.15rem;color:#fff;margin-bottom:8px;}
  .meta{display:flex;gap:14px;color:var(--muted);font-size:0.82rem;margin-bottom:10px;}
  .price{color:var(--gold);font-size:1.25rem;font-weight:700;margin-bottom:14px;}
  .price span{color:var(--muted);font-size:0.78rem;font-weight:400;}
  .btn{display:inline-block;background:var(--gold);color:#000;padding:9px 22px;border-radius:4px;font-weight:600;text-decoration:none;font-size:0.86rem;}
</style>
</head><body>
<nav>
  <a href="index.php" class="logo">✈ Travel Mate</a>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="packages.php">Packages</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="my-bookings.php">My Bookings</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php" class="btn-nav">Register</a>
    <?php endif; ?>
  </div>
</nav>

<div class="page-header">
  <h1>All Tour <span>Packages</span></h1>
  <p>Choose from our curated collection of travel experiences</p>
</div>

<div class="container">
  <div class="grid">
    <?php
    $res = $conn->query("SELECT * FROM packages ORDER BY created_at DESC");
    while($p = $res->fetch_assoc()):
        $img = $p['photo'] ? UPLOAD_URL.$p['photo'] : 'https://picsum.photos/seed/'.$p['id'].'/600/400';
    ?>
    <div class="card">
      <img src="<?= htmlspecialchars($img) ?>" alt="">
      <div class="card-body">
        <h3><?= htmlspecialchars($p['title']) ?></h3>
        <div class="meta">
          <span>📍 <?= htmlspecialchars($p['destination']) ?></span>
          <span>⏱ <?= htmlspecialchars($p['duration']) ?></span>
          <span>👥 Max <?= $p['max_persons'] ?></span>
        </div>
        <div class="price">₹<?= number_format($p['price']) ?> <span>/ person</span></div>
        <a href="package-detail.php?id=<?= $p['id'] ?>" class="btn">View Details →</a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
</body></html>

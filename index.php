<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Travel Mate — Explore the World</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --gold: #C9A84C;
    --dark: #0d1117;
    --card-bg: #161b22;
    --text: #e6edf3;
    --muted: #8b949e;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--text); }

  /* NAV */
  nav {
    display:flex; justify-content:space-between; align-items:center;
    padding:18px 60px; background:rgba(13,17,23,0.95);
    position:sticky; top:0; z-index:100;
    border-bottom:1px solid #21262d;
  }
  .logo { font-family:'Playfair Display',serif; font-size:1.6rem; color:var(--gold); }
  .nav-links a {
    color:var(--text); text-decoration:none; margin-left:28px;
    font-size:0.9rem; letter-spacing:0.05em;
    transition:color 0.2s;
  }
  .nav-links a:hover { color:var(--gold); }
  .btn-nav {
    background:var(--gold); color:#000; padding:8px 22px;
    border-radius:4px; font-weight:600; text-decoration:none;
    margin-left:28px; font-size:0.88rem;
  }

  /* HERO */
  .hero {
    height:92vh; display:flex; flex-direction:column;
    justify-content:center; align-items:center; text-align:center;
    background: linear-gradient(135deg, #0d1117 0%, #1a1f2e 50%, #0d1117 100%);
    position:relative; overflow:hidden;
  }
  .hero::before {
    content:''; position:absolute; inset:0;
    background:radial-gradient(ellipse at 60% 40%, rgba(201,168,76,0.08) 0%, transparent 70%);
  }
  .hero h1 {
    font-family:'Playfair Display',serif;
    font-size:clamp(2.5rem, 6vw, 5rem);
    color:#fff; line-height:1.1; margin-bottom:18px;
  }
  .hero h1 span { color:var(--gold); }
  .hero p { font-size:1.1rem; color:var(--muted); max-width:500px; margin-bottom:36px; }
  .hero-btns { display:flex; gap:16px; }
  .btn-primary {
    background:var(--gold); color:#000; padding:14px 34px;
    border-radius:4px; font-weight:700; text-decoration:none;
    font-size:1rem; transition:opacity 0.2s;
  }
  .btn-primary:hover { opacity:0.88; }
  .btn-outline {
    border:1px solid var(--gold); color:var(--gold); padding:14px 34px;
    border-radius:4px; font-weight:600; text-decoration:none;
    font-size:1rem; transition:background 0.2s;
  }
  .btn-outline:hover { background:rgba(201,168,76,0.1); }

  /* PACKAGES */
  .section { padding:80px 60px; }
  .section-title {
    font-family:'Playfair Display',serif;
    font-size:2.2rem; color:#fff; margin-bottom:8px;
  }
  .section-title span { color:var(--gold); }
  .section-sub { color:var(--muted); margin-bottom:48px; }
  .grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));
    gap:24px;
  }
  .card {
    background:var(--card-bg); border-radius:10px;
    overflow:hidden; border:1px solid #21262d;
    transition:transform 0.25s, border-color 0.25s;
  }
  .card:hover { transform:translateY(-6px); border-color:var(--gold); }
  .card img {
    width:100%; height:200px; object-fit:cover;
  }
  .card-body { padding:20px; }
  .card-body h3 {
    font-family:'Playfair Display',serif;
    font-size:1.2rem; color:#fff; margin-bottom:8px;
  }
  .card-meta { display:flex; gap:14px; color:var(--muted); font-size:0.83rem; margin-bottom:12px; }
  .card-price { color:var(--gold); font-size:1.3rem; font-weight:700; margin-bottom:16px; }
  .card-price span { color:var(--muted); font-size:0.8rem; font-weight:400; }
  .btn-card {
    display:inline-block; background:var(--gold); color:#000;
    padding:9px 22px; border-radius:4px; font-weight:600;
    text-decoration:none; font-size:0.88rem;
  }

  /* FOOTER */
  footer {
    text-align:center; padding:36px;
    border-top:1px solid #21262d; color:var(--muted);
    font-size:0.88rem;
  }
</style>
</head>
<body>

<nav>
  <div class="logo">✈ Travel Mate</div>
  <div class="nav-links">
    <a href="index.php">Home</a>
    <a href="packages.php">Packages</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="my-bookings.php">My Bookings</a>
      <a href="logout.php">Logout</a>
      <?php if($_SESSION['role']=='admin'): ?>
        <a href="admin/dashboard.php" class="btn-nav">Admin Panel</a>
      <?php endif; ?>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php" class="btn-nav">Register</a>
    <?php endif; ?>
  </div>
</nav>

<div class="hero">
  <h1>Discover Your Next<br><span>Adventure</span></h1>
  <p>Curated tour packages across India and beyond. Book your dream trip today.</p>
  <div class="hero-btns">
    <a href="packages.php" class="btn-primary">Explore Packages</a>
    <a href="register.php" class="btn-outline">Get Started</a>
  </div>
</div>

<div class="section">
  <h2 class="section-title">Featured <span>Packages</span></h2>
  <p class="section-sub">Handpicked destinations for unforgettable experiences</p>
  <div class="grid">
    <?php
    $res = $conn->query("SELECT * FROM packages ORDER BY created_at DESC LIMIT 6");
    while($p = $res->fetch_assoc()):
        $img = $p['photo'] ? UPLOAD_URL.$p['photo'] : 'https://picsum.photos/seed/'.$p['id'].'/600/400';
    ?>
    <div class="card">
      <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
      <div class="card-body">
        <h3><?= htmlspecialchars($p['title']) ?></h3>
        <div class="card-meta">
          <span>📍 <?= htmlspecialchars($p['destination']) ?></span>
          <span>⏱ <?= htmlspecialchars($p['duration']) ?></span>
        </div>
        <div class="card-price">₹<?= number_format($p['price']) ?> <span>/ person</span></div>
        <a href="package-detail.php?id=<?= $p['id'] ?>" class="btn-card">View Details →</a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>

<footer>
  &copy; <?= date('Y') ?> Travel Mate. All rights reserved.
</footer>
</body>
</html>

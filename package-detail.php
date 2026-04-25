<?php
include 'config.php';
$id = intval($_GET['id'] ?? 0);
$p  = $conn->query("SELECT * FROM packages WHERE id=$id")->fetch_assoc();
if(!$p){ header("Location: packages.php"); exit; }

$booked = $success = $error = '';
if($_POST && isset($_SESSION['user_id'])){
  $uid   = $_SESSION['user_id'];
  $date  = $conn->real_escape_string($_POST['travel_date']);
  $pers  = intval($_POST['persons']);
  $total = $p['price'] * $pers;
  $conn->query("INSERT INTO bookings (user_id,package_id,travel_date,persons,total_price)
                VALUES ($uid,$id,'$date',$pers,$total)");
  $success = "Booking confirmed! ✅ Check <a href='my-bookings.php'>My Bookings</a>";
} elseif($_POST){
  $error = "Please login to book this package.";
}
$img = $p['photo'] ? UPLOAD_URL.$p['photo'] : 'https://picsum.photos/seed/'.$p['id'].'/1200/600';
?>
<!DOCTYPE html>
<html><head>
<title><?= htmlspecialchars($p['title']) ?> — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  :root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
  nav{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:rgba(13,17,23,0.95);border-bottom:1px solid #21262d;}
  .logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--gold);text-decoration:none;}
<?php
include 'config.php';
$id = intval($_GET['id'] ?? 0);
$p  = $conn->query("SELECT * FROM packages WHERE id=$id")->fetch_assoc();
if(!$p){ header("Location: packages.php"); exit; }

$success = $error = '';
$booking_id = 0;

if($_POST && isset($_SESSION['user_id'])){
  $uid   = $_SESSION['user_id'];
  $date  = $conn->real_escape_string($_POST['travel_date']);
  $pers  = intval($_POST['persons']);
  $total = $p['price'] * $pers;
  $conn->query("INSERT INTO bookings (user_id,package_id,travel_date,persons,total_price,status)
                VALUES ($uid,$id,'$date',$pers,$total,'confirmed')");
  $booking_id = $conn->insert_id;
  header("Location: receipt.php?id=$booking_id");
  exit;
} elseif($_POST){
  $error = "Please login to book this package.";
}

// User details
$user = [];
if(isset($_SESSION['user_id'])){
  $uid  = $_SESSION['user_id'];
  $user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
}

$dest_encoded = urlencode($p['destination']);
$img = $p['photo'] ? UPLOAD_URL.$p['photo'] : "https://source.unsplash.com/1200x600/?".urlencode(explode(',', $p['destination'])[0]).",travel";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($p['title']) ?> — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);}
nav{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:rgba(13,17,23,0.97);border-bottom:1px solid #21262d;position:sticky;top:0;z-index:100;}
.logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--gold);text-decoration:none;}
.nav-links a{color:var(--text);text-decoration:none;margin-left:24px;font-size:0.9rem;}
.nav-links a:hover{color:var(--gold);}
.hero{position:relative;height:480px;overflow:hidden;}
.hero img{width:100%;height:100%;object-fit:cover;filter:brightness(0.45);}
.hero-overlay{position:absolute;inset:0;display:flex;flex-direction:column;justify-content:flex-end;padding:48px 80px;background:linear-gradient(to top,rgba(13,17,23,1) 0%,transparent 60%);}
.hero-overlay h1{font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);color:#fff;margin-bottom:16px;}
.badges{display:flex;gap:12px;flex-wrap:wrap;}
.badge{background:rgba(201,168,76,0.15);color:var(--gold);padding:6px 18px;border-radius:20px;font-size:0.83rem;border:1px solid rgba(201,168,76,0.3);}
.layout{display:grid;grid-template-columns:1fr 380px;gap:32px;max-width:1200px;margin:0 auto;padding:48px 24px 80px;}
.section-title{font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #21262d;}
.desc{color:#c9d1d9;line-height:1.85;margin-bottom:36px;font-size:0.96rem;}
.highlights{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:36px;}
.hl-card{background:var(--card);border:1px solid #21262d;border-radius:10px;padding:20px;text-align:center;}
.hl-icon{font-size:2rem;margin-bottom:8px;}
.hl-val{font-size:1.2rem;font-weight:700;color:var(--gold);}
.hl-label{color:var(--muted);font-size:0.78rem;margin-top:4px;}
.includes-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:36px;}
.include-item{background:var(--card);border:1px solid #21262d;border-radius:8px;padding:12px 16px;display:flex;align-items:center;gap:10px;font-size:0.86rem;color:#c9d1d9;}
.gallery{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:36px;}
.gallery img{width:100%;height:130px;object-fit:cover;border-radius:8px;filter:brightness(0.8);transition:filter 0.3s;}
.gallery img:hover{filter:brightness(1);}
.book-card{background:var(--card);border:1px solid #21262d;border-radius:12px;padding:28px;position:sticky;top:90px;height:fit-content;}
.book-card h3{font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;margin-bottom:6px;}
.price-big{font-size:2rem;font-weight:700;color:var(--gold);margin-bottom:4px;}
.price-big span{color:var(--muted);font-size:0.85rem;font-weight:400;}
.divider{border:none;border-top:1px solid #21262d;margin:18px 0;}
.user-info-box{background:#0d1117;border-radius:8px;padding:14px 16px;margin-bottom:16px;border:1px solid #21262d;}
.user-info-box p{font-size:0.83rem;color:var(--muted);margin-bottom:4px;}
.user-info-box strong{color:#fff;font-size:0.92rem;}
label{display:block;color:var(--muted);font-size:0.78rem;margin-bottom:6px;letter-spacing:0.04em;text-transform:uppercase;}
input,select{width:100%;padding:11px 14px;background:#0d1117;border:1px solid #30363d;color:var(--text);border-radius:6px;font-family:'DM Sans',sans-serif;font-size:0.92rem;margin-bottom:14px;outline:none;}
input:focus,select:focus{border-color:var(--gold);}
.total-row{display:flex;justify-content:space-between;align-items:center;background:#0d1117;padding:14px 16px;border-radius:6px;margin-bottom:16px;border:1px solid #21262d;}
.total-row span:first-child{color:var(--muted);font-size:0.88rem;}
.total-row span:last-child{color:var(--gold);font-weight:700;font-size:1.2rem;}
.btn-book{width:100%;padding:14px;background:var(--gold);color:#000;border:none;border-radius:6px;font-weight:700;font-size:1rem;cursor:pointer;transition:opacity 0.2s;}
.btn-book:hover{opacity:0.88;}
.msg{padding:12px;border-radius:6px;margin-bottom:14px;font-size:0.88rem;}
.err{background:#2d1b1b;color:#f85149;border:1px solid #f85149;}
.login-prompt{text-align:center;padding:20px;background:#0d1117;border-radius:8px;color:var(--muted);font-size:0.88rem;border:1px solid #21262d;}
.login-prompt a{color:var(--gold);font-weight:600;text-decoration:none;}
.safe-badge{display:flex;justify-content:center;gap:16px;margin-top:14px;font-size:0.78rem;color:var(--muted);}
@media(max-width:768px){.layout{grid-template-columns:1fr;}.hero-overlay{padding:24px;}.highlights{grid-template-columns:1fr 1fr;}.includes-grid{grid-template-columns:1fr;}nav{padding:14px 20px;}}
</style>
</head>
<body>
<nav>
  <a href="index.php" class="logo">✈ Travel Mate</a>
  <div class="nav-links">
    <a href="packages.php">All Packages</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="my-bookings.php">My Bookings</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </div>
</nav>

<div class="hero">
  <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
  <div class="hero-overlay">
    <h1><?= htmlspecialchars($p['title']) ?></h1>
    <div class="badges">
      <span class="badge">📍 <?= htmlspecialchars($p['destination']) ?></span>
      <span class="badge">⏱ <?= htmlspecialchars($p['duration']) ?></span>
      <span class="badge">👥 Max <?= $p['max_persons'] ?> Persons</span>
      <span class="badge">⭐ Top Rated</span>
    </div>
  </div>
</div>

<div class="layout">
  <div>
    <div class="highlights">
      <div class="hl-card"><div class="hl-icon">📅</div><div class="hl-val"><?= explode(' ', $p['duration'])[0] ?></div><div class="hl-label">Days</div></div>
      <div class="hl-card"><div class="hl-icon">👥</div><div class="hl-val"><?= $p['max_persons'] ?></div><div class="hl-label">Max People</div></div>
      <div class="hl-card"><div class="hl-icon">💰</div><div class="hl-val">₹<?= number_format($p['price']) ?></div><div class="hl-label">Per Person</div></div>
    </div>

    <h2 class="section-title">About This Trip</h2>
    <p class="desc"><?= nl2br(htmlspecialchars($p['description'])) ?></p>

    <?php if($p['includes']): ?>
    <h2 class="section-title">What's Included</h2>
    <div class="includes-grid">
      <?php
      $icons = ['🏨','🍳','🚌','🎯','👨‍✈️','🚢','✈️','📸','🎪','🏊'];
      $items = explode(',', $p['includes']);
      foreach($items as $i => $item):
      ?>
      <div class="include-item"><span><?= $icons[$i % count($icons)] ?></span><?= htmlspecialchars(trim($item)) ?></div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h2 class="section-title">Gallery</h2>
    <div class="gallery">
      <?php
      $dest = urlencode(explode(',', $p['destination'])[0]);
      $tags = [10,20,30,40,50,60];
      foreach($tags as $t):
     ?>
     <img src="https://picsum.photos/seed/<?= $p['id'].$t ?>/400/300" alt="">      <?php endforeach; ?>
    </div>
  </div>

  <!-- BOOKING CARD -->
  <div>
    <div class="book-card">
      <h3>Book This Trip</h3>
      <div class="price-big">₹<?= number_format($p['price']) ?> <span>/ person</span></div>
      <div style="color:var(--muted);font-size:0.8rem;margin-bottom:4px;">Inclusive of all taxes</div>
      <hr class="divider">

      <?php if($error): ?><div class="msg err"><?= $error ?></div><?php endif; ?>

      <?php if(isset($_SESSION['user_id'])): ?>

      <!-- USER INFO AUTO FETCHED -->
      <div class="user-info-box">
        <p>Booking for:</p>
        <strong><?= htmlspecialchars($user['name']) ?></strong><br>
        <span style="color:var(--muted);font-size:0.82rem;"><?= htmlspecialchars($user['email']) ?></span>
      </div>

      <form method="POST" id="bookForm">
        <label>Travel Date</label>
        <input type="date" name="travel_date" id="tdate" required min="<?= date('Y-m-d') ?>">

        <label>Number of Persons</label>
        <select name="persons" id="persons" onchange="updateTotal()">
          <?php for($i=1;$i<=$p['max_persons'];$i++): ?>
            <option value="<?= $i ?>"><?= $i ?> Person<?= $i>1?'s':'' ?></option>
          <?php endfor; ?>
        </select>

        <div class="total-row">
          <span>Total Amount</span>
          <span id="totalAmt">₹<?= number_format($p['price']) ?></span>
        </div>

        <button type="submit" class="btn-book">🎫 Confirm Booking →</button>
      </form>

      <?php else: ?>
      <div class="login-prompt">
        <p style="margin-bottom:12px;font-size:1rem;">Login to book this package</p>
        <a href="login.php" style="background:var(--gold);color:#000;padding:10px 28px;border-radius:6px;font-weight:700;display:inline-block;">Login Now</a>
        <p style="margin-top:12px;">New user? <a href="register.php">Register Free</a></p>
      </div>
      <?php endif; ?>

      <hr class="divider">
      <div class="safe-badge">
        <span>✅ Free Cancellation</span>
        <span>🔒 Secure Payment</span>
      </div>
    </div>
  </div>
</div>

<script>
const price = <?= $p['price'] ?>;
function updateTotal(){
  const p = parseInt(document.getElementById('persons').value);
  const t = price * p;
  document.getElementById('totalAmt').textContent = '₹' + t.toLocaleString('en-IN');
}
</script>
</body>
</html>

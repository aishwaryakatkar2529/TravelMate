<?php
include 'config.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }

$id = intval($_GET['id'] ?? 0);
$uid = $_SESSION['user_id'];

$b = $conn->query("
  SELECT b.*, u.name, u.email, p.title, p.destination, p.duration, p.includes, p.photo
  FROM bookings b
  JOIN users u ON b.user_id = u.id
  JOIN packages p ON b.package_id = p.id
  WHERE b.id = $id AND b.user_id = $uid
")->fetch_assoc();

if(!$b){ header("Location: my-bookings.php"); exit; }

$booking_ref = 'TM' . str_pad($b['id'], 6, '0', STR_PAD_LEFT);
$img = $b['photo'] ? UPLOAD_URL.$b['photo'] : 'https://picsum.photos/seed/'.$b['package_id'].'/800/400';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Receipt — Travel Mate</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);min-height:100vh;}
nav{display:flex;justify-content:space-between;align-items:center;padding:18px 60px;background:rgba(13,17,23,0.97);border-bottom:1px solid #21262d;}
.logo{font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--gold);text-decoration:none;}
.nav-links a{color:var(--text);text-decoration:none;margin-left:24px;font-size:0.9rem;}

.page{max-width:780px;margin:48px auto;padding:0 24px 80px;}

/* SUCCESS BANNER */
.success-banner{
  text-align:center;padding:32px;
  background:linear-gradient(135deg,#1b2d1f,#0d2016);
  border:1px solid #2ea043;border-radius:12px;
  margin-bottom:32px;
}
.check-icon{font-size:3.5rem;margin-bottom:12px;}
.success-banner h2{font-family:'Playfair Display',serif;font-size:1.8rem;color:#3fb950;margin-bottom:8px;}
.success-banner p{color:#8b949e;font-size:0.92rem;}
.ref-no{
  display:inline-block;
  background:#0d1117;color:var(--gold);
  padding:8px 24px;border-radius:6px;
  font-size:1.1rem;font-weight:700;
  border:1px solid var(--gold);
  margin-top:14px;letter-spacing:0.1em;
}

/* RECEIPT CARD */
.receipt{
  background:var(--card);
  border:1px solid #21262d;
  border-radius:12px;
  overflow:hidden;
}
.receipt-header{
  position:relative;height:200px;overflow:hidden;
}
.receipt-header img{width:100%;height:100%;object-fit:cover;filter:brightness(0.4);}
.receipt-header-text{
  position:absolute;inset:0;
  display:flex;flex-direction:column;
  justify-content:center;align-items:center;
  text-align:center;
}
.receipt-header-text h3{
  font-family:'Playfair Display',serif;
  font-size:1.6rem;color:#fff;margin-bottom:8px;
}
.receipt-header-text p{color:var(--gold);font-size:0.9rem;}

.receipt-body{padding:32px;}

.info-grid{
  display:grid;grid-template-columns:1fr 1fr;
  gap:20px;margin-bottom:28px;
}
.info-item label{
  display:block;color:var(--muted);
  font-size:0.75rem;text-transform:uppercase;
  letter-spacing:0.06em;margin-bottom:6px;
}
.info-item strong{color:#fff;font-size:0.96rem;}

.divider{border:none;border-top:1px solid #21262d;margin:24px 0;}

/* PRICE BREAKDOWN */
.price-table{width:100%;border-collapse:collapse;margin-bottom:20px;}
.price-table td{padding:10px 0;font-size:0.9rem;color:#c9d1d9;border-bottom:1px solid #21262d;}
.price-table td:last-child{text-align:right;color:#fff;}
.price-table tr:last-child td{border-bottom:none;font-size:1.1rem;font-weight:700;color:var(--gold);padding-top:16px;}
.price-table tr:last-child td:first-child{color:var(--gold);}

/* INCLUDES */
.includes-list{
  display:flex;flex-wrap:wrap;gap:8px;margin-bottom:28px;
}
.inc-tag{
  background:#0d1117;border:1px solid #30363d;
  color:#c9d1d9;padding:6px 14px;
  border-radius:20px;font-size:0.8rem;
}

/* STATUS */
.status-confirmed{
  display:inline-flex;align-items:center;gap:8px;
  background:#1b2d1f;color:#3fb950;
  padding:6px 16px;border-radius:20px;
  font-size:0.83rem;font-weight:600;
  border:1px solid #2ea043;
}

/* ACTIONS */
.actions{display:flex;gap:14px;margin-top:28px;}
.btn-print{
  flex:1;padding:13px;background:var(--gold);
  color:#000;border:none;border-radius:6px;
  font-weight:700;font-size:0.95rem;cursor:pointer;
}
.btn-home{
  flex:1;padding:13px;background:transparent;
  color:var(--text);border:1px solid #30363d;
  border-radius:6px;font-weight:600;font-size:0.95rem;
  text-decoration:none;text-align:center;
}
.btn-home:hover{border-color:var(--gold);color:var(--gold);}

/* FOOTER NOTE */
.receipt-footer{
  background:#0d1117;padding:18px 32px;
  border-top:1px solid #21262d;
  display:flex;justify-content:space-between;
  font-size:0.78rem;color:var(--muted);
}

@media print{
  nav,.actions{display:none !important;}
  body{background:#fff;color:#000;}
  .receipt{border:1px solid #ccc;}
  .success-banner{background:#e6ffe6;border-color:#2ea043;}
  .success-banner h2{color:#1a7a1a;}
  .ref-no{color:#b8860b;border-color:#b8860b;background:#fff;}
  .receipt-body{padding:20px;}
}

@media(max-width:600px){
  .info-grid{grid-template-columns:1fr;}
  nav{padding:14px 20px;}
  .actions{flex-direction:column;}
}
</style>
</head>
<body>

<nav>
  <a href="index.php" class="logo">✈ Travel Mate</a>
  <div class="nav-links">
    <a href="my-bookings.php">My Bookings</a>
    <a href="packages.php">Packages</a>
    <a href="logout.php">Logout</a>
  </div>
</nav>

<div class="page">

  <!-- SUCCESS -->
  <div class="success-banner">
    <div class="check-icon">✅</div>
    <h2>Booking Confirmed!</h2>
    <p>Your trip has been successfully booked. Have a wonderful journey!</p>
    <div class="ref-no"><?= $booking_ref ?></div>
  </div>

  <!-- RECEIPT -->
  <div class="receipt">
    <!-- HEADER WITH IMAGE -->
    <div class="receipt-header">
      <img src="<?= htmlspecialchars($img) ?>" alt="">
      <div class="receipt-header-text">
        <h3><?= htmlspecialchars($b['title']) ?></h3>
        <p>📍 <?= htmlspecialchars($b['destination']) ?></p>
      </div>
    </div>

    <div class="receipt-body">

      <!-- STATUS -->
      <div style="margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
        <span style="color:var(--muted);font-size:0.85rem;">Booking Reference: <strong style="color:var(--gold)"><?= $booking_ref ?></strong></span>
        <span class="status-confirmed">● Confirmed</span>
      </div>

      <hr class="divider" style="margin-top:0;">

      <!-- TRAVELLER & TRIP INFO -->
      <div class="info-grid">
        <div class="info-item">
          <label>Traveller Name</label>
          <strong><?= htmlspecialchars($b['name']) ?></strong>
        </div>
        <div class="info-item">
          <label>Email</label>
          <strong><?= htmlspecialchars($b['email']) ?></strong>
        </div>
        <div class="info-item">
          <label>Destination</label>
          <strong>📍 <?= htmlspecialchars($b['destination']) ?></strong>
        </div>
        <div class="info-item">
          <label>Duration</label>
          <strong>⏱ <?= htmlspecialchars($b['duration']) ?></strong>
        </div>
        <div class="info-item">
          <label>Travel Date</label>
          <strong>📅 <?= date('d M Y', strtotime($b['travel_date'])) ?></strong>
        </div>
        <div class="info-item">
          <label>Number of Persons</label>
          <strong>👥 <?= $b['persons'] ?> Person<?= $b['persons']>1?'s':'' ?></strong>
        </div>
        <div class="info-item">
          <label>Booking Date</label>
          <strong><?= date('d M Y, h:i A', strtotime($b['created_at'])) ?></strong>
        </div>
        <div class="info-item">
          <label>Booking Status</label>
          <strong style="color:#3fb950;">✅ Confirmed</strong>
        </div>
      </div>

      <hr class="divider">

      <!-- PRICE BREAKDOWN -->
      <h3 style="font-family:'Playfair Display',serif;color:#fff;margin-bottom:16px;">Price Breakdown</h3>
      <table class="price-table">
        <tr>
          <td>Package Price (per person)</td>
          <td>₹<?= number_format($b['total_price'] / $b['persons']) ?></td>
        </tr>
        <tr>
          <td>Number of Persons</td>
          <td><?= $b['persons'] ?></td>
        </tr>
        <tr>
          <td>Subtotal</td>
          <td>₹<?= number_format($b['total_price']) ?></td>
        </tr>
        <tr>
          <td>Taxes & Fees</td>
          <td>Included</td>
        </tr>
        <tr>
          <td>Total Amount Paid</td>
          <td>₹<?= number_format($b['total_price']) ?></td>
        </tr>
      </table>

      <hr class="divider">

      <!-- INCLUDES -->
      <?php if($b['includes']): ?>
      <h3 style="font-family:'Playfair Display',serif;color:#fff;margin-bottom:14px;">What's Included</h3>
      <div class="includes-list">
        <?php
        $icons = ['🏨','🍳','🚌','🎯','👨‍✈️','🚢','✈️','📸','🎪','🏊'];
        $items = explode(',', $b['includes']);
        foreach($items as $i => $item):
        ?>
        <span class="inc-tag"><?= $icons[$i%count($icons)] ?> <?= htmlspecialchars(trim($item)) ?></span>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- ACTIONS -->
      <div class="actions">
        <button class="btn-print" onclick="window.print()">🖨️ Print / Save Receipt</button>
        <a href="index.php" class="btn-home">🏠 Back to Home</a>
      </div>

    </div>

    <!-- FOOTER -->
    <div class="receipt-footer">
      <span>✈ Travel Mate</span>
      <span>Thank you for booking with us!</span>
      <span>Booking ID: <?= $booking_ref ?></span>
    </div>
  </div>

</div>
</body>
</html>

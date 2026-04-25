<?php
include '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){ header("Location: ../login.php"); exit; }
$id = intval($_GET['id'] ?? 0);
$p  = $conn->query("SELECT * FROM packages WHERE id=$id")->fetch_assoc();
if(!$p){ header("Location: packages.php"); exit; }
$success = '';
if($_POST){
  $title    = $conn->real_escape_string($_POST['title']);
  $desc     = $conn->real_escape_string($_POST['description']);
  $dest     = $conn->real_escape_string($_POST['destination']);
  $duration = $conn->real_escape_string($_POST['duration']);
  $price    = floatval($_POST['price']);
  $max      = intval($_POST['max_persons']);
  $includes = $conn->real_escape_string($_POST['includes']);
  $photo    = $p['photo'];

  if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    if(in_array(strtolower($ext), ['jpg','jpeg','png','webp'])){
      $fname = uniqid().'.'.$ext;
      move_uploaded_file($_FILES['photo']['tmp_name'], '../uploads/packages/'.$fname);
      $photo = $fname;
    }
  }
  $conn->query("UPDATE packages SET title='$title',description='$desc',destination='$dest',duration='$duration',price=$price,max_persons=$max,includes='$includes',photo='$photo' WHERE id=$id");
  $success = "Package updated!";
  $p = $conn->query("SELECT * FROM packages WHERE id=$id")->fetch_assoc();
}
?>
<!DOCTYPE html>
<html><head>
<title>Edit Package — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans&display=swap" rel="stylesheet">
<style>
  :root{--gold:#C9A84C;--dark:#0d1117;--card:#161b22;--text:#e6edf3;--muted:#8b949e;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--text);display:flex;}
  .sidebar{width:230px;min-height:100vh;background:#0a0e14;border-right:1px solid #21262d;padding:28px 0;flex-shrink:0;}
  .sidebar .logo{font-family:'Playfair Display',serif;color:var(--gold);font-size:1.3rem;padding:0 24px 28px;display:block;border-bottom:1px solid #21262d;margin-bottom:16px;}
  .sidebar a{display:block;padding:12px 24px;color:var(--muted);text-decoration:none;font-size:0.9rem;}
  .sidebar a:hover{color:#fff;background:#161b22;border-left:3px solid var(--gold);}
  .main{flex:1;padding:36px 40px;}
  h1{font-family:'Playfair Display',serif;font-size:1.8rem;color:#fff;margin-bottom:28px;}
  h1 span{color:var(--gold);}
  .form-card{background:var(--card);border-radius:10px;border:1px solid #21262d;padding:32px;max-width:700px;}
  .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  label{display:block;color:var(--muted);font-size:0.82rem;margin-bottom:6px;}
  input,textarea{width:100%;padding:11px 14px;background:#0d1117;border:1px solid #30363d;color:var(--text);border-radius:6px;font-family:'DM Sans',sans-serif;font-size:0.92rem;margin-bottom:18px;}
  input:focus,textarea:focus{outline:none;border-color:var(--gold);}
  textarea{min-height:90px;resize:vertical;}
  button{background:var(--gold);color:#000;border:none;padding:13px 32px;border-radius:6px;font-weight:700;font-size:0.95rem;cursor:pointer;}
  .suc{background:#1b2d1f;color:#3fb950;border:1px solid #3fb950;padding:10px 16px;border-radius:6px;margin-bottom:16px;font-size:0.88rem;}
  .cur-photo{width:120px;border-radius:6px;margin-bottom:12px;display:block;}
</style>
</head><body>
<div class="sidebar">
  <span class="logo">✈ Admin</span>
  <a href="dashboard.php">📊 Dashboard</a>
  <a href="packages.php">🗺️ Packages</a>
  <a href="add-package.php">➕ Add Package</a>
  <a href="bookings.php">📋 Bookings</a>
  <a href="users.php">👥 Users</a>
  <a href="../index.php">🌐 View Site</a>
  <a href="../logout.php">🚪 Logout</a>
</div>
<div class="main">
  <h1>Edit <span>Package</span></h1>
  <?php if($success): ?><div class="suc"><?= $success ?></div><?php endif; ?>
  <div class="form-card">
    <form method="POST" enctype="multipart/form-data">
      <label>Package Title</label>
      <input type="text" name="title" value="<?= htmlspecialchars($p['title']) ?>" required>
      <div class="form-row">
        <div>
          <label>Destination</label>
          <input type="text" name="destination" value="<?= htmlspecialchars($p['destination']) ?>" required>
        </div>
        <div>
          <label>Duration</label>
          <input type="text" name="duration" value="<?= htmlspecialchars($p['duration']) ?>" required>
        </div>
      </div>
      <div class="form-row">
        <div>
          <label>Price (₹)</label>
          <input type="number" name="price" value="<?= $p['price'] ?>" required>
        </div>
        <div>
          <label>Max Persons</label>
          <input type="number" name="max_persons" value="<?= $p['max_persons'] ?>">
        </div>
      </div>
      <label>Description</label>
      <textarea name="description"><?= htmlspecialchars($p['description']) ?></textarea>
      <label>What's Included</label>
      <textarea name="includes"><?= htmlspecialchars($p['includes']) ?></textarea>
      <label>Current Photo</label>
      <?php if($p['photo']): ?>
        <img src="../uploads/packages/<?= htmlspecialchars($p['photo']) ?>" class="cur-photo">
      <?php endif; ?>
      <label>Change Photo (optional)</label>
      <input type="file" name="photo" accept="image/*">
      <button type="submit">Update Package →</button>
    </form>
  </div>
</div>
</body></html>

<?php
require_once 'klase.php';
session_start();

$korisnikObj = new Korisnik();
$poruka = "";
$poruka_reg = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];

    if ($korisnikObj->login($email, $lozinka)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $poruka = "❌ Pogrešan email ili lozinka!";
    }
}

if (isset($_POST['registracija'])) {
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime']; 
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];

    if ($korisnikObj->registracija($ime, $prezime, $email, $lozinka)) {
        $poruka_reg = "✅ Uspešna registracija! Možete se prijaviti.";
    } else {
        $poruka_reg = "❌ Greška prilikom registracije. Email je možda već u upotrebi.";
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Student Planner - Prijava i Registracija</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fcf8f7; font-family: 'Quicksand', sans-serif; color: #5a4b4c; }
        .auth-container { max-width: 900px; margin: 80px auto; }
        .card { border: none; border-radius: 16px; box-shadow: 0 8px 24px rgba(186, 144, 148, 0.1); background-color: #ffffff; }
        .form-control { border: 1px solid #e8dedf; border-radius: 8px; background-color: #faf6f6; padding: 10px 15px; color: #5a4b4c; }
        .form-control:focus { border-color: #ba9094; box-shadow: 0 0 0 0.25rem rgba(186, 144, 148, 0.25); }
        .btn-pink { background-color: #ba9094; color: white; border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; width: 100%; }
        .btn-pink:hover { background-color: #a3797d; color: white; }
        .welcome-title { color: #8c6267; font-weight: 700; margin-bottom: 10px; }
        .divider { border-left: 1px solid #e8dedf; height: 100%; }
        @media (max-width: 768px) { .divider { display: none; } }
    </style>
</head>
<body>

<div class="container auth-container">
    <div class="card p-4 p-md-5">
        <div class="text-center mb-5">
            <h2 class="welcome-title">🎓 Student Planner</h2>
            <p class="text-muted">Dobrodošli u sistem za praćenje napretka i efikasnosti učenja</p>
        </div>
        
        <div class="row g-5">
            <div class="col-md-5">
                <h4 class="mb-3" style="color: #8c6267; font-weight: 600;">🔑 Prijava</h4>
                
                <?php if(!empty($poruka)): ?>
                    <div class="alert alert-danger p-2 small"><?php echo $poruka; ?></div>
                <?php endif; ?>

                <form action="index.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small">Email adresa</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Lozinka</label>
                        <input type="password" name="lozinka" class="form-control" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-pink mt-2">Prijavi se</button>
                </form>
            </div>

            <div class="col-md-2 d-flex justify-content-center align-items-center">
                <div class="divider"></div>
            </div>

            <div class="col-md-5">
                <h4 class="mb-3" style="color: #8c6267; font-weight: 600;">📝 Registracija</h4>
                
                <?php if(!empty($poruka_reg)): ?>
                    <div class="alert alert-info p-2 small"><?php echo $poruka_reg; ?></div>
                <?php endif; ?>

                <form action="index.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Ime</label>
                            <input type="text" name="ime" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small">Prezime</label>
                            <input type="text" name="prezime" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Email adresa</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Lozinka</label>
                        <input type="password" name="lozinka" class="form-control" required>
                    </div>
                    <button type="submit" name="registracija" class="btn btn-pink mt-2">Registruj se</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
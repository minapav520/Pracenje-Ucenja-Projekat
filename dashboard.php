<?php
require_once 'klase.php';
session_start();

if (!isset($_SESSION['korisnik_id'])) {
    header("Location: index.php");
    exit();
}

$napredakObj = new NapredakUcenja();

$edit_mod = false;
$edit_id = "";
$u_ucenik = "";
$u_predmet = "";
$u_zadatak = "";
$u_ocena = "";
$u_vreme = "";

if (isset($_POST['sacuvaj'])) {
    $podaci = [
        'ucenik' => $_POST['ucenik'],
        'predmet' => $_POST['predmet'],
        'zadatak' => $_POST['zadatak'],
        'ocena' => !empty($_POST['ocena']) ? $_POST['ocena'] : null,
        'vreme' => $_POST['vreme']
    ];

    if (!empty($_POST['zapis_id'])) {
        $napredakObj->update($_POST['zapis_id'], $podaci);
    } else {
        $napredakObj->create($podaci);
    }
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['izmeni'])) {
    $edit_mod = true;
    $edit_id = $_GET['izmeni'];
    $stari_podaci = $napredakObj->readOne($edit_id);
    
    if ($stari_podaci) {
        $u_ucenik = $stari_podaci['ucenik'];
        $u_predmet = $stari_podaci['predmet'];
        $u_zadatak = $stari_podaci['zadatak'];
        $u_ocena = $stari_podaci['ocena'];
        $u_vreme = $stari_podaci['vreme'];
    }
}

if (isset($_GET['obrisi'])) {
    $id = $_GET['obrisi'];
    $napredakObj->delete($id);
    header("Location: dashboard.php");
    exit();
}

$svi_zapisi = $napredakObj->read();

$svi_ucenici_za_filter = array_unique(array_column($svi_zapisi, 'ucenik'));

$izabrani_učenik = isset($_GET['filter_ucenik']) ? $_GET['filter_ucenik'] : '';

if (!empty($izabrani_učenik)) {
    $prikaz_zapisa = array_filter($svi_zapisi, function($zapis) use ($izabrani_učenik) {
        return $zapis['ucenik'] === $izabrani_učenik;
    });
} else {
    $prikaz_zapisa = $svi_zapisi;
}

$ukupno_ucenika = count(array_unique(array_column($prikaz_zapisa, 'ucenik')));
$ukupno_predmeta = count(array_unique(array_column($prikaz_zapisa, 'predmet')));
$ukupno_minuta = array_sum(array_column($prikaz_zapisa, 'vreme'));
$ukupno_sati = round($ukupno_minuta / 60, 1);
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Student Planner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #fcf8f7; font-family: 'Quicksand', sans-serif; color: #5a4b4c; }
        .navbar-custom { background-color: #ffffff; border-bottom: 1px solid #e8dedf; padding: 15px 0; }
        .navbar-brand-custom { color: #8c6267 !important; font-weight: 700; font-size: 1.4rem; }
        .card { border: none; border-radius: 16px; background-color: #ffffff; box-shadow: 0 5px 15px rgba(186, 144, 148, 0.05); margin-bottom: 20px; }
        .stat-card { background-color: #f7eded; border-left: 5px solid #ba9094; padding: 20px; text-align: center; }
        .stat-card h5 { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; color: #a3797d; margin-bottom: 5px; }
        .stat-card h3 { color: #5a4b4c; font-weight: 700; margin-bottom: 0; }
        .card-header-custom { background-color: #faf6f6; border-bottom: 1px solid #e8dedf; color: #8c6267; font-weight: 600; padding: 15px 20px; border-top-left-radius: 16px !important; border-top-right-radius: 16px !important; }
        .table-custom { margin-bottom: 0; }
        .table-custom th { background-color: #faf6f6 !important; color: #8c6267 !important; font-weight: 600; border-bottom: 1px solid #e8dedf; padding: 12px 20px; }
        .table-custom td { padding: 15px 20px; vertical-align: middle; color: #5a4b4c; border-bottom: 1px solid #f5eded; }
        .form-control, .form-select { border: 1px solid #e8dedf; border-radius: 8px; background-color: #faf6f6; color: #5a4b4c; }
        .form-control:focus, .form-select:focus { border-color: #ba9094; box-shadow: 0 0 0 0.25rem rgba(186, 144, 148, 0.25); }
        .btn-pink { background-color: #ba9094; color: white; border: none; border-radius: 8px; padding: 10px; font-weight: 600; }
        .btn-pink:hover { background-color: #a3797d; color: white; }
        .badge-pink { background-color: #e6cbd0; color: #8c6267; font-weight: 600; padding: 6px 12px; border-radius: 20px; }
        .quote-box { background-color: #fffafb; border: 1px dashed #d1b2b5; border-radius: 12px; padding: 15px; text-align: center; font-style: italic; margin-bottom: 20px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-custom mb-4 shadow-sm">
        <div class="container">
            <span class="navbar-brand-custom">☕ Zdravo, <?php echo htmlspecialchars($_SESSION['ime_prezime']); ?>!</span>
            <a href="logout.php" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">Odjavi se</a>
        </div>
    </nav>

    <div class="container px-3 px-sm-0">
        <div class="quote-box mx-1">
            "Small steps every day lead to big results." 🤍
        </div>

        <div class="row mb-2 g-3">
            <div class="col-12 col-md-4">
                <div class="card stat-card h-100">
                    <h5>Ukupno učenika</h5>
                    <h3><?php echo $ukupno_ucenika; ?></h3>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #d1b2b5;">
                    <h5>Aktivni predmeti</h5>
                    <h3><?php echo $ukupno_predmeta; ?></h3>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card stat-card h-100" style="border-left-color: #e8dedf;">
                    <h5>Sati učenja</h5>
                    <h3><?php echo $ukupno_sati; ?> h</h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header-custom">
                        <?php echo $edit_mod ? "✏️ Izmeni napredak" : "✍️ Unesi napredak"; ?>
                    </div>
                    <div class="card-body p-3 p-sm-4">
                        <form action="dashboard.php" method="POST">
                            <input type="hidden" name="zapis_id" value="<?php echo $edit_id; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Učenik</label>
                                <input type="text" name="ucenik" class="form-control" value="<?php echo htmlspecialchars($u_ucenik); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Predmet</label>
                                <input type="text" name="predmet" class="form-control" value="<?php echo htmlspecialchars($u_predmet); ?>" placeholder="Npr. Matematika" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Zadatak / Tema</label>
                                <input type="text" name="zadatak" class="form-control" value="<?php echo htmlspecialchars($u_zadatak); ?>" placeholder="Npr. Kvadratne jednačine" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ocena</label>
                                <select name="ocena" class="form-select">
                                    <option value="">Izaberi ocenu</option>
                                    <option value="5" <?php echo $u_ocena == 5 ? 'selected' : ''; ?>>5</option>
                                    <option value="4" <?php echo $u_ocena == 4 ? 'selected' : ''; ?>>4</option>
                                    <option value="3" <?php echo $u_ocena == 3 ? 'selected' : ''; ?>>3</option>
                                    <option value="2" <?php echo $u_ocena == 2 ? 'selected' : ''; ?>>2</option>
                                    <option value="1" <?php echo $u_ocena == 1 ? 'selected' : ''; ?>>1</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vreme učenja (minuti)</label>
                                <input type="number" name="vreme" class="form-control" value="<?php echo $u_vreme; ?>" placeholder="Npr. 45" required>
                            </div>
                            <button type="submit" name="sacuvaj" class="btn btn-pink w-100 mt-2">
                                <?php echo $edit_mod ? "Ažuriraj napredak ✨" : "Sačuvaj napredak ✨"; ?>
                            </button>
                            <?php if($edit_mod): ?>
                                <a href="dashboard.php" class="btn btn-light w-100 mt-2 btn-sm" style="border-radius: 8px;">Otkaži izmenu</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card p-3 mb-3">
                    <form action="dashboard.php" method="GET" class="row g-3 align-items-center">
                        <div class="col-12 col-sm-auto">
                            <label class="col-form-label text-muted" style="font-size: 0.9rem;">🔍 Filtriraj po učeniku:</label>
                        </div>
                        <div class="col-12 col-sm-6 col-md-5">
                            <select name="filter_ucenik" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Prikaži sve učenike</option>
                                <?php foreach ($svi_ucenici_za_filter as $ime_ucenika): ?>
                                    <option value="<?php echo htmlspecialchars($ime_ucenika); ?>" <?php echo $izabrani_učenik === $ime_ucenika ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($ime_ucenika); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (!empty($izabrani_učenik)): ?>
                            <div class="col-12 col-sm-auto">
                                <a href="dashboard.php" class="btn btn-sm btn-light w-100" style="border-radius: 8px;">Poništi filter</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header-custom">📅 Pregled napretka učenja</div>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover">
                            <thead>
                                <tr>
                                    <th>Učenik</th>
                                    <th>Predmet</th>
                                    <th>Zadatak</th>
                                    <th>Ocena</th>
                                    <th>Vreme</th>
                                    <th>Akcije</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($prikaz_zapisa)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted p-4">Nema zapisa za prikaz. 💕</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($prikaz_zapisa as $zapis): ?>
                                        <tr>
                                            <td class="text-nowrap"><strong><?php echo htmlspecialchars($zapis['ucenik']); ?></strong></td>
                                            <td class="text-nowrap"><?php echo htmlspecialchars($zapis['predmet']); ?></td>
                                            <td><?php echo htmlspecialchars($zapis['zadatak']); ?></td>
                                            <td>
                                                <?php if ($zapis['ocena']): ?>
                                                    <span class="badge badge-pink"><?php echo $zapis['ocena']; ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-nowrap"><?php echo $zapis['vreme']; ?> min</td>
                                            <td class="text-nowrap">
                                                <a href="dashboard.php?izmeni=<?php echo $zapis['id']; ?>" class="btn btn-sm btn-link text-muted p-0 me-2" style="text-decoration: none;">✏️</a>
                                                <a href="dashboard.php?obrisi=<?php echo $zapis['id']; ?>" class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Da li ste sigurni da želite da obrišete ovaj zapis?')" style="text-decoration: none;">❌</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (isset($etapa) && $etapa !== null): ?>
        
        <div class="mb-5 text-center">
            <h1>Etapa č. <?= $etapa->number ?? 'Neznámé' ?></h1>
            <p class="fs-5">Trasa: <?= $etapa->departure ?? '?' ?> – <?= $etapa->arrival ?? '?' ?></p>
            
            <ul class="list-group shadow-sm mx-auto text-start mb-4" style="max-width: 400px;">
                <li class="list-group-item"><strong>Vzdálenost:</strong> <?= $etapa->distance ?? '0' ?> km</li>
                <li class="list-group-item"><strong>Převýšení:</strong> <?= $etapa->vertical_meters ?? '0' ?> m</li>
                <li class="list-group-item"><strong>Typ trasy:</strong> <?= $etapa->parcour_name ?? 'Nespecifikováno' ?></li>
            </ul>

            <?php if (!empty($etapa->profile)): ?>
                <div class="mx-auto" style="max-width: 800px;">
                    <img src="<?= base_url('obrazky/stages/profiles/' . $etapa->profile) ?>" 
                         class="img-fluid rounded shadow" alt="Profil etapy <?= $etapa->number ?>">
                </div>
            <?php endif; ?>

            <?php if (!empty($etapa->description)): ?>
                <div class="card mx-auto mt-5 shadow-sm text-start" style="max-width: 800px; border-radius: 8px;">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="fw-bold mb-0 py-1">Podrobnosti etapy</h5>
                    </div>
                    <div class="card-body p-4">
                        <?= $etapa->description ?>
                    </div>
                </div>
            <?php endif; ?>
            </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h3 class="text-center mb-4">Celkové pořadí</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-hover shadow-sm border">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" style="width: 10%;">Pořadí</th>
                                <th>Jméno</th>
                                <th class="text-center" style="width: 15%;">Stát</th>
                                <th class="text-end" style="width: 20%;">Čas</th>
                                <th class="text-end" style="width: 15%;">Ztráta</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ranking)): ?>
                                <?php 
                                    $vitez_cas = null;
                                    foreach ($ranking as $jezdec) {
                                        if (!empty($jezdec->time)) {
                                            $vitez_cas = $jezdec->time;
                                            break;
                                        }
                                    }
                                ?>
                                <?php foreach ($ranking as $r): ?>
                                    <tr>
                                        <td class="text-center fw-bold align-middle"><?= $r->rank ?>.</td>
                                        <td class="align-middle"><?= $r->first_name ?> <?= $r->last_name ?></td>
                                        <td class="text-center align-middle">
                                            <?php if (!empty($r->country)): ?>
                                                <span class="fi fi-<?= strtolower($r->country) ?> me-2 border"></span>
                                                <?= strtoupper($r->country) ?>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end align-middle fw-semibold"><?= $r->time ?? '--:--' ?></td>
                                        <td class="text-end align-middle text-muted small fw-semibold">
                                            <?php 
                                                if (empty($r->time) || empty($vitez_cas)) {
                                                    echo '—';
                                                } else {
                                                    $sekundy_vitez = strtotime("1970-01-01 " . $vitez_cas);
                                                    $sekundy_jezdec = strtotime("1970-01-01 " . $r->time);
                                                    $rozdil = $sekundy_jezdec - $sekundy_vitez;

                                                    if ($rozdil === 0) {
                                                        echo (int)$r->rank === 1 ? '—' : '+ 00:00';
                                                    } else if ($rozdil > 0) {
                                                        $hodiny = floor($rozdil / 3600);
                                                        $minuty = floor(($rozdil % 3600) / 60);
                                                        $sekundy = $rozdil % 60;
                                                        echo ($hodiny > 0) ? sprintf('+ %d:%02d:%02d', $hodiny, $minuty, $sekundy) : sprintf('+ %02d:%02d', $minuty, $sekundy);
                                                    } else {
                                                        echo '—';
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Výsledky pro tuto etapu nebyly nalezeny.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <div class="alert alert-danger shadow-sm">
            <strong>Chyba:</strong> Data o etapě se nepodařilo načíst.
        </div>
    <?php endif; ?>

    <hr class="mt-5">
    <div class="text-center pb-4">
        <a href="<?= base_url('/') ?>" class="btn btn-secondary px-4">Zpět na seznam etap</a>
    </div>
</div>

<?= $this->endSection() ?>
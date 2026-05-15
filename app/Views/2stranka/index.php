<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (isset($etapa) && $etapa !== null): ?>
        
        <div class="mb-5 text-center">
            <h1>Etapa č. <?= $etapa->number ?? 'Neznámé' ?></h1>
            <p class="fs-5">Trasa: <?= $etapa->departure ?? '?' ?> – <?= $etapa->arrival ?? '?' ?></p>
            
            <ul class="list-group shadow-sm mx-auto text-start" style="max-width: 400px;">
                <li class="list-group-item">
                    <strong>Vzdálenost:</strong> <?= $etapa->distance ?? '0' ?> km
                </li>
                <li class="list-group-item">
                    <strong>Převýšení:</strong> <?= $etapa->vertical_meters ?? '0' ?> m
                </li>
                <li class="list-group-item">
                    <strong>Typ trasy:</strong> <?= $etapa->parcour_name ?? 'Nespecifikováno' ?>
                </li>
            </ul>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ranking)): ?>
                                <?php foreach ($ranking as $r): ?>
                                    <tr>
                                        <td class="text-center fw-bold align-middle"><?= $r->rank ?>.</td>
                                        <td class="align-middle"><?= $r->first_name ?> <?= $r->last_name ?></td>
                                        
                                        <td class="text-center align-middle">
                                            <span class="fi fi-<?= strtolower($r->country) ?> me-2 border"></span>
                                            <?= strtoupper($r->country) ?>
                                        </td>
                                        
                                        <td class="text-end align-middle fw-semibold"><?= $r->time ?? '--:--' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Výsledky pro tuto etapu nebyly nalezeny.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <div class="alert alert-danger shadow-sm">
            <strong>Chyba:</strong> Data o etapě se nepodařilo načíst z tabulky <code>km_stage</code>.
        </div>
    <?php endif; ?>

    <hr class="mt-5">
    <div class="text-center pb-4">
        <a href="<?= base_url('/') ?>" class="btn btn-secondary px-4">Zpět na seznam etap</a>
    </div>
</div>

<?= $this->endSection() ?>
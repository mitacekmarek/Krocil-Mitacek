<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (isset($etapa) && $etapa !== null): ?>
        <h1>Etapa č. <?= $etapa->km_number ?? $etapa->number ?? 'Neznámé' ?></h1>
        <p>Trasa: <?= $etapa->km_departure ?? $etapa->departure ?? '?' ?> – <?= $etapa->km_arrival ?? $etapa->arrival ?? '?' ?></p>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item">Vzdálenost: <?= $etapa->km_distance ?? $etapa->distance ?? '0' ?> km</li>
                    <li class="list-group-item">Typ: <?= $etapa->km_type ?? $etapa->type ?? 'Nespecifikováno' ?></li>
                </ul>
            </div>
            
            <div class="col-md-8">
                <h3>Celkové pořadí</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pořadí</th>
                            <th>Jméno (Odkaz)</th>
                            <th>Čas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ranking)): ?>
                            <?php foreach ($ranking as $r): ?>
                                <tr>
                                    <td><?= $r->rank ?>.</td>
                                    <td><?= $r->name_link ?? 'Neznámý jezdec' ?></td>
                                    <td><?= $r->time ?? '--:--' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">Výsledky pro tuto etapu nebyly nalezeny.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <strong>Chyba:</strong> Data o etapě se nepodařilo načíst z tabulky <code>km_stage</code>.
        </div>
    <?php endif; ?>

    <hr>
    <a href="<?= base_url('etapy') ?>" class="btn btn-secondary">Zpět na seznam etap</a>
</div>

<?= $this->endSection() ?>
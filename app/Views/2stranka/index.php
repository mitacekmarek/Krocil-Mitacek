<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<?php 
  /** @var \App\Models\EtapaI $etapa */ 
?>

<div class="container mt-4">
    <?php if (isset($etapa) && $etapa !== null): ?>
        <h1>Etapa č. <?= $etapa->number ?></h1>
        <p>Trasa: <?= $etapa->departure ?> – <?= $etapa->arrival ?></p>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item">Vzdálenost: <?= $etapa->distance ?> km</li>
                    <li class="list-group-item">Typ: <?= $etapa->type ?? 'Nespecifikováno' ?></li>
                </ul>
            </div>
            
            <div class="col-md-8">
                <h3>Celkové pořadí</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Pořadí</th>
                            <th>Jméno</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ranking)): ?>
                            <?php foreach ($ranking as $r): ?>
                                <tr>
                                    <td><?= $r->rank ?>.</td>
                                    <td><?= $r->rider_name ?? 'Neznámý jezdec' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2">Výsledky nebyly nalezeny.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Data o etapě se nepodařilo načíst.</div>
    <?php endif; ?>

    <a href="<?= base_url('etapy') ?>" class="btn btn-secondary">Zpět</a>
</div>

<?= $this->endSection() ?>
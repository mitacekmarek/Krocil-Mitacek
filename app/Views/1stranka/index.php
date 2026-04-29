<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <?php /** @var string $nazev */ ?> 
    <h2><?= $nazev ?></h2>
    <hr>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Číslo etapy</th>
                <th>Start (Departure)</th>
                <th>Cíl (Arrival)</th>
                <th>Vzdálenost</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($etapy)): ?>
                <?php foreach ($etapy as $etapa): ?>
                    <tr>
                        <td><?= $etapa->id ?></td>
                        <td><?= $etapa->number ?></td>
                        <td><?= $etapa->departure ?></td>
                        <td><?= $etapa->arrival ?></td>
                        <td><?= $etapa->distance ?> km</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
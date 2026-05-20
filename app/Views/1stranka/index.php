<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <?php /** @var string $nazev */ ?> 
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0"><?= $nazev ?></h2>
        <?= anchor('etapa/upravit', 'Upravit', ['class' => 'btn btn-primary']) ?>
    </div>
    <hr>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Číslo etapy</th>
                <th>Start</th>
                <th>Cíl</th>
                <th>Vzdálenost</th>
                <th class="text-center">Web</th>
                <th class="text-center">Etapa</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($etapy)): ?>
                <?php foreach ($etapy as $etapa): ?>
                    <tr>
                        <td class="align-middle fw-bold"><?= $etapa->id ?></td>
                        <td class="align-middle"><?= $etapa->number ?>. etapa</td>
                        <td class="align-middle"><?= $etapa->departure ?></td>
                        <td class="align-middle"><?= $etapa->arrival ?></td>
                        <td class="align-middle"><?= $etapa->distance ?> km</td>
                        
                        <td class="text-center align-middle">
                            <?= anchor($etapa->link, 'Odkaz', [
                                'target' => '_blank', 
                                'class'  => 'btn btn-outline-secondary btn-sm'
                            ]) ?>
                        </td>
                        <td class="text-center align-middle">
                            <?= anchor('etapa/detail/' . $etapa->id, 'Detail', ['class' => 'btn btn-dark btn-sm']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Zatím nebyly nalezeny žádné etapy.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <?php /** @var string $nazev */ ?> 
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0"><?= $nazev ?></h2>
        <?= anchor('sprava', 'Přidat novou etapu', ['class' => 'btn btn-success fw-bold']) ?>
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
                <th class="text-center">Akce</th>
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
                            <div class="d-flex justify-content-center gap-1">
                                <?= anchor('etapa/detail/' . $etapa->id, 'Detail', ['class' => 'btn btn-dark btn-sm']) ?>
                                <?= anchor('sprava/edit/' . $etapa->id, 'Upravit', ['class' => 'btn btn-warning btn-sm text-dark']) ?>
                                <?= anchor('sprava/delete/' . $etapa->id, 'Smazat', [
                                    'class' => 'btn btn-danger btn-sm',
                                    'onclick' => "return confirm('Opravdu chcete tuto etapu smazat?');"
                                ]) ?>
                            </div>
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
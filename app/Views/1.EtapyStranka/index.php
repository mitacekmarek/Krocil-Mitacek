<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
    <?php /** @var string $nazev */ ?> 
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0"><?= $nazev ?></h2>
        <a href="<?= base_url('sprava/add') ?>" class="btn btn-success fw-bold fs-5 px-3 shadow-sm" title="Přidat novou etapu">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>
    <hr>

    <div class="table-responsive shadow-sm border rounded">
        <table class="table table-bordered table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Číslo etapy</th>
                    <th>Datum</th> 
                    <th>Start</th>
                    <th>Cíl</th>
                    <th>Vzdálenost</th>
                    <th class="text-center">Web</th>
                    <th class="text-center">Detail</th>
                    <th class="text-center">Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($etapy)): ?>
                    <?php foreach ($etapy as $etapa): ?>
                        <tr>
                            <td class="align-middle fw-bold"><?= $etapa->id ?></td>
                            <td class="align-middle"><?= $etapa->number ?>. etapa</td>
                            
                            <td class="align-middle">
                                <?= !empty($etapa->date) ? date('d. m. Y', strtotime($etapa->date)) : 'Neznámé' ?>
                            </td>
                            
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
                                <a href="<?= base_url('etapa/detail/' . $etapa->id) ?>" class="btn btn-primary btn-sm">Prozkoumat</a>
                            </td>

                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?= base_url('sprava/edit/' . $etapa->id) ?>" class="btn btn-warning btn-sm shadow-sm" title="Upravit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <a href="<?= base_url('sprava/delete/' . $etapa->id) ?>" class="btn btn-danger btn-sm shadow-sm" title="Smazat" onclick="return confirm('Opravdu chcete tuto etapu smazat?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">Zatím nebyly nalezeny žádné etapy.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
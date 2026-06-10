<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>
<div class="container mt-5">
<?php /** @var string $nazev */ ?>
    <?php if (session()->getFlashdata('alert')): ?>
        <?php $alert = session()->getFlashdata('alert'); ?>
        <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show shadow-sm mb-4" role="alert"> 
            <?php if ($alert['type'] === 'success'): ?>
                <i class="bi bi-check-circle-fill me-2"></i>
            <?php else: ?>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php endif; ?>
            
            <?= $alert['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="m-0"><?= $nazev ?></h2>
        <a href="<?= base_url('sprava/add') ?>" class="btn btn-success fw-bold fs-5 px-3 shadow-sm">
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
                    <th>Vítěz</th> <th class="text-center">Web</th>
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
                            <td class="align-middle"><?= !empty($etapa->date) ? date('d. m. Y', strtotime($etapa->date)) : 'Neznámé' ?></td>
                            <td class="align-middle"><?= $etapa->departure ?></td>
                            <td class="align-middle"><?= $etapa->arrival ?></td>
                            <td class="align-middle"><?= $etapa->distance ?> km</td>
                            <td class="align-middle fw-bold"><?= $etapa->vitez_jmeno ?? '—' ?></td>
                            
                            <td class="text-center align-middle">
                                <?= anchor($etapa->link, 'Odkaz', ['target' => '_blank', 'class' => 'btn btn-outline-secondary btn-sm']) ?>
                            </td>
                            <td class="text-center align-middle">
                                <a href="<?= base_url('etapa/detail/' . $etapa->id) ?>" class="btn btn-primary btn-sm">Prozkoumat</a>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?= base_url('sprava/edit/' . $etapa->id) ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                    <a href="<?= base_url('sprava/delete/' . $etapa->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Opravdu smazat?');"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center py-4">Zatím nebyly nalezeny žádné etapy pro tento ročník.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
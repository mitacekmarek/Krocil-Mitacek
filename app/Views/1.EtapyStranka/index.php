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
                    <th>Vítěz</th> 
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
                                    
                                    <button type="button" 
                                            class="btn btn-danger btn-sm open-delete-modal" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            data-id="<?= $etapa->id ?>"
                                            data-name="<?= $etapa->number ?>. etapu (<?= $etapa->departure ?> – <?= $etapa->arrival ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Potvrzení smazání</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-5">
                Opravdu si přejete smazat <strong id="deleteStageName"></strong>? Tato akce je nevratná.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger fw-bold">Ano, smazat</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () { // Po načtení celé stránky
    const deleteButtons = document.querySelectorAll('.open-delete-modal'); // Vybereme všechna tlačítka, která otevírají modální okno pro smazání
    const deleteStageNameSpan = document.getElementById('deleteStageName'); // Element pro zobrazení názvu etapy v modálním okně
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn'); // Odkaz pro potvrzení smazání

    deleteButtons.forEach(button => { // Pro každé tlačítko přidáme posluchač události
        button.addEventListener('click', function () { // Při kliknutí na tlačítko pro smazání
            const id = this.getAttribute('data-id'); // Získá ID etapy z data atributu
            const name = this.getAttribute('data-name'); // Získá název etapy z data atributu
            deleteStageNameSpan.textContent = name; // Dynamicky nastaví název etapy v modálním okně
            confirmDeleteBtn.setAttribute('href', '<?= base_url('sprava/delete/') ?>' + id); // Dynamicky nastaví odkaz pro potvrzení smazání
        });
    });
});
</script>

<?= $this->endSection() ?>
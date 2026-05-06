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
                <th class="text-center">Odkaz</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($etapy)): ?>
                <?php foreach ($etapy as $etapa): ?>
                    <tr>
                        <td class="align-middle"><?= $etapa->id ?></td>
                        <td class="align-middle"><?= $etapa->number ?></td>
                        <td class="align-middle"><?= $etapa->departure ?></td>
                        <td class="align-middle"><?= $etapa->arrival ?></td>
                        <td class="align-middle"><?= $etapa->distance ?> km</td>
                        
                        <!-- Tady je přidané centrování buňky a btn-dark pro černé tlačítko -->
                        <td class="text-center align-middle">
                            <?= anchor($etapa->link, 'Zobrazit na webu', [
                                'target' => '_blank', 
                                'class'  => 'btn btn-dark btn-sm text-white'
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Zatím nebyly nalezeny žádné etapy.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
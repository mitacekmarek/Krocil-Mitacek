<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mt-5 mb-5">
    <?php 
        /** @var string $nazev */ 
        /** @var array $mozna_cisla */
    ?>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><?= $nazev ?></h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="<?= base_url('sprava/create') ?>" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="id_race_year" value="12283">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" id="number" name="number" required>
                                        <option value="">-- Vyber číslo --</option>
                                        <?php foreach ($mozna_cisla as $cislo): ?>
                                            <option value="<?= $cislo ?>"><?= $cislo ?>. etapa</option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="number">Číslo etapy <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date">
                                    <label for="date">Datum etapy</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="departure" name="departure" placeholder="Start" required>
                            <label for="departure">Start etapy <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="arrival" name="arrival" placeholder="Cíl" required>
                            <label for="arrival">Cíl etapy <span class="text-danger">*</span></label>
                        </div>

                        <div class="mb-3 p-3 bg-light border rounded">
                            <label for="profile_image" class="form-label fw-bold">Soubor mapky</label>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-info-circle"></i> Zatím není nahrán žádný soubor.
                            </p>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept=".jpg, .png">
                            <div class="form-text">Nahrání obrázku je volitelné.</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" step="0.1" class="form-control" id="distance" name="distance" placeholder="Délka">
                            <label for="distance">Délka etapy (km)</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="vitez_jmeno" name="vitez_jmeno" placeholder="Vítěz etapy">
                            <label for="vitez_jmeno">Vítěz etapy (Jméno Příjmení)</label>
                            <div class="form-text text-muted">Volitelné. Jméno musí přesně odpovídat databázi (např. Kiya Rogora).</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Podrobnosti etapy</label>
                            <textarea id="tinymce-editor" name="description"></textarea>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary px-4">Zrušit</a>
                            <button type="submit" class="btn btn-success px-5 fw-bold">Vytvořit etapu</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    tinymce.init({
        selector: '#tinymce-editor',
        license_key: 'gpl',
        promotion = false,
        height: 400,
        width: 800,
        menubar: 'file edit insert view format table tools',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 
            'insertdatetime', 'media', 'table', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | table | removeformat',
    });
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mt-5 mb-5">
    <?php 
        /** @var string $nazev */ 
        /** @var object $etapa */
    ?>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><?= $nazev ?></h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="<?= base_url('sprava/update/' . $etapa->id) ?>" method="post" enctype="multipart/form-data">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="number" name="number" value="<?= $etapa->number ?>" required readonly>
                                    <label for="number">Číslo etapy <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" value="<?= $etapa->date ?>">
                                    <label for="date">Datum etapy</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="departure" name="departure" value="<?= $etapa->departure ?>" required>
                            <label for="departure">Start etapy <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="arrival" name="arrival" value="<?= $etapa->arrival ?>" required>
                            <label for="arrival">Cíl etapy <span class="text-danger">*</span></label>
                        </div>

                        <div class="mb-3 p-3 bg-light border rounded">
                            <label for="profile_image" class="form-label fw-bold">Mapka trati (Obrázek .jpg, .png)</label>
                            <?php if(!empty($etapa->profile)): ?>
                                <p class="text-success small mb-2"><i class="bi bi-check-circle"></i> Aktuální soubor: <strong><?= $etapa->profile ?></strong></p>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="profile_image" name="profile_image" accept=".jpg, .png">
                            <div class="form-text">Pokud nevyberete žádný soubor, zůstane ten původní.</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" step="0.1" class="form-control" id="distance" name="distance" value="<?= $etapa->distance ?>">
                            <label for="distance">Délka etapy (km)</label>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Podrobnosti etapy</label>
                            <textarea id="tinymce-editor" name="note"><?= $etapa->note ?></textarea>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary px-4">Zrušit a zpět</a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold">Uložit změny</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#tinymce-editor',
        height: 300,
        menubar: false,
        plugins: ['advlist', 'autolink', 'lists', 'link', 'visualblocks', 'wordcount'],
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright',
    });
</script>

<?= $this->endSection() ?>
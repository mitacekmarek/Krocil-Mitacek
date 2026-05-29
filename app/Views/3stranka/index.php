<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card shadow">
                <div class="card-header <?= isset($etapa) && !empty($etapa->id) ? 'bg-warning text-dark' : 'bg-success text-white' ?>">
                    <h4 class="mb-0"><?= $nazev ?? 'Správa etapy' ?></h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="<?= base_url('sprava/save') ?>" method="post">
                        <input type="hidden" name="id" value="<?= $etapa->id ?? '' ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <select class="form-select" id="number" name="number" required>
                                        <option value="">Vyberte položku</option>
                                        
                                        <?php if(!empty($cisla_etap)): ?>
                                            <?php foreach($cisla_etap as $c): ?>
                                                <option value="<?= $c ?>" <?= (isset($etapa) && $etapa->number == $c) ? 'selected' : '' ?>>
                                                    Etapa č. <?= $c ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        
                                        <?php if(!isset($etapa) || empty($etapa->id)): ?>
                                            <?php $dalsi = !empty($cisla_etap) ? max($cisla_etap) + 1 : 1; ?>
                                            <option value="<?= $dalsi ?>">
                                                Etapa č. <?= $dalsi ?> (Nová)
                                            </option>
                                        <?php endif; ?>
                                        
                                    </select>
                                    <label for="number">Číslo etapy <span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" value="<?= $etapa->date ?? '' ?>">
                                    <label for="date">Datum etapy</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="departure" name="departure" placeholder="Start" value="<?= $etapa->departure ?? '' ?>" required>
                            <label for="departure">Start etapy<span class="text-danger">*</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="arrival" name="arrival" placeholder="Cíl" value="<?= $etapa->arrival ?? '' ?>" required>
                            <label for="arrival">Cíl etapy<span class="text-danger">*</span></label>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" step="0.1" class="form-control" id="distance" name="distance" placeholder="Délka" value="<?= $etapa->distance ?? '' ?>">
                                    <label for="distance">Délka etapy (km)</label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="profile" name="profile" placeholder="Mapka" value="<?= $etapa->profile ?? '' ?>">
                                    <label for="profile">Soubor mapky (např. profile-1.jpg)</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold">Podrobnosti etapy</label>
                            <textarea id="tinymce-editor" name="note"><?= $etapa->note ?? '' ?></textarea>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary px-4">Zrušit a zpět</a>
                            
                            <button type="submit" class="btn <?= isset($etapa) && !empty($etapa->id) ? 'btn-warning' : 'btn-success' ?> px-5 fw-bold">
                                <?= isset($etapa) && !empty($etapa->id) ? 'Uložit změny' : 'Vytvořit etapu' ?>
                            </button>
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
        plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'],
        toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });
</script>

<?= $this->endSection() ?>
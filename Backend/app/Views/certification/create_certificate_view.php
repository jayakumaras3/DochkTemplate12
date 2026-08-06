<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Certification/Dashboard') ?>">Certification</a></li>
                </ol>
            </div>
            <h4 class="page-title">Create New Certificate</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Certification/Dashboard/add_new_certificate') ?>" method="POST" enctype="multipart/form-data"><?= csrf_field() ?>
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Certificate Name</label>
                                <input type="text" name="name" class="form-control" id="name" aria-describedby="name" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" name="type">
                                    <option value="3">Courses</option>
                                    <option value="2">Learning Plan</option>
                                    <?php if ($client_id == 1) { ?>
                                        <option value="1">Marketplace</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="font_size" class="form-label">Font Size (px)</label>
                                <input type="number" name="font_size" class="form-control" id="font_size" value="16" min="8" max="72" step="1" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="font_weight" class="form-label">Font Weight</label>
                                <select class="form-select" name="font_weight" id="font_weight">
                                    <option value="300">Light</option>
                                    <option value="400" selected>Normal</option>
                                    <option value="500">Medium</option>
                                    <option value="600">Semi-Bold</option>
                                    <option value="700">Bold</option>
                                    <option value="800">Extra Bold</option>
                                    <option value="900">Black</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="font_color" class="form-label">Font Color</label>
                                <div class="input-group">
                                    <input type="color" name="font_color" class="form-control form-control-color" id="font_color" value="#000000" title="Choose color" style="max-width: 60px;">
                                    <input type="text" class="form-control" id="font_color_text" value="#000000" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="background_image" class="form-label">Background Image</label>
                                <input type="file" name="background_image" class="form-control" id="background_image" accept="image/*">
                                <small class="form-text text-muted">JPG, PNG, GIF, SVG (Max 5MB)</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light">Submit</button>
                        </div>
                    </div>
                </form>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
</div>

<script>
    // Sync color picker with text input
    document.getElementById('font_color').addEventListener('change', function() {
        document.getElementById('font_color_text').value = this.value;
    });

    // Allow text input for color codes
    document.getElementById('font_color_text').addEventListener('blur', function() {
        const colorRegex = /^#[0-9A-F]{6}$/i;
        if (colorRegex.test(this.value)) {
            document.getElementById('font_color').value = this.value;
        } else {
            this.value = document.getElementById('font_color').value;
        }
    });
</script>
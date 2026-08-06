<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Others/Ojts_consolidated/ojts_download_pdf'); ?>">OJTS Dashboard</a></li>

                </ol>
            </div>
            <h4 class="page-title">Add OJT</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/add_ojtsfilename') ?>" method="POST" id="submitForm"><?= csrf_field() ?>
                    <div class="row">
                        <div class="form-group col-md-12 mb-2">
                            <label>Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="filenameInput" name="filename" placeholder="Title"
                                value="<?= esc($ojts_row[0]['filename'] ?? '') ?>" required maxlength="115" />
                            <div id="errorImage" style="display:none; margin-top:5px;">
                                <small class="text-danger">Title cannot exceed 115 characters.</small>
                            </div>
                        </div>

                        <script>
                            document.getElementById('filenameInput').addEventListener('input', function() {
                                const maxLength = this.getAttribute('maxlength');
                                const errorImage = document.getElementById('errorImage');

                                if (this.value.length >= maxLength) {
                                    errorImage.style.display = 'block';
                                } else {
                                    errorImage.style.display = 'none';
                                }
                            });
                        </script>
                    </div>
                    <div class="form-group col-md-12 mb-2">
                        <label>Language<span class="text-danger">*</span></label>
                        <select name="language" class="form-control">
                            <option value="English">English</option>
                            <option value="Spanish">Spanish</option>
                            <option value="French">French</option>
                            <option value="Russian">Russian</option>
                            <option value="Portuguese">Portuguese</option>
                            <option value="Bahasa">Bahasa</option>
                            <option value="Arabic">Arabic</option>
                            <option value="German">German</option>
                            <option value="Italian">Italian</option>

                        </select>
                    </div>
                    <div class="row">
                        <div class="form-group  col-md-12 mb-2">
                            <?php if (isset($ojtsavalidation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-white" role="alert">
                                        <?= $ojtsavalidation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light float-end col-md-12" id="submitButton">
                                Create
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
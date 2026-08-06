<style>
    .settings-back-link {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-weight: 600;
        font-size: .875rem;
        color: #6658dd;
        text-decoration: none;
    }

    [data-bs-theme="dark"] .settings-back-link {
        color: #9298f5;
    }

    .settings-back-link:hover {
        text-decoration: underline;
    }

    .settings-section {
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06), 0 2px 6px rgba(0, 0, 0, 0.04);
        border: none;
    }

    .settings-section .section-title {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .settings-section .section-title i {
        color: #6658dd;
    }

    [data-bs-theme="dark"] .settings-section .section-title i {
        color: #9298f5;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url($courses_link) ?>"><?= esc($courses_link_label) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url($header_link) ?>"><?= lang('UI_Text.Course_Details') ?></a></li>
                </ol>
            </div>
            <h4 class="page-title"><?= lang('UI_Text.Categories') ?> - <?php echo esc($course_name) ?></h4>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-4">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-shape-plus-outline"></i> <?= lang('UI_Text.Add_Category') ?></h5>
                <form class="row" id="addcategoryForm">
                    <div class="col-12 mt-1">
                        <label class="form-label"><?= lang('UI_Text.Add_Category') ?></label>
                        <select class="form-select select2-multiple" data-toggle="select2" data-width="100%" multiple="multiple" name="metaCategory[]" required="">
                            <?php foreach ($allcategories as $eachcategories) {
                                $key = array_search($eachcategories['sc_mcid'], array_column($getAssigncategorydata, 'fk_sc_mcid'));
                                if (!empty($key) || $key === 0) {
                                } else { ?>
                                    <option value="<?= $eachcategories['sc_mcid'] ?>"><?= $eachcategories['description'] ?></option>

                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <input type="hidden" name="scourse_id" value="<?php echo $scourse_id ?>">
                        <input type="hidden" name="typeofval" value="2">
                        <button type="submit" class="btn btn-outline-primary rounded-pill btn-xs waves-effect waves-light" id="submitButton">
                           <?= lang('Buttons.Submit') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card settings-section mb-3">
            <div class="card-body">
                <h5 class="section-title"><i class="mdi mdi-format-list-bulleted"></i> <?= lang('UI_Text.Categories_View') ?></h5>
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th><?= lang('UI_Text.Categories') ?></th>
                                <th><?= lang('UI_Text.Delete') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 0;
                            foreach ($getAssigncategorydata as $eachassigncategory) {
                                $j = $j + 1; ?>
                                <tr>
                                    <td><?= $j ?></td>
                                    <td><?= $eachassigncategory['description'] ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url($form_url_5) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="status" value="0">
                                            <input type="hidden" name="mc_id" value="<?php echo $eachassigncategory['mc_id'] ?>">
                                            <button type="submit" class="btn btn-outline-danger rounded-pill waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-delete"></span></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('addcategoryForm').addEventListener('submit', function() {
        var button = document.getElementById('submitButton');
        button.disabled = true;
        button.innerHTML = 'Submitting...';
    });
</script>
<script>
    $('#addcategoryForm').on('submit', function(event) {

        event.preventDefault();

        var dataString = new FormData($('#addcategoryForm')[0]);

        if (typeof FormData !== 'undefined') {

            $.ajax({
                url: '<?php echo base_url($form_url_4) ?>',
                type: "POST",
                data: dataString,
                async: false,
                processData: false,
                contentType: false,
                success: function(data) {

                    var obj = JSON.parse(data);

                    console.log(obj);

                    if (obj.status === 'OK') {
                        console.log('inside on condition');
                        //window.location.href = 'project_settings.php';
                        alert('Category added successfully');
                        location.reload();
                      

                    } else {

                        alert('error', 'Something Went Wrong! Please contact Site Admin!');
                    }

                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('request failed');
                }
            })
        } else {
            message("Your Browser Don't support FormData API! Use IE 10 or Above!");
        }

    });
</script>
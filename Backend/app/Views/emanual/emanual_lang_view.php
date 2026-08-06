<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url($header_link); ?>">
                            Page View
                        </a>
                    </li>
                </ol>
            </div>
            <h4 class="page-title">
                <?php echo $sub_header_1; ?>
            </h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6>Select Language</h6>
                <form class="form-horizontal1" id="addlangForm">
                    <div class="form-row">
                        <select name="language" class="form-control">
                            <?php
                            if ($languageData) {
                                foreach ($languageData  as $eachlanguageData) {
                                    $key = array_search($eachlanguageData['id_d'], array_column($getLanguagedata, 'lang_id'));
                                    if (!empty($key) || $key === 0) {
                                    } else {
                                        if ($eachlanguageData['id_d']  == 103) { // 103 -> english
                                        } else {
                                            echo '<option value="' . $eachlanguageData['id_d'] . '">' . $eachlanguageData['name'] . '</option>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div><br>
                    <div class="form-row">
                        <input type="hidden" name="document_id" value="<?php echo $document_id  ?>" />
                        <button type="submit" class="btn btn-sm btn-warning">Add Language</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
            <table id="searchdatatable" class="table table-sm table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Langauge</th>
                            <th>Translate</th>
                            <th width="10%">Del</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if ($getLanguagedata) {
                            foreach ($getLanguagedata as $eachlangdata) {
                                // print_r($eachlangdata);
                                $j = $j + 1; ?>
                                <tr>
                                    <th><?php echo $j; ?></th>
                                    <th><?php echo $eachlangdata['lang_name']; ?></th>
                                    <th>
                                        <form class="form-horizontal" action="<?php echo base_url($form_url_1) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="document_id" value="<?php echo $document_id ?>">
                                            <input type="hidden" name="lang_id" value="<?php echo $eachlangdata['lang_id'] ?>">
                                            <button type="submit" class="btn btn-sm widget-icon btn-success"><span class="fa fa-book"></span></button>
                                        </form>
                                    </th>
                                    <th>
                                        <form class="form-horizontal" id="deletelangForm">
                                            <input type="hidden" name="el_id" value="<?php echo $eachlangdata['el_id'] ?>">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_002') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
                                        </form>
                                    </th>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
</div>

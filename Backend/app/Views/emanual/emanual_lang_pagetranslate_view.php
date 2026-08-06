<style>
    .collapsible {

        color: white;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.2);
        width: 100%;
        border: none;
        text-align: center;
        outline: none;
        font-size: 12px;
    }

    .contented {
        padding: 0 18px;
        display: none;
        overflow: hidden;
        background-color: rgb(118, 118, 118);

    }
</style>
<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <!-- <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header; ?></a></li><b>&nbsp;>&nbsp;</b> -->
            <li><a href="<?php echo base_url($sub_header_1_link); ?>"><?php echo $sub_header_1; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($sub_header_2_link); ?>"><?php echo $sub_header_2; ?></a></li><b>&nbsp;>&nbsp;</b>
            <li class="active"><?php echo $sub_header_3; ?></li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-3">

    </div>
    <?php $userlevel = session()->get('userlevel');
    $array  = array_map('intval', str_split($userlevel)); ?>
    <div class="x_panel">

    <table id="searchdatatable" class="table table-sm table-bordered table-striped">
            <thead>
                <tr>
                    <th width=5%>#</th>
                    <th>Page Name</th>
                    <th>Page Name  - (<?php echo $getAssigntranslationpages[0]['language_name'] ?>)</th>
                    <th>Content</th>
                    <th>Translate</th>
            </thead>
            <tbody>

                <?php
                $j = 0;
                foreach ($getAssigntranslationpages as $eachPageDetails) {
                    // print_r($eachDocumentDetails);
                    $j = $j + 1;
                ?>
                    <tr>
                        <td width=5%><?php echo $j ?></td>
                        <td><?php echo $eachPageDetails['page_name'] ?></td>
                        <td><?php echo $eachPageDetails['translate_page_name'] ?></td>
                        <td>
                            <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="emd_id" value="<?php echo $document_id ?>">
                                <input type="hidden" name="empg_id" value="<?php echo $eachPageDetails['empg_id'] ?>">
                                <input type="hidden" name="page_name" value="<?php echo $eachPageDetails['page_name'] ?>">
                                <input type="hidden" name="lang_id" value="<?php echo $lang_id ?>">
                                <button type="submit" class="btn btn-sm widget-icon btn-info"><span class="fa fa-file-text"></span></button>
                            </form>
                        </td>
                        <td>
                            <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                <input type="hidden" name="empg_id" value="<?php echo $eachPageDetails['empg_id'] ?>">
                                <input type="hidden" name="lang_id" value="<?php echo $lang_id ?>">
                                <input type="hidden" name="emd_id" value="<?php echo $document_id ?>">
                                <button type="submit" class="btn btn-sm widget-icon btn-warning"><span class="fa fa-book"></span></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>

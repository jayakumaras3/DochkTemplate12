<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <form action="<?php echo base_url('Others/Ojts_consolidated/export_all_OJTS_excelformat') ?>" method="POST"><?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light float-end">
                            Download All Excel
                        </button>
                    </form>&nbsp;&nbsp;
                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojts_group_view') ?>" method="POST"><?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-primary waves-effect btn-sm waves-light float-end">OJT Bundles</button>
                    </form>&nbsp;&nbsp;
                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojts') ?>" method="POST"><?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-info waves-effect btn-sm waves-light float-end">+ Add OJT</button>
                    </form>
                </ol>
            </div>
            <h4 class="page-title">OJTS Dashboard</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Language</th>
                            <th>Edit</th>
                            <th>PDF</th>
                            <th>Excel</th>
                            <th>Delete</th>

                    </thead>

                    <tbody class="row_position">
                        <?php $j = 0;
                        //  $nxt_page = 1;
                        foreach ($ojts_consolidatedData as $data) {
                            $j = $j + 1;
                        ?>
                            <tr>
                                <td><?php echo $j; ?></td>
                                <td><?php echo $data['filename']; ?></td>
                                <td><?php echo $data['language']; ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ojts_id" value="<?php echo $data['ojts_id']  ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td>
                                <!-- <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojtsfilenameedit') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ojts_id" value="<?php echo $data['ojts_id']  ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                    </form>
                                </td> -->


                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojts_conslidated_pdf') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="filename" value="<?php echo $data['filename'] ?>">
                                        <input type="hidden" name="ojts_id" value="<?php echo $data['ojts_id']  ?>">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-file-pdf-box"></span></button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/export_OJTS_excelformat') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ojts_id" value="<?php echo $data['ojts_id']  ?>">
                                        <button type="submit" class="btn btn-outline-info waves-effect btn-xs waves-light"><span class="mdi mdi-file-excel-box"></span></button>
                                    </form>
                                </td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url('Others/Ojts_consolidated/ojtsfilenamedelete') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="ojts_id" value="<?php echo $data['ojts_id']  ?>">
                                        <input type="hidden" name="status" value="0">
                                        <button type="submit" class="btn btn-outline-danger waves-effect btn-xs waves-light" onclick="return confirm('<?php echo lang('Alert.Aler_003') ?>')"><span class="mdi mdi-trash-can-outline"></span></button>
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
<script>
    function downloadAllExcels() {

        for (let i = 1; i <= 682; i++) {

            const link = document.createElement("a");
            link.href = "/downloadExcel?id=" + i;
            link.download = "";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

        }

    }
</script>
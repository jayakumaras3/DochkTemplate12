<div class="row">
    <div class="col-12">
        <div class="page-title-box">
           <!--  <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"> <a href="<?php echo base_url('Emanual/emanual_product') ?>">
                            e-Manual
                        </a>
                    </li>
                </ol>
            </div> -->
            <h4 class="page-title">
                <?php // echo $sub_header_1;
               // echo ' {';
                echo $e_manual_name[0]['product_name'];
               // echo '}';
                ?>
            </h4>
        </div>
    </div>
</div>
<?php $userlevel = session()->get('userlevel');
$array  = array_map('intval', str_split($userlevel)); ?>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/adddocumnet') ?>" method="POST"><?= csrf_field() ?>

                    <div class="form-group col-md-12 mb-2">
                        <label>Document Name</label>
                        <input type="text" class="form-control col-md-12" name="document_name" required />
                    </div>
                   <!--  <div class="form-group col-md-12 mb-2">
                        <label>Description</label>
                        <textarea class="form-control col-md-12" name="description"></textarea>
                    </div> -->
                    <div class="form-group col-md-12 mb-2">
							<label>Sequence</label>
							<input type="number" class="form-control col-md-12" name="sequence" placeholder="Sequence" value="" />
						</div>
                    <input type="hidden" name="type" value="6">
                    <input type="hidden" name="launch_link" value="2">
                    <input type="hidden" name="description" value="">
                    <!-- <div class="form-group col-md-12 mb-2">
                        <label>Type of Document</label>
                        <select name="type" class="form-control">
                            <option value="6">Document</option>
                            <option value="7">TroubleShooting</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 mb-2">
                        <label>Type of Link</label>
                        <select name="launch_link" class="form-control">
                            <option value="1">Open</option>
                            <option value="2">Login Based</option>
                        </select>
                    </div> -->
                    <div class="form-group  col-md-12 mb-2">
                        <input type="hidden" name="em_id" value="<?php echo $em_id ?>">
                        <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">
                            Add New e-Document
                        </button>
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
                            <th>#</th>
                            <th>Document Name</th>
                            <th>Pages</th>
                            
                            <!-- <th>Type</th>
                            <th>Language</th>
                            <th>Type</th>
                            <th>Link</th> -->
                            <th>Edit</th>
                    </thead>
                    <tbody>
                        <?php
                        $j = 0;
                        foreach ($getAssigndocument as $eachDocumentDetails) {
                            $j = $j + 1;
                        ?>
                            <tr> 
                                
                                <td><?php echo $eachDocumentDetails['sequence']; ?></td>
                                <td><?php echo $eachDocumentDetails['document_name'] ?></td>
                                <td>
                                    <?php if ($eachDocumentDetails['type'] == 6) { ?>
                                        <form class="form-horizontal" action="<?php echo base_url($settings_link) ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="emd_id" value="<?php echo $eachDocumentDetails['emd_id'] ?>">
                                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Pages</button>
                                        </form>
                                    <?php } ?>
                                    <?php if ($eachDocumentDetails['type'] == 7) { ?>
                                        <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_product/troubleshoot_view') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="emd_id" value="<?php echo $eachDocumentDetails['emd_id'] ?>">
                                            <button type="submit" class="btn btn-outline-info btn-xs waves-effect waves-light">Pages</button>
                                        </form>
                                    <?php } ?>
                                </td>
                                <!-- <td><?php
                                   // if ($eachDocumentDetails['type'] == 6) echo "Document";
                                   // if ($eachDocumentDetails['type'] == 7) echo "Troubleshooting";
                                    ?></td>
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($emanual_lang_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="emd_id" value="<?php echo $eachDocumentDetails['emd_id'] ?>">
                                        <button type="submit" class="btn btn-outline-primary btn-xs waves-effect waves-light"><span class="fa fa-language"></span></button>
                                    </form>
                                </td> -->
                                <!-- <td><?php
                                    //if ($eachDocumentDetails['typeofLink'] == 1) echo "Open Link";
                                    //if ($eachDocumentDetails['typeofLink'] == 2) echo "Secured";
                                    ?></td> -->
                                <!-- <td> <a href="<?php echo base_url('Emanual/emanual_link/emanual_lang/' . $eachDocumentDetails['emd_id'] . '/' . $eachDocumentDetails['emd_id']) ?>" target="_blank"><button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-link-variant"></span></button></a></td> -->
                               <!-- <td>
                                     <form class="form-horizontal" action="<?php echo base_url('Emanual/emanual_link/link_v1') ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="empg_id" value="<?php echo $eachDocumentDetails['emd_id'] ?>">
                                        <button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-link-variant"></span></button>
                                    </form> -->

                                    <!--  <a href="<?php echo base_url('Emanual/emanual_link/link_v1/' . $eachDocumentDetails['emd_id']  . '/' . $eachDocumentDetails['emd_id']) ?>" target="_blank"><button type="submit" class="btn btn-outline-primary waves-effect btn-xs waves-light"><span class="mdi mdi-link-variant"></span></button></a></td>
                               -->
                                <td>
                                    <form class="form-horizontal" action="<?php echo base_url($edit_link) ?>" method="POST"><?= csrf_field() ?>
                                        <input type="hidden" name="emd_id" value="<?php echo $eachDocumentDetails['emd_id'] ?>">
                                        <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
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
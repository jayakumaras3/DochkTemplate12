<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('my_training') ?>">Dashboard</a></li>
                   
                </ol>
            </div>
            <h4 class="page-title"><?php echo $header; ?></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h6>Create New <?php echo $header; ?></h6>
                <form action="<?php echo base_url('verbs/createNewVerb') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                    <div class="mb-3">
                        <label>Verb</label>
                        <input type="text" name="verb" class="form-control" placeholder="Verb" value="" required="" />
                    </div>
                    <div class="mb-3">
                        <label>Negative Verb</label>
                        <input type="text" name="negative_verb" class="form-control" placeholder="Negative Verb" value="" />
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Description" value="" required="" />
                    </div>
                    <div>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="icon-key"> </i>Create</button>
                    </div>
                    <?php if (isset($validation)) : ?>
                        <div class=col-12 col-sm-4>
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted font-13 mb-4"></p>
                <table id="alternative-page-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Verb</th>
                            <th>Negative Verb</th>
                            <th width="10%">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($verbs)) {
                            foreach ($verbs as $k) {
                                $j = $j + 1;
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $k['verb']; ?></td>
                                    <td><?php echo $k['negative_verb']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('verbs/viewVerbDetails') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="verbid" value="<?php echo $k['verbid'] ?>">
                                            <button type="submit" class="btn btn-outline-warning waves-effect btn-xs waves-light"><span class="mdi mdi-pencil-outline"></span></button>
                                        </form>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
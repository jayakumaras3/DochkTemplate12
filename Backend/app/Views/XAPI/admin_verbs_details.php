<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('verbs/admin_verbs') ?>">AR/VR/Sim Verbs</a></li>
                
                </ol>
            </div>
            <h4 class="page-title">Update Verbs</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="card">
        <div class="card-body">
            <h6>Update Verbs</h6>
            <form action="<?php echo base_url('verbs/updateVariable') ?>" method="post" autocomplete="off"><?= csrf_field() ?>
                <div class="mb-3">
                    <label>Verb</label>
                    <input type="text" name="verb" class="form-control" placeholder="Verb" value="<?php echo $specific[0]['verb']; ?>" required="" />
                </div>
                <div class="mb-3">
                    <label>Negative Verb</label>
                    <input type="text" name="negative_verb" class="form-control" placeholder="Negative Verb" value="<?php echo $specific[0]['negative_verb']; ?>" />
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Description" value="<?php echo $specific[0]['description']; ?>" required="" />
                </div>
                <div>
                    <input type="hidden" name="verbid" value="<?php echo $specific[0]['verbid']; ?>">
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
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

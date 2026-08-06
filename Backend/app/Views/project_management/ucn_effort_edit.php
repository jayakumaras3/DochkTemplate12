<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project_Manage/PM_ucn/edit_ucn'); ?>">Edit UCN</a></li>
                </ol>
            </div>
            <h4 class="page-title">Edit Effort and Cost</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo base_url('Project_Manage/PM_ucn/update_effort_data') ?>" method="POST"><?= csrf_field() ?>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <?php

                                ?>
                                <label for="projectname" class="form-label">Instruction Design</label>
                                <input type="number" class="form-control col-md-12" name="ID_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(52, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            }
                                                                                                            ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Content Editor</label>
                                <input type="number" class="form-control col-md-12" name="CE_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(2, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            }
                                                                                                            ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Graphic Design</label>
                                <input type="number" class="form-control col-md-12" name="Graphic_effort" min="1" value="<?php
                                                                                                                    $key1 = array_search(3, array_column($effort_data, 'type_resource'));
                                                                                                                    if ($key1 != '') {
                                                                                                                        echo $effort_data[$key1]['effort'];
                                                                                                                    }
                                                                                                                    ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Visual Design</label>
                                <input type="number" class="form-control col-md-12" name="Media_effort" min="1" value="<?php
                                                                                                                $key1 = array_search(4, array_column($effort_data, 'type_resource'));
                                                                                                                if ($key1 != '') {
                                                                                                                    echo $effort_data[$key1]['effort'];
                                                                                                                }
                                                                                                                ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Visualizer</label>
                                <input type="number" class="form-control col-md-12" name="Viz_effort" min="1" value="<?php
                                                                                                                $key1 = array_search(5, array_column($effort_data, 'type_resource'));
                                                                                                                if ($key1 != '') {
                                                                                                                    echo $effort_data[$key1]['effort'];
                                                                                                                } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Post Production</label>
                                <input type="number" class="form-control col-md-12" name="PP_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(6, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Articulate</label>
                                <input type="number" class="form-control col-md-12" name="AR_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(7, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">3D Modeling</label>
                                <input type="number" class="form-control col-md-12" name="3D_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(8, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">General Programming</label>
                                <input type="number" class="form-control col-md-12" name="GP_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(9, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Quality Assurance</label>
                                <input type="number" class="form-control col-md-12" name="QA_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(10, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Unity3D</label>
                                <input type="number" class="form-control col-md-12" name="Unity_effort" min="1" value="<?php
                                                                                                                $key1 = array_search(51, array_column($effort_data, 'type_resource'));
                                                                                                                if ($key1 != '') {
                                                                                                                    echo $effort_data[$key1]['effort'];
                                                                                                                } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Project Management</label>
                                <input type="number" class="form-control col-md-12" name="PM_effort" min="1" value="<?php
                                                                                                            $key1 = array_search(53, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Subject Matter Expert</label>
                                <input type="number" class="form-control col-md-12" name="SME_effort" min="1" value="<?php
                                                                                                                $key1 = array_search(54, array_column($effort_data, 'type_resource'));
                                                                                                                if ($key1 != '') {
                                                                                                                    echo $effort_data[$key1]['effort'];
                                                                                                                } ?>" />
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p>ADDITIONAL DATA</p>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Add. Cost Desc 1</label>
                                <input type="text" class="form-control col-md-12" name="desc_1" value="<?php
                                                                                                        $key1 = array_search(55, array_column($effort_data, 'type_resource'));
                                                                                                        if ($key1 != '') {
                                                                                                            echo $effort_data[$key1]['remarks'];
                                                                                                        } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Value 1 ($)</label>
                                <input type="number" class="form-control col-md-12" name="value_1" min="1" value="<?php $key1 = array_search(55, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Add. Cost Desc 2</label>
                                <input type="text" class="form-control col-md-12" name="desc_2" value="<?php
                                                                                                        $key1 = array_search(56, array_column($effort_data, 'type_resource'));
                                                                                                        if ($key1 != '') {
                                                                                                            echo $effort_data[$key1]['remarks'];
                                                                                                        } ?>" />
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-1">
                                <label for="projectname" class="form-label">Value 2 ($)</label>
                                <input type="number" class="form-control col-md-12" name="value_2" min="1" value="<?php
                                                                                                            $key1 = array_search(56, array_column($effort_data, 'type_resource'));
                                                                                                            if ($key1 != '') {
                                                                                                                echo $effort_data[$key1]['effort'];
                                                                                                            } ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input type="hidden" name="ucn_id" value="<?php echo $ucn_id; ?>">
                            <div class="text-sm-end  mt-sm-0">
                                <button type="submit" class="btn btn-outline-success waves-effect btn-sm waves-light">
                                    Update Effort
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
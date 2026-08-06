<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url($header_link); ?>"><?php echo $header_link_name ?></a></li><b>&nbsp;>&nbsp;</b>
            <li><a href="<?php echo base_url($header_link2); ?>">Course Report - <?php echo $coursename[0]['course_name'] ?></a></li><b>&nbsp;>&nbsp;</b>
        
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-md-6 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>TOP DEFECTS</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <?php
                    $unprocessed = count($allmissed);
                    if ($unprocessed > 0) {
                    ?>
                        <li><a href="<?php echo base_url('User_login/client_users/process_defects'); ?>"><button type="submit" class="btn btn-sm btn-danger">PROCESS <?php echo '(' . $unprocessed . ')'; ?></button></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="">
                    <ul class="to_do">
                        <?php
                        $counter_def = -1;
                        foreach ($outputVariables as $topDefects) {
                            $counter_def++;
                            echo '<li><p>';
                            if($topDefects['counter']==1){
                                echo $topDefects['counter'].' user missed ' . $topDefects['negative_verb'] . ' ' . $topDefects['variable_description'];
                            }else{
                                echo $topDefects['counter'].' users missed ' . $topDefects['negative_verb'] . ' ' . $topDefects['variable_description'];
                            }
                            echo '</p></li>';
                        }
                        ?>
                        <p>

                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

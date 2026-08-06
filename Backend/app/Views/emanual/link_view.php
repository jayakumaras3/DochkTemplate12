<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico" />
    <meta name="author" content="">

    <title>e-Manual</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>/assets/emanual_design/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo base_url(); ?>/assets/emanual_design/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?php echo base_url(); ?>/assets/emanual_design/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>/assets/emanual_design/css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?php echo base_url(); ?>/assets/emanual_design/css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo base_url(); ?>/assets/emanual_design/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>

    <div id="wrapper">
        <!-- Top Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Left Menu -->
            <ul class="nav navbar-nav navbar-left navbar-top-links">
                <li> <a class="navbar-brand" href="<?php echo base_url('Emanual/emanual_product/documents'); ?>"> <?php echo $document_name; ?></a></li>
            </ul>
            <?php
            $emd_id = $emd_id;
            $empg_id = base64_decode($empg_id);
            $currentPage = isset($empg_id) ? $empg_id : '';
            $totalPages = $totalPages; // Replace with your actual total number of pages

            $currentDocumentIndex = -1;
            foreach ($getAllpagedetails as $index => $pagedetail) {
                if ($pagedetail['document_id'] == $emd_id) {
                    $currentDocumentIndex = $index;
                    break;
                }
            }

            if ($currentDocumentIndex >= 0) {
                $currentDocument = $getAllpagedetails[$currentDocumentIndex];
                $db = \Config\Database::connect(); // Get the current document's page details
                $builder = $db->table('emanual_page as ep');
                $builder->select('ep.*');
                $builder->where('ep.document_id =', $currentDocument['document_id']);
                $pagedata = $builder->get()->getResultArray();

                $currentDocumentPageIds = array_column($pagedata, 'empg_id');

                $currentPageIndex = array_search($currentPage, $currentDocumentPageIds); // finding an current index in an array
                $previousPage = ($currentPageIndex > 0) ? $currentDocumentPageIds[$currentPageIndex - 1] : null; // decrement pageid 
                $nextPage = ($currentPageIndex < count($currentDocumentPageIds) - 1) ? $currentDocumentPageIds[$currentPageIndex + 1] : null; // increment pageid
                $lastindex = $currentPageIndex + 1;
                if (count($currentDocumentPageIds) >= $lastindex) {  // pagination of document
                    $pageIndexCount = $currentPageIndex + 1;
                } elseif ($previousPage !== null) {
                    $pageIndexCount = $currentPageIndex - 1;
                } elseif ($nextPage !== null) {
                    $pageIndexCount = $currentPageIndex + 1;
                } ?>
            <?php } else {
                echo 'Invalid document ID';
            }
            ?>
    </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar navbar-default" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </li>
                <?php echo '<div align="center" style="text-align: center; background-color: #0C9;width:100%">' . $pageIndexCount . ' / ' . $totalPages . '</div>'; ?>
                <?php
                foreach ($pagealldetails as $allpages) {
                    echo '<li>';
                    if ($allpages['page_name'] == $page_name) {
                        echo '<a href="' . base_url('Emanual/emanual_link?tempass=' . $emd_id . '&empg_id=' . $allpages['empg_id']) . '" class="active">' . $allpages['page_name'] . '</a>';
                    } else {
                        echo '<a href="' . base_url('Emanual/emanual_link?tempass=' . $emd_id . '&empg_id=' . $allpages['empg_id']) . '" >' . $allpages['page_name'] . '</a>';
                    }

                    echo '</li>';
                } ?>
            </ul>

        </div>
    </aside>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <?php foreach ($pagecontentdata as $order => $eachpagecontentdata) { ?>
                <?php if ($eachpagecontentdata['type'] == '96') { ?>
                    <div>
                        <?php if ($eachpagecontentdata['content1'] == '') { ?>
                        <?php } else { ?>
                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <img style="max-width: 100%;height: auto;" alt="Responsive image" src="<?php echo base_url() ?>/assets/uploads/emanual_image/<?php echo $eachpagecontentdata['page_id'] ?>/<?php echo $eachpagecontentdata['content1'] ?>" />
                                    </div>
                                </div>
                            </div><br />
                        <?php } ?>
                    </div>
                <?php } elseif ($eachpagecontentdata['type'] == '97') { ?>
                    <div>
                        <?php if ($eachpagecontentdata['content1'] == '') { ?>
                        <?php } else { ?>
                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <video style="max-width: 100%;height: auto;" id="videoElement" controls>
                                            <?php $videoUrl =  base_url("assets/uploads/emanual_video/" . $empg_id . "/" . $eachpagecontentdata['content1']); ?>
                                            <source src="<?= $videoUrl ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            </div>
                            <br />
                        <?php } ?>
                    </div>
                <?php } elseif ($eachpagecontentdata['type'] == '88') { ?>

                    <div class="col-lg-12">
                        <h1 class="page-header"><?php echo $eachpagecontentdata['content1'] ?></h1>
                    </div>

                <?php } elseif ($eachpagecontentdata['type'] == '89') { ?>

                    <div class="col-lg-12">
                        <h2 class="page-header"><?php echo $eachpagecontentdata['content1'] ?></h2>
                    </div>

                <?php } elseif ($eachpagecontentdata['type'] == '100') { ?>

                    <div class="col-lg-12">
                        <h3 class="page-header"><?php echo $eachpagecontentdata['content1'] ?></h3>
                    </div>

                <?php } elseif ($eachpagecontentdata['type'] == '95') { ?>
                    <div class="col-lg-6">
                        <div class="alert alert-info">
                            <?php echo $eachpagecontentdata['content1'] ?>
                        </div>
                    </div>
                <?php } elseif ($eachpagecontentdata['type'] == '94') { ?>
                    <div class="col-lg-6">
                        <div class="alert alert-danger ">
                            <?php echo $eachpagecontentdata['content1'] ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <?php echo $eachpagecontentdata['content1'] ?>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            <?php } ?>
            <!-- ... Your content goes here ... -->
            <?php if ($previousPage !== null) { ?>
                <a href="<?php echo base_url('Emanual/emanual_link?tempass=' . $tempass . '&empg_id=' . $previousPage) ?>" class="float-left">
                    <i class="fa fa-arrow-circle-left"></i>
                </a>
            <?php }

            if ($nextPage !== null) { ?>
                <a href="<?php echo base_url('Emanual/emanual_link?tempass=' . $tempass . '&empg_id=' . $nextPage) ?>" class="float-right">
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            <?php } ?>
        </div>
    </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>/assets/emanual_design/js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url(); ?>/assets/emanual_design/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>/assets/emanual_design/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo base_url(); ?>/assets/emanual_design/js/startmin.js"></script>

</body>

</html>
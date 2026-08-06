<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" type="newtheme/image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico" />
    <title>E-manual</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/carousel/">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>/public/css/emanual_css_bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>/public/css/emanual_carousel.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
</head>
<style type="text/css">
    #zoomtext {
        transform-origin: top left;
        /* Set the transform origin */
        transform: scale(1);
        transition: transform 0.2s ease-in-out;
        overflow: auto;
        /* Add overflow property for scrolling */
    }

    .navbar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .zoom-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        color: white;
    }

    .center-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .carousel-item {
        background-color: white;
    }

    .carousel-control-next-icon {
        color: red;
    }
</style>

<body>
    <header>

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
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="#"> <?php echo $document_name; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link">

                        </a>
                    </li>
                </ul>
                <div class="zoom-buttons">
                    <span id="zoomOut" class="fa fa-search-minus"></span>&nbsp;
                    <span id="zoomIn" class="fa fa-search-plus"></span>
                </div>
                <?php
                echo '<div class="pull-right">';

                if ($previousPage !== null) {
                    echo '<a href="emanual_link/' . $tempass . '/' . $previousPage . '"><button class="btn btn-sm btn-dark">Previous</button></a>&nbsp;&nbsp;';
                }
                echo '<a style="color:white;font-size:80%;border:1px solid grey;padding :2px">' . $pageIndexCount . ' / ' . $totalPages . '</a>&nbsp;&nbsp;';
                if ($nextPage !== null) {
                    echo '<a href="emanual_link/' . $tempass . '/' . $nextPage . '"><button class="btn btn-sm btn-dark">Next</button></a>';
                }

                echo '</div>';
                ?>
            </div>
        </nav>
    </header>
    <article>
        <section id="zoomtext">
            <main role="main">

                <div class="container">
                    <div class="col-md-12"><br/>

                        <?php foreach ($pagecontentdata as $order => $eachpagecontentdata) { ?>
                            <?php if ($eachpagecontentdata['type'] == '96') { ?>
                                <div>
                                    <?php if ($eachpagecontentdata['content1'] == '') { ?>
                                    <?php } else { ?>
                                        <div class="head bg-dot30 np tac">
                                            <img src="<?php echo base_url() ?>/assets/uploads/emanual_image/<?php echo $eachpagecontentdata['page_id'] ?>/<?php echo $eachpagecontentdata['content1'] ?>" class="img-squre img-thumbnail" />
                                        </div><br />
                                    <?php } ?>
                                </div>
                            <?php } elseif ($eachpagecontentdata['type'] == '97') { ?>
                                <div>
                                    <?php if ($eachpagecontentdata['content1'] == '') { ?>
                                    <?php } else { ?>
                                        <div id="videoContainer">
                                            <video id="videoElement" controls>
                                                <?php $videoUrl =  base_url("assets/uploads/emanual_video/" . $empg_id . "/" . $eachpagecontentdata['content1']); ?>
                                                <source src="<?= $videoUrl ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                        <br />
                                    <?php } ?>
                                </div>

                            <?php } elseif ($eachpagecontentdata['type'] == '88' || $eachpagecontentdata['type'] == '100') { ?>
                                <div class="row featurette">
                                    <h1><?php echo $eachpagecontentdata['content1'] ?></h1>
                                </div>
                            <?php } elseif ($eachpagecontentdata['type'] == '89') { ?>
                                <div>
                                    <h2><?php echo $eachpagecontentdata['content1'] ?></h2>
                                </div>
                            <?php } elseif ($eachpagecontentdata['type'] == '90') { ?>
                                <div>
                                    <h3><?php echo $eachpagecontentdata['content1'] ?></h3>
                                </div>
                            <?php } elseif ($eachpagecontentdata['type'] == '91') { ?>
                                <div>
                                    <p><?php echo $eachpagecontentdata['content1'] ?></p>
                                </div>
                            <?php } elseif ($eachpagecontentdata['type'] == '92') { ?>
                                <div>
                                    <ul>
                                        <li><?php echo $eachpagecontentdata['content1'] ?></li>
                                    </ul>
                                </div>
                            <?php } else { ?>
                                <div><?php echo $eachpagecontentdata['content1'] ?></div>
                            <?php } ?>

                        <?php } ?>
                    </div>
                </div>

            </main>
        </section>
    </article>
    <?php if ($previousPage !== null) { ?>
        <a class="carousel-control-prev" href="<?php echo base_url('Emanual/emanual_link?tempass=' . $tempass . '&empg_id=' . base64_encode($previousPage)) ?>" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color:grey"></span>
            <span class="sr-only">Previous</span>
        </a>
    <?php }
    if ($nextPage !== null) { ?>
        <a class="carousel-control-next" href="<?php echo base_url('Emanual/emanual_link?tempass=' . $tempass . '&empg_id=' . base64_encode($nextPage)) ?>" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true" style="background-color:grey"></span>
            <span class="sr-only">Next</span>
        </a>
    <?php } ?>

</body>

</html>
<script>
    var zoom = 1;
    var zoomStep = 0.2;

    document.getElementById("zoomIn").addEventListener("click", function() {
        zoom += zoomStep;
        document.getElementById("zoomtext").style.transform = "scale(" + zoom + ")";
    });
    document.getElementById("zoomOut").addEventListener("click", function() {
        if (zoom > zoomStep) {
            zoom -= zoomStep;
            document.getElementById("zoomtext").style.transform = "scale(" + zoom + ")";
        }
    });
</script>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="<?php echo base_url(); ?>/public/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>/public/js/bootstrap.min.js"></script>
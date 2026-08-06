<?php if (!empty($proposalImage)) { ?>

    <?php foreach ($proposalImage as $eachproposalImage) {

        $path = ROOTPATH . 'assets/assets/uploads/proposal_image/' . $proposal_id . '/' . $eachproposalImage['details_03'];

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data); ?>

            <div style="page-break-after: always; text-align: center;">
                <img src="<?php echo $base64; ?>" alt="cover image" style="max-width: 100%; height: auto;">
            </div>

<?php   }
    }
} ?>
<style>
    .section {
        margin-bottom: 10px;
    }

    .section h3 {
        background-color: #f9c8a0;
        /* Light orange color */
        padding: 10px;
        border-radius: 2px;
        font-family: Arial, sans-serif;
    }

    .section p {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin-top: 10px;
        margin-left: 10px;
    }
</style>

<div class="section">
    <h3>About Client</h3>
    <p><?php echo $get_proposal_data[0]['about_client']; ?></p>
</div>
<div class="section">
    <h3>Understanding Requirement</h3>
    <p><?php echo $get_proposal_data[0]['requirement']; ?></p>
</div>
<div class="section">
    <h3>Proposed Solution</h3>
    <p><?php echo $get_proposal_data[0]['solution']; ?></p>
</div>
<div class="section">
    <h3>Assumptions</h3>
    <p><?php echo $get_proposal_data[0]['assumption']; ?></p>
</div>
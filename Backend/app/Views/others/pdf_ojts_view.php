<html>

<head>
    <style>
        /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            font-family: 'tahoma', 'DejaVu Sans', sans-serif;
            margin: 20px;
            padding: 25px;
            padding-top: 110px;
            position: relative;
            min-height: 100vh;
            /* margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm; */
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: auto;
            width: 100%;
        }

        /** Define the footer rules **/
        @page {
            margin-bottom: 35px;
        }

        /* Adjust as needed for footer height */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            /* Height of the footer area */
            margin-bottom: 5px;
            /* Offset to account for footer height */
        }

        .footer-line {
            border-top: 1px solid black;
            /* Style of the line */
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .header-img {
            width: 100%;
            height: auto;
        }

        /* Footer image: Fixed at the bottom of the page */
        /* .footer-img {
            bottom: 0px;
            left: 0px;
            width: 100%;
            color: #CCC;
            border: 0; */
        /* padding: 8px; */
        /* Add some padding to make sure the footer doesn't touch content */
        /* } */

        .page-break {
            page-break-before: always;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            color: black;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        th {
            background-color: #19a4a3;
            color: white;
            padding: 8px;
            text-align: center;
            border: 1px solid black;
            word-wrap: break-word;
        }

        td {
            padding: 15px;
            border: 1px solid black;
            word-wrap: break-word;
            /* font-size: 12px; */
        }

        .signature-section {
            width: 100%;
            padding-left: 20px;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-bottom: 120px;
            /* Adjust space to avoid overlapping with footer */
        }

        .header-section {
            width: 150%;
            padding-left: 20px;
            display: flex;
            justify-content: space-between;
            /* margin-top: 10px; */
            padding-bottom: 20px;

            /* Adjust space to avoid overlapping with footer */
        }

        .signature-section,
        .signature-section {
            display: inline-block;
            width: 55%;
        }

        .leader-header {
            position: absolute;
            width: 400px;
            left: 30px;
            margin-bottom: 5%;
        }

        .learner-header {
            position: absolute;
            width: 300px;
            left: 55%;
            margin-bottom: 5%;
        }

        .leader-signature {
            position: absolute;
            margin-bottom: 5%;
            left: 30px;
            width: 300px;
        }

        .learner-signature {
            position: absolute;
            margin-bottom: 5px;
            left: 77%;
            width: 300px;
        }

        .signature-line {
            width: 250px;
            border-bottom: 1px solid black;
            margin-bottom: 5px;
        }

        .signature-label {
            font-size: 12px;
            margin-bottom: 0;
        }

        .signatures {
            display: flex;
            position: absolute;
            bottom: 100px;
            width: 100%;
            justify-content: space-between;
            padding-left: 20px;
            padding-right: 20px;
        }

        .signature {
            text-align: center;
            width: 45%;
        }
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>
        <img src="<?php echo $header; ?>" alt="Header Image" class="header-img" />
    </header>

    <footer>
        <div class="footer-line"></div>
        <!-- <img src="<?php echo $footer; ?>" alt="Footer Image" class="footer-img" /> -->
        <?php //$j = 0;
        // foreach ($ojts_consolidatedData as $data) {
        //     $j = $j + 1; 
        ?>

        <!-- <div style="position: fixed; left: 0; right: 0;text-align: center;padding:20px;font-size: 8px;font-weight:bold">
                <?php //echo str_replace('_', ' ', $data['filename']); 
                ?>
            </div>
            <div style="position: fixed; left: 0; right: 0;text-align: right;padding-right:20px;padding-top:5px;font-size: 6px;font-weight:bold">
                1
            </div> -->
        <!-- <?php  //} 
                ?> -->

    </footer>


    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <div class="row" style="margin-bottom: 15px;">
            <div style="text-align: left; font-size: 16px;"><b><?php echo str_replace('_', ' ', $filename); ?></b></div>
        </div>
        <div class="row" style="margin-bottom: 15px;">
            <div class="learner-header">
                <div style="text-align: right;font-size: 12px;font-weight:bold;">Learner Name: _______________________________</div>
            </div>
        </div>
        <!-- <br /> -->
        <div class="row">
            <table style="font-size: 12px;">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Task</th>
                        <th>Remarks</th>
                        <th>Manager<br />Checkoff</th>
                        <th>Self<br />Checkoff</th>
                    </tr>
                </thead>

                <tbody class="row_position">
                    <?php $j = 0;
                    foreach ($ojts_consolidatedData as $data) {
                        $j = $j + 1; ?>
                        <tr>
                            <td width="5%"><?php echo $j ?></td>
                            <td width="40%"><b><?php echo (isset($data['title']) && $data['title'] != '') ? $data['title'] . ':' : ''; ?></b> <?php echo $data['task']; ?></td>
                            <td></td>
                            <td width="8%" style="text-align: center;">
                                <input type="checkbox" name="instructor_checkoff_<?php echo $j; ?>" value="1">
                            </td>
                            <td width="8%" style="text-align: center;">
                                <input type="checkbox" name="self_checkoff_<?php echo $j; ?>" value="1">
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <br /><br />

        <div class="signature-section" style="font-size: 12px;font-weight:bold;">
            <div class="leader-signature">
                <div style=" text-align: left;margin-bottom: 8px;">__________________________</div>
                <div style=" text-align: left;margin-bottom: 8px;">Signature of Manager</div>
                <div style=" text-align: left;">Date: </div>

            </div>
            <div class="learner-signature">
                <div style="margin-bottom: 8px;">________________________</div>
                <div style="margin-bottom: 8px;">Signature of Learner</div>
                <div>Date: </div>

            </div>
        </div>

    </main>
</body>

</html>
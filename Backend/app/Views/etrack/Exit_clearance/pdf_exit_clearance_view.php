<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11.5px;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: black;
            font-weight: bold;
        }

        h4 {
            margin: 0;
            margin-bottom: 8px;
            text-align: center;
        }

        h2 {
            margin: 0;
            margin-bottom: 8px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div style=" margin-bottom: 2px;">
        <img src="<?php echo $logo; ?>" alt="Header Image" class="header-img" height="40px" />
        
    </div>
    <h2>Exit Clearance</h2>
    <?php
    foreach ($getExitclearanceheader as $header) {
        // Get header status
        $header_status = '';
        foreach ($getUserexitformstatus as $user_status_row) {
            if ($user_status_row['fk_header_id'] == $header['header_id']) {
                switch ($user_status_row['status']) {
                    case 3:
                        $header_status = 'Initiate';
                        break;
                    case 2:
                        $header_status = 'Approved';
                        break;
                    case 1:
                        $header_status = 'Rejected';
                        break;
                    case 0:
                        $header_status = 'Delete';
                        break;
                }
                break;
            }
        }
        ?>

        <h4><?php echo $header['description']; ?><?php echo !empty($header_status) ? ' - ' . $header_status : ""; ?></h4>

        <table>
            <thead>
                <tr>
                    <th width='5%'>#</th>
                    <th>Description</th>
                    <th width="8%">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $index = 1;
                foreach ($getExitclearanceSubheader as $subheader) {
                    if ($subheader['sub_main_header'] == $header['header_id']) {
                        $status_text = '';
                        $comment_value = '';

                        foreach ($getUserexitformstatus as $user_status_row) {
                            if ($user_status_row['fk_header_id'] == $subheader['header_id']) {
                                if ($subheader['description'] === 'Comment') {
                                    $comment_value = htmlspecialchars($user_status_row['comment'] ?? '');
                                } else {
                                    switch ($user_status_row['status']) {
                                        case 3:
                                            $status_text = 'Done';
                                            break;
                                        case 2:
                                            $status_text = 'NA';
                                            break;
                                        case 1:
                                            $status_text = 'Yes';
                                            break;
                                        case 0:
                                            $status_text = 'No';
                                            break;
                                    }
                                }
                                break;
                            }
                        }

                        if ($subheader['description'] === 'Comment') {
                            echo "<tr>
                                <td>Note</td>
                                <td colspan='2'>" . (isset($comment_value) ? htmlspecialchars($comment_value) : '') . "</td>
                            </tr>";

                        } else {
                            echo "<tr>
                                    <td width='5%'>{$index}</td>
                                    <td>{$subheader['description']}</td>
                                    <td width='8%'>{$status_text}</td>
                                  </tr>";
                            $index++;
                        }
                    }
                }
                ?>
            </tbody>
        </table>

    <?php } ?>
    <div style="text-align: left; margin-top: 50px;">
        <p><strong>Name:</strong></p>
        <p><strong>Designation:</strong></p>
    </div>

</body>

</html>
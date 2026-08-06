<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
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

        h2 {
            margin: 0;
            margin-bottom: 8px;
            text-align: center;
        }

        h5 {
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
    <h2>Exit Interview</h2>
    <table>
        = <thead>
            <tr>
                <th>#</th>
                <th width="40%">Description</th>
                <th width="50%">Comment</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Employee</td>
                <td><?php echo $username; ?></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Mobile Number</td>
                <td><?php echo isset($userexitInterdata[0]['mobile_number']) ? $userexitInterdata[0]['mobile_number'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Personal Email id</td>
                <td><?php echo isset($userexitInterdata[0]['personal_email_id']) ? $userexitInterdata[0]['personal_email_id'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Why did you begin looking for a new job? </td>
                <td><?php echo isset($userexitInterdata[0]['why_new_job']) ? $userexitInterdata[0]['why_new_job'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>5</td>
                <td>How would you describe the culture of our company? </td>
                <td><?php echo isset($userexitInterdata[0]['culture_of_our_comany']) ? $userexitInterdata[0]['culture_of_our_comany'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>6</td>
                <td>What could have been done for you to remain employed here? </td>
                <td><?php echo isset($userexitInterdata[0]['remain_employed_here']) ? $userexitInterdata[0]['remain_employed_here'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>7</td>
                <td>If you could change anything about your job or the company, what would you change? </td>
                <td><?php echo isset($userexitInterdata[0]['job_company_change']) ? $userexitInterdata[0]['job_company_change'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>8</td>
                <td>Were you satisfied with the way you were managed? </td>
                <td><?php echo isset($userexitInterdata[0]['satisfied_manged']) ? $userexitInterdata[0]['satisfied_manged'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>9</td>
                <td>Did you have clear goals and objectives? </td>
                <td><?php echo isset($userexitInterdata[0]['objectives']) ? $userexitInterdata[0]['objectives'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>10</td>
                <td>Did you receive constructive feedback to help you improve your performance? </td>
                <td><?php echo isset($userexitInterdata[0]['performance']) ? $userexitInterdata[0]['performance'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>11</td>
                <td>Would you consider coming back to work here in the future? In what area or function? What would need
                    to change? </td>
                <td><?php echo isset($userexitInterdata[0]['work_here_future']) ? $userexitInterdata[0]['work_here_future'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>12</td>
                <td>Would you recommend a friend to pursue employment with this company? </td>
                <td><?php echo isset($userexitInterdata[0]['recommend_employment']) ? $userexitInterdata[0]['recommend_employment'] : ''; ?>
                </td>
            </tr>
            <tr>
                <td>13</td>
                <td>Any other feedback </td>
                <td><?php echo isset($userexitInterdata[0]['feedback']) ? $userexitInterdata[0]['feedback'] : ''; ?>
                </td>
            </tr>
        </tbody>
    </table>
</body>
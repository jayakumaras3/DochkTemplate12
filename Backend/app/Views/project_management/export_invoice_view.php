<style>
    /* Add margin and center the table */
    table {
        width: 100%;
        /* border-collapse: collapse; */
        margin: 20px auto;
        /* Center table with margin */
        padding: 10px;
        /* Optional: Add padding inside table for better space */
    }

    /* Add padding to table cells for proper spacing */
    td {
        padding: 2px;
        vertical-align: top;
        /* Align content to the top */
    }

    /* Optional: Add a light border for readability */
    /* table, th, td {
        border: 1px solid #ddd;
    } */

    /* Add spacing for image and text alignment */
    img {
        max-width: 100%;
        height: auto;
    }

    h2 {
        text-align: center;
        margin-top: 0;
    }

    h4 {
        margin: 0;
    }
</style>
<style>
    hr {
        border: 0;
        border-top: 2px solid #000;
        /* margin: 20px auto; */
    }
</style>
<?php
$path = ROOTPATH . 'public/aristo_assets/images/talentquest.jpg';

if (file_exists($path)) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
} ?>

<!-- Table starts here -->
<table>
    <tr>
        <td rowspan="5"><img src="<?php echo $base64; ?>" alt="logo"></td>
        <td style="width: 30%;">Invoice No.</td>
        <td style="width: 30%;">SPINV-000<?php echo $invoice_details[0]['invoice_id'] ?></td>
    </tr>
    <tr>

        <td>Invoice Date</td>
        <td><?php echo  date('M d, Y', strtotime($invoice_details[0]['inv_dt'])) ?></td>
    </tr>
    <tr>

        <td>PO Number</td>
        <td>12344</td>
    </tr>
    <tr>

        <td>Contract</td>
        <td></td>
    </tr>
    <tr>

        <td>Project</td>
        <td></td>
    </tr>
    <tr>
        <td  style="width: 40%;"></td>
        <td  style="width: 50%;"> <h2>Sales Invoice</h2></td>
        <td  style="width: 40%;"></td>
    </tr>
    <tr>
        <td style="width: 50%;">
            <h4><?php echo $invoice_details[0]['address'] ?></h4>
        </td>
        <td></td>
        <td></td>
    </tr><br />
      <tr>
            <td><b>Description</b></td>
            <td><b>Date</b></td>
            <td><b>Amount</b></td>
        </tr>
        <tr>
            <td><?php echo $invoice_details[0]['description'] ?></td>
            <td><?php echo date('m/d/Y', strtotime($invoice_details[0]['inv_dt'])); ?></td>
            <td><?php echo $invoice_details[0]['value'] ?></td>
        </tr>
        <br />
        <tr>
            <td></td>
            <td><b>Subtotal</b></td>
            <td><?php echo $invoice_details[0]['value'] ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Total Tax</td>
            <td>0.00</td>
        </tr>
        <tr>
            <td></td>
            <td><b>Total $ Incl. Tax</b></td>
            <td><?php echo $invoice_details[0]['value'] ?></td>
        </tr>
    </table>

<br />
<hr>
<p>TalentQuest, LLC 2400 Riverstone Bvd #4277 Canton, GA 30114</p>
<p><b style="color:red">Remit to by Check: </b> TalentQuest, LLC PO Box 96491, Charlotte, NC 28296-0491</p>
<p>For Billing Inquiries Contact: US-Accounting@talentquest.com\Phone: 404.266.9368</p>
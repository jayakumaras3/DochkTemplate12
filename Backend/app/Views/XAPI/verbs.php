<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dochek Verbs </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="jumbotron text-center">
        <h1>Dochek Verbs</h1>
        <p>Resize this responsive page to see the effect!</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <table class="table  table-sm table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th>Verb</th>
                            <th>Description</th>
                            <th width="10%">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $j = 0;
                        if (!empty($verbs)) {
                            foreach ($verbs as $k) {
                                $j = $j + 1;
                        ?>
                                <tr>
                                    <td><?php echo $j; ?></td>
                                    <td><?php echo $k['verb']; ?></td>
                                    <td><?php echo $k['description']; ?></td>
                                    <td>
                                        <form class="form-horizontal" action="<?php echo base_url('verbs/viewVerbDetails') ?>" method="POST"><?= csrf_field() ?>
                                            <input type="hidden" name="ticketID" value="<?php echo $k['verbid'] ?>">
                                            <button type="submit" class="btn btn-sm widget-icon btn-warning">View</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
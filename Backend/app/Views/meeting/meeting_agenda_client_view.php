<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Meeting Agenda</title>
    <link rel="icon" type="image/ico" href="<?php echo base_url(); ?>/public/img/favicon.ico" />
    <link href="<?php echo base_url(); ?>/public/css/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>/public/css/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/jquery/jquery.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/jquery/jquery-ui.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/jquery/jquery-migrate.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/bootstrap/bootstrap.min.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/fancybox/jquery.fancybox.pack.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/uniform/jquery.uniform.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/bootstrap/bootstrap-file-input.js'></script>


    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/knob/jquery.knob.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/sparkline/jquery.sparkline.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/flot/jquery.flot.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins/flot/jquery.flot.resize.js'></script>

    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/plugins.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/actions.js'></script>
    <script type='text/javascript' src='<?php echo base_url(); ?>/public/newtheme/js/charts.js'></script>


</head>
<style>
    table,
    td {
        border-collapse: collapse;
        border: 1px solid black;
        padding: 5px;
        font-size: 92%;
    }

    table {
        width: 100%;
    }
</style>

<body style="padding:2%">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="x_panel">
                    <h6><?php echo isset($meetingagenda[0]['projectname']) ? 'Project Name : ' . $meetingagenda[0]['projectname'] : '' ?></h6>
                    <h6><?php echo $meetingagenda[0]['description'] ?></h6>
                    <table>

                        <tr>
                            <th colspan="4" style="text-align: center;background-color:cadetblue;color:white;font-weight:bold;font-size:100%">Meeting Agenda</th>
                        </tr>
                        <tr style="color:black">
                            <td style="width:10%;">Date:</td>
                            <td style="width:40%;"><?php echo date('m-d-Y', strtotime($meetingagenda[0]['start_date'])); ?></td>
                            <td style="width:10%;">Time:</td>
                            <td style="width:40%;" onclick="showpsttime('<?php echo $meetingagenda[0]['start_date'] . ' ' . $meetingagenda[0]['time']; ?>');">IST : <?php echo $meetingagenda[0]['time']; ?>
                                <br>
                                <div id="demo"></div>
                            </td>

                        </tr>
                        <tr style="color:black">
                            <td style="width:10%;">Attendees:</td>
                            <td style="width:40%;"><?php echo $meetingagenda[0]['attendees'] ?></td>

                            <td style="width:10%;">Meeting link:</td>
                            <td style="width:40%;"><?php if ($meetingagenda[0]['meeting_link'] != '') { ?>
                                    <a href="<?php echo $meetingagenda[0]['meeting_link'] ?>">Click here to join the meeting</a>
                                <?php  } else {
                                                    } ?>
                            </td>
                        </tr>
                        <tr style="background-color:cadetblue;color:white;font-weight:bold;font-size:medium">
                            <td style="width:10%;">#</td>
                            <td style="width:40%;">Project Status and Topics for discussion</td>
                            <td style="width:10%;">Completion Date</td>
                            <td style="width:40%;">Remarks</td>
                        </tr>
                        <?php $k = 0;
                        $currentdt = new DateTime();
                        //print_r(count($meetingagenda));

                        foreach ($meetingagenda as $eachmeetingagenda) {
                            //print_r($eachmeetingagenda['project_status']);
                            if (!empty($eachmeetingagenda['project_status'])) {
                                $add4days = date('Y-m-d', strtotime($meetingagenda[0]['start_date'] . ' +' . 4 . ' day'));
                                $disabledate = new DateTime($add4days);
                                $k = $k + 1;
                        ?>
                                <tr>
                                    <td style="width:10%;"><?php echo $k ?></td>
                                    <td style="width:40%;"><?php echo $eachmeetingagenda['project_status'] ?></td>
                                    <td><?php if ($eachmeetingagenda['completion_dt'] != '0000-00-00') {
                                            echo date('m-d-Y', strtotime($eachmeetingagenda['completion_dt']));
                                        } else {
                                            echo '';
                                        } ?></td>
                                    <?php if ($currentdt >= $disabledate) { ?>
                                        <td style="width:40%;" id="myP" contentEditable="false" onBlur="updateDate(this,'remarks','<?php echo $eachmeetingagenda['id_ma'] ?>')"><?php echo $eachmeetingagenda['remarks'] ?></td>
                                    <?php } else { ?>
                                        <td style="width:40%;" id="myP" contentEditable="true" onBlur="updateDate(this,'remarks','<?php echo $eachmeetingagenda['id_ma'] ?>')"><?php echo $eachmeetingagenda['remarks'] ?></td>
                                    <?php } ?>
                                </tr>

                        <?php }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function updateDate(element, column, id) {

        var value = element.innerText;
        console.log(value + column + id);
        // console.log(startdt);



        ///conole.log($(this).find(':selected').data('id'));
        $.ajax({
            url: '<?php echo base_url('meeting_agenda_client/updateremarksformat') ?>',
            type: 'post',
            data: {
                value: value,
                column: column,
                id: id
            },
            success: function(data) {
                var obj = JSON.parse(data);

                console.log(obj);

                if (obj.status === 'OK') {
                    console.log('inside on condition');
                    location.reload(true);


                } else {
                    alert(obj.status, 'Something Went Wrong! Please contact Site Admin!');
                }
                //location.reload(true);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('request failed');
            }

        })

    }

    function showpsttime(dttime) {
        const date = new Date(dttime);
        console.log(date);
        const pst = date.toLocaleTimeString('en-US', {
            timeZone: 'America/Los_Angeles',
        });
        document.getElementById("demo").innerHTML = 'PST : ' + pst;

    }
</script>
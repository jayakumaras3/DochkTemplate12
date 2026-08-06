<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo base_url('Project/project_plan') ?>">Project Plan</a></li>
             
                </ol>
            </div>
            <h4 class="page-title">Link</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form class="form" action="<?php echo base_url('Project/project_plan/updateitemlink/' . $projectid . '/' . $dt_id); ?>" method="POST"><?= csrf_field() ?>
                            <div class="input-group file">
                                <select name="link" class="form-control">
                                    <option value="0">Select Item</option>
                                    <option value="0">Unlink</option>
                                    <?php foreach ($itemdescription as $eachitemdescrip) {
                                        //print_r($eachitemdescrip); 
                                    ?>
                                        <option value="<?php echo $eachitemdescrip['dt_id'] ?>"><?php echo $eachitemdescrip['dt_id'] . ' - ' . $eachitemdescrip['item_description'] ?></option>
                                    <?php } ?>
                                </select>
                            </div><br/>
                            <div class="input-group file">
                                <button type="submit" id="sub" class="btn btn-info block" class="form-control">
                                    <i class="ace-icon fa fa-key bigger-110"></i> <?php echo lang('Buttons.Add') ?>
                                </button>

                            </div>

                            <?php if (isset($validation)) : ?>
                                <div class=col-12 col-sm-4>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script LANGUAGE="JavaScript">
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        $('#startdate').datepicker({
            dateFormat: 'yy-mm-dd',
        });

        $('#enddate').datepicker({
            dateFormat: 'yy-mm-dd',
        });

        $('#enddate').change(function() {
            var s = $('#startdate').datepicker("getDate");
            var e = $('#enddate').datepicker("getDate");
            var diff = $('#startdate').datepicker("getDate") - $('#enddate').datepicker("getDate");


            var totalDays = diff / (1000 * 60 * 60 * 24) * -1;

            // Get the difference in whole weeks
            var wholeWeeks = totalDays / 7 | 0;
            // Estimate business days as number of whole weeks * 5
            var days = wholeWeeks * 5;
            console.log(days);
            // If not even number of weeks, calc remaining weekend days
            if (totalDays % 7) {
                s.setDate(s.getDate() + wholeWeeks * 7);

                while (s < e) {
                    s.setDate(s.getDate() + 1);

                    // If day isn't a Sunday or Saturday, add to business days
                    if (s.getDay() != 0 && s.getDay() != 6) {
                        ++days;
                    }
                }
            }
            //$('#diff').text(days);
            document.getElementById("days").value = days;
        });
    });
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,

        })
    });

    function updateDate(element, column, id) {

        var value = element.innerText;
        console.log(value + column + id);

        ///conole.log($(this).find(':selected').data('id'));
        $.ajax({
            url: '<?php echo base_url('event/updateEventformat') ?>',
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
                    if (column == 'duration') {
                        location.reload(true);
                    }
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
</script>
<script>
    $("#button").click(function() {
        $("#leveldata").toggle();
    });
    $("#button").click(function() {
        $("#userdata").toggle();
    });

    function myFunction(element, id) {
        var value = element;
        console.log(value);
        let column = 'responsible';

        $.ajax({
            url: '<?php echo base_url('event/updateEventformat') ?>',
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

                    alert('error', 'Something Went Wrong! Please contact Site Admin!');
                }
                //location.reload(true);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('request failed');
            }

        })

    };

    function myFunctionlevel(element, id) {

        var value = element;
        console.log(value);
        let column = 'level';

        $.ajax({
            url: '<?php echo base_url('Project/event/updateEventformat') ?>',
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

                    alert('error', 'Something Went Wrong! Please contact Site Admin!');
                }
                //location.reload(true);
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('request failed');
            }

        })

    };
</script>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var contented = this.nextElementSibling;
            if (contented.style.display === "block") {
                contented.style.display = "none";
            } else {
                contented.style.display = "block";

            }
        });
    }
</script>
<script>
    var coll = document.getElementsByClassName("collapsiblee");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var contented = this.nextElementSibling;
            if (contented.style.display === "block") {
                contented.style.display = "none";
            } else {
                contented.style.display = "block";

            }
        });
    }
</script>
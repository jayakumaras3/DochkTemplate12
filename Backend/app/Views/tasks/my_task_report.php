<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">My Task Report</h4>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table  w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Points</th>
                                <th>To Do</th>
                                <th>In Progress</th>
                                <th>Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            date_default_timezone_set('America/New_York');
                            for ($i = 1; $i <= 12; $i++) {
                            ?>
                                <tr>
                                    <td>
                                        <?php
                                        $month = date('M', mktime(0, 0, 0, $i, 10));
                                        echo strtoupper($month);
                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
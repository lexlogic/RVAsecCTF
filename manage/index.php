<?php
require_once '../Init.php';
$user = new User();
if($user->isLoggedIn()) {
    if($user->hasPermission('admin')) {
        $getQuestions = DB::getInstance()->getAssoc("SELECT * FROM questions");
        foreach($getQuestions->results() as $results) {
            $questions[] = $results;
            $questionsCount = count($questions);
        }
        $getUsers = DB::getInstance()->getAssoc("SELECT * FROM users");
        foreach($getUsers->results() as $results) {
            $users[] = $results;
            $usersCount = count($users);
        }

        $page = new Page;
        $page->setTitle('Manage CTF');
        $page->startBody();
        ?>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title" style="padding: 5px;">Active Questions</div>
                    <div style="float: right; padding: 5px;">
                        <a href="javascript:$('#new-question').modal('show');" class="btn btn-info">Add New Question</a>
                        <a href="javascript:$('#edit-user').modal('show');" class="btn btn-info">Modify User</a>
                    </div>
                </div>

                <table class="table table-bordered table-responsive">
                    <thead>
                    <?php
                    if(!empty($questions)) { ?>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Tier</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($questions as $question) {
                        echo "<tr>";
                        echo "<td>".$question['question']."</td>";
                        echo "<td>".$question['answer']."</td>";
                        echo "<td>".$question['tier']."</td>";
                        echo "</tr>";
                    }
                    } else { ?>
                    </tbody>
                    <tr>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    echo "<tr>";
                    echo "<td align='center'>No Questions Yet!</td>";
                    echo "</tr>";
                    } ?>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">CTF Analytics</div>

                    <div class="panel-options">
                        <a href="#" data-rel="collapse"><i class="entypo-down-open" data-toggle="tooltip" data-placement="top" title="" data-original-title="Collapse"></i></a>
                        <a href="#" data-rel="reload"><i class="entypo-arrows-ccw" data-toggle="tooltip" data-placement="top" title="" data-original-title="Refresh"></i></a>
                    </div>
                </div>
                <div id="chartContainer" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
        <?php
        $page->endBody();
        include 'modals.php';
        echo $page->render('../includes/template.php');
    } else {
        Redirect::to('../dashboard');
    }
} else {
    Redirect::to('../login');
}
?>

<script type="text/javascript">
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer",
            {
                data: [
                    {
                        type: "doughnut",
                        showInLegend: true,
                        dataPoints: [
                            {  y: <?php echo escape($usersCount); ?>, legendText:"Flags Complete", indexLabel: "Flags Complete" },
                            {  y: <?php echo escape($usersCount); ?>, legendText:"Flags Left", indexLabel: "Flags Left" },
                            {  y: <?php echo escape($usersCount); ?>, legendText:"Active Users", indexLabel: "Active Users" }
                        ]
                    }
                ]
            });

        chart.render();
    }
</script>
<script type="text/javascript" src="../assets/js/canvasjs.min.js"></script>
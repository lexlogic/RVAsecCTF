<?php
require_once '../Init.php';
$getTeams = DB::getInstance()->getAssoc("SELECT * FROM teams ORDER BY points DESC");
foreach($getTeams->results() as $results) {
    $teams[] = $results;
}
?>
<link rel="stylesheet" href="../assets/css/neon.css">
<meta http-equiv="refresh" content="20">
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="panel-title">Scoreboard</div>

        <div class="panel-options">
            <a href="#" data-rel="collapse"><i class="entypo-down-open" data-toggle="tooltip" data-placement="top" title="" data-original-title="Collapse"></i></a>
            <a href="#" data-rel="reload"><i class="entypo-arrows-ccw" data-toggle="tooltip" data-placement="top" title="" data-original-title="Refresh"></i></a>
        </div>
    </div>

    <table class="table table-bordered table-responsive">
        <thead>
        <tr>

            <th width="1%">Position</th>
            <th>Team</th>
            <th width="10%">Score</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(!empty($teams)) {
            $x = 1;
            foreach ($teams as $team) {
                echo '<tr>';
                echo '<td>'.$x.'</td>';
                echo '<td>'.$team['name'].'</td>';
                echo '<td>'.$team['points'].'</td>';
                echo '</tr>';
                $x++;
            }
        }
        ?>
        </tbody>
    </table>
</div>

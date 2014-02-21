<?php
require_once '../Init.php';
$user = new User();
if($user->isLoggedIn()) {
    $getMembers = DB::getInstance()->getAssoc("SELECT * FROM users WHERE team = ?", array($user->data()->team));
    foreach($getMembers->results() as $results) {
        $members[] = $results;
    }
    $getTeam = DB::getInstance()->getAssoc("SELECT * FROM teams WHERE `name` = ?", array($user->data()->team));
    foreach($getTeam->results() as $results) {
        $teamInfo[] = $results;
    }
    $page = new Page;
    $page->setTitle('Dashboard');
    $page->startBody();
    if(!empty($teamInfo)) {
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div style="padding: 10px 15px; margin-top: 0; margin-bottom: 0;font-size: 14px;" align="center">
                            Team's Score
                        </div>
                    </div>
                    <?php
                    if(!empty($teamInfo)) {
                        foreach ($teamInfo as $info) {
                            echo '<div align="center"><h1>'.$info['points'].'</h1></div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">Team Members</div>

                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open" data-toggle="tooltip" data-placement="top" title="" data-original-title="Collapse"></i></a>
                            <a href="#" data-rel="reload"><i class="entypo-arrows-ccw" data-toggle="tooltip" data-placement="top" title="" data-original-title="Refresh"></i></a>
                        </div>
                    </div>

                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th width="10%">Role</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                        if(!empty($members)) {
                            foreach ($members as $member) {
                                echo "<tr>";
                                echo "<td>".$member['username']."</td>";
                                echo "<td>";
                                if ($member['isLeader'] == 1) {
                                    echo "Leader";
                                } else {
                                    echo "Member";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    } else {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div align="center">
                        <h1>Please Make/Join a Team to Participate in the CTF!</h1>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    $page->endBody();
    echo $page->render('../includes/template.php');
} else {
    Redirect::to('../login/');
}
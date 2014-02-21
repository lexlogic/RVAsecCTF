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
        $getTeams = DB::getInstance()->getAssoc("SELECT * FROM teams ORDER BY points DESC LIMIT 10");
        foreach($getTeams->results() as $results) {
            $teams[] = $results;
        }
        $page = new Page;
        $page->setTitle('Manage CTF');
        $page->startBody();
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">Active Questions</div>
                        <div style="float: right; padding: 5px;">
                            <a href="javascript:$('#new-question').modal('show');" class="btn btn-info">Add New Question</a>
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
                        <div class="panel-title">Top Scorers</div>
                        <div style="float: right; padding: 5px;">
                            <a href="javascript:$('#edit-user').modal('show');" class="btn btn-info">Modify User</a>
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
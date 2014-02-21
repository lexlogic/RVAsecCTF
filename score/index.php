<?php
require_once '../Init.php';
$user = new User();
if($user->isLoggedIn()) {
    $getTeam = DB::getInstance()->getAssoc("SELECT * FROM teams WHERE `name` = ?", array($user->data()->team));
    foreach($getTeam->results() as $results) {
        $team[] = $results;
    }
    foreach($team as $info) {
        $getTier = $info['tier'];
        $getTeamID = $info['id'];
        $getPoints = $info['points'];
    }
    $getQuestions = DB::getInstance()->getAssoc("SELECT * FROM questions WHERE tier = ?", array($getTier));
    foreach($getQuestions->results() as $results) {
        $questions[] = $results;
        $questionsCount = count($questions);
    }
    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {
            if(!empty($questions)) {
                foreach($questions as $question) {
                    $getAnswers = DB::getInstance()->getAssoc("SELECT * FROM answers WHERE team_id = ? AND questions_id = ?", array($getTeamID, $question['id']));
                    foreach($getAnswers->results() as $results) {
                        $answers[] = $results;
                    }
                    if(empty($answers)) {
                        $hash = Hash::make(Input::get('pgp'));
                        if ($hash == $question['answer']) {
                            $questionsCount = $team[0]['flagsLeft'] -1;
                            $team = DB::getInstance()->insertAssoc('answers', array(
                                'questions_id' => $question['id'],
                                'team_id' => escape($getTeamID)
                            ));
                            if($questionsCount == 0) {
                                Session::flash("success", "Flag Successfully Submitted! You have completed Tier ".$getTier);
                                $getTier = $getTier + 1;
                                $getNew = DB::getInstance()->getAssoc("SELECT * FROM questions WHERE tier = ?", array($getTier));
                                foreach($getNew->results() as $results) {
                                    $quest[] = $results;
                                    $questionsCount = count($quest);
                                }
                                $points = $question['points'] + $getPoints;
                                $update = DB::getInstance()->update('teams', $getTeamID, array(
                                    'points' => $points,
                                    'tier' => $getTier,
                                    'flagsLeft' => $questionsCount
                                ));
                            } else {
                                $points = $question['points'] + $getPoints;
                                $update = DB::getInstance()->update('teams', $getTeamID, array(
                                    'points' => $points,
                                    'flagsLeft' => $questionsCount
                                ));
                                Session::flash("success", "Flag Successfully Submitted! You have ".$questionsCount." flags's Left");
                            }
                        }
                    } else {
                        Session::flash("failed", "You Already Submitted this flag!!");
                    }
                }
            } else {
                Session::flash("success", "CTF Complete! No more flags to submit.");
            }
        }
    }
    $page = new Page;
    $page->setTitle('Score a Flag');
    $page->startBody();


    if(Session::exists('success')) {
        echo '<div class="alert alert-success">';
        echo Session::flash('success');
        echo '</div>';
    }
    if(Session::exists('failed')) {
        echo '<div class="alert alert-danger">';
        echo Session::flash('failed');
        echo '</div>';
    }
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title">Submit Score</div>
        </div>
        <div class="panel-body">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="field-ta">Flag Hash</label>
                    <input type="text" class="form-control" name="pgp" id="pgp" data-validate="required" data-message-required="Hash is Required" placeholder="Hash" />
                </div>
                <div class="form-group">
                    <input type="hidden" name="token" value="<?php echo escape(Token::generate()); ?>" />
                    <button type="submit" class="btn btn-success">Submit Flag</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    $page->endBody();
    echo $page->render('../includes/template.php');
} else {
    Redirect::to('../login');
}
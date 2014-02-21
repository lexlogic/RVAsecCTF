<?php
require_once '../Init.php';
$user = new User();
if($user->isLoggedIn()) {
    if(Input::exists()) {
        if(Token::check(Input::get('token'))) {
            $validate = new Validate();
            $passwordValidate = $validate->check($_POST, array(
                'teamname' => array(
                    'required' => true
                )
            ));
            if($passwordValidate->passed()) {
                $getQuestions = DB::getInstance()->getAssoc("SELECT * FROM questions WHERE tier = 1");
                foreach($getQuestions->results() as $results) {
                    $questions[] = $results;
                    $questionsCount = count($questions);
                }
                $team = DB::getInstance()->insertAssoc('teams', array(
                    'name' => Input::get('teamname'),
                    'flagsLeft' => $questionsCount,
                    'tier' => 1
                ));

                $update = DB::getInstance()->update('users', $user->data()->id, array(
                    'team' => Input::get('teamname'),
                    'isLeader' => 1
                ));
            }
            Redirect::to('../dashboard/');
        }
    }

    $page = new Page;
    $page->setTitle('Create Team');
    $page->startBody();
    ?>

    <div class="panel panel-primary">

        <div class="panel-heading">
            <div class="panel-title">New Team</div>

            <div class="panel-options">
                <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
            </div>
        </div>

        <div class="panel-body">
            <form enctype="multipart/form-data" action="" method="POST">
                <div class="form-group">
                    <label class="control-label">Team Name</label>
                    <input type="text" class="form-control" name="teamname" id="teamname" data-validate="required" data-message-required="Team Name is Required" placeholder="Team Name" />
                </div>
                <div class="form-group">
                    <input type="hidden" name="token" value="<?php echo escape(Token::generate()); ?>" />
                    <button type="submit" class="btn btn-success">Create Team</button>
                    <button type="reset" class="btn">Reset</button>
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
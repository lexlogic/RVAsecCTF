<?php
require_once '../Init.php';
$user = new User();
if($user->isLoggedIn()) {
    if($user->data()->isLeader == 1) {
        $getUsers = DB::getInstance()->getAssoc("SELECT * FROM users WHERE NOT username = ?", array($user->data()->username));
        foreach($getUsers->results() as $results) {
            $users[] = $results;
        }
        $getMembers = DB::getInstance()->getAssoc("SELECT * FROM users WHERE team = ?", array($user->data()->team));
        foreach($getMembers->results() as $results) {
            $members[] = $results;
        }
        if(Input::exists()) {
            if (!empty($_POST['inviteMembers'])) {
                if(Token::check(Input::get('token'))) {
                    foreach ($_POST['members'] as $invited) {
                        $team = DB::getInstance()->insertAssoc('pending_invites', array(
                            'team_name' => $user->data()->team,
                            'user_id' => $invited['members']
                        ));
                    }
                    Redirect::to('../team/');
                }
            }
            if (!empty($_POST['removeMembers'])) {
                if(Token::check(Input::get('token'))) {
                    foreach ($_POST['checkbox'] as $id) {
                        $update = DB::getInstance()->update('users', escape($id), array(
                            'team' => NULL,
                            'isLeader' => 0
                        ));
                    }
                    Redirect::to('../team/');
                }
            }
        }
        $page = new Page;
        $page->setTitle('Manage Team');
        $page->startBody();
        ?>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">Invite Members to your Team</div>
                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label>Invite Teammates</label>
                                <select name="members[]" class="select2" multiple>
                                    <?php
                                    foreach ($users as $user) {
                                        echo '<option value="'.escape($user['id']).'">'.$user['username'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="token" value="<?php echo escape(Token::generate()); ?>" />
                                <button type="submit" name="inviteMembers" class="btn btn-success">Invite Teammates</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title">Remove Members</div>
                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                            <a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                    <tr>
                                        <th width="1%">#</th>
                                        <th>Username</th>
                                        <th width="10%">Role</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php
                                    if(!empty($members)) {
                                        foreach ($members as $member) {
                                            echo "<tr>";
                                            echo '<td><input name="checkbox[]" type="checkbox" id="checkbox[]" value="'.$member['id'].'"></td>';
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
                            <div class="form-group">
                                <input type="hidden" name="token" value="<?php echo escape(Token::generate()); ?>" />
                                <input type="submit" name="removeMembers" class="btn btn-success" value="Remove Selected Members">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $page->endBody();
        echo $page->render('../includes/template.php');
    } else {
        Redirect::to('../dashboard/');
    }
} else {
    Redirect::to('../login/');
}
<?php
require_once '../Init.php';
$user = new User();

if(isset($_GET['userid']) && $_GET['userid'] != '') {
    $q = (int) escape($_GET['userid']);
    $getAllUserInfo = DB::getInstance()->getAssoc("SELECT * FROM users WHERE id = ?", array($q));
    foreach($getAllUserInfo->results() as $results) {
        $userInfo[] = $results;
    }

    foreach($userInfo as $info) { ?>

        <div class="col-sm-12 col-md-12">
            <div class="block-flat">
                <form role="form" id="modify-user" method="post" action="">
                    <div class="form-group">
                        <?php if($info['group'] == 1) { ?>
                            <label>Select Usergroup</label><br>
                            <input type="radio" id="rd-1" name="group" value="1" checked>
                            <label>Standard User</label>
                            <input type="radio" id="rd-2" name="group" value="2">
                            <label>Organizer</label>
                        <?php } else { ?>
                            <label>Select Usergroup</label><br>
                            <input type="radio" id="rd-1" name="group" value="1">
                            <label>Standard User</label>
                            <input type="radio" id="rd-2" name="group" value="2" checked>
                            <label>Organizer</label>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control" name="password" id="password" data-validate="required" data-message-required="Password is Required" placeholder="Password" />
                    </div>
                    <div class="form-group">
                        <label>New Password Again</label>
                        <input type="password" class="form-control" name="password_again" id="password_again" data-validate="required" data-message-required="Please Re-Enter the Password" placeholder="Password" />
                    </div>
                    <input type="hidden" name="userid" value="<?php echo escape($info['id']); ?>" />
                    <input type="submit" class="btn btn-info btn-flat" name="modifyUser" value="Modify User" />
                    <input type="submit" style="float: right;" class="btn btn-danger btn-flat" name="deleteUser" value="Delete User" onclick="return confirm('Are you sure you want to delete this user?')"/>
                </form>
            </div>
        </div>
    <?php }
}

if(Input::exists()) {
    if (!empty($_POST['modifyUser'])) {
        if(Input::get('password') != NULL) {
            $salt = Hash::salt(32);
            $update = DB::getInstance()->update('users', Input::get('userid'), array(
                'password' => Hash::make(Input::get('password'), $salt),
                'salt' => $salt
            ));
        } else {
            $update = DB::getInstance()->update('users', Input::get('userid'), array(
                '`group`' => Input::get('group')
            ));
        }
        Redirect::to('../manage/');
    }
    if (!empty($_POST['deleteUser'])) {
        DB::getInstance()->delete('users', array('id', '=', Input::get('userid')));
    }
    if (!empty($_POST['newQuestion'])) {
        $team = DB::getInstance()->insertAssoc('questions', array(
            'question' => Input::get('question'),
            'answer' => Hash::make(Input::get('answer')),
            'category' => Input::get('category'),
            'points' => Input::get('points'),
            'tier' => Input::get('tier')
        ));
        Redirect::to('../manage/');
    }
}

?>

<script>
    function showUserInfo(str)
    {
        if (str=="")
        {
            document.getElementById("txtUser").innerHTML="";
            return;
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("txtUser").innerHTML=xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET","modals.php?userid="+str,true);
        xmlhttp.send();
    }
</script>

<div class="modal fade" id="edit-user">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Modify User</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select User</label>
                    <form class="validate">
                        <select class="select2" name="userchange" onchange="showUserInfo(this.value)">
                            <option>-- Select User --</option>
                            <?php
                            if(!empty($users)) {
                                foreach($users as $edit) {
                                    echo '<option value="'.$edit['id'].'">';
                                    echo $edit['username'];
                                    echo '</option>';
                                }
                            }
                            ?>
                        </select>
                    </form>
                </div>
            </div>
            <div id="txtUser"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="new-question">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">New Question</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="new-question" method="post" action="">
                    <div class="form-group">
                        <label>Enter Question</label>
                        <input type="text" class="form-control" name="question" id="question" placeholder="Question" />
                    </div>
                    <div class="form-group">
                        <label>Enter Answer</label>
                        <input type="text" class="form-control" name="answer" id="answer" placeholder="Answer" />
                    </div>
                    <div class="form-group">
                        <label>Enter Category</label>
                        <input type="text" class="form-control" name="category" id="category" placeholder="Category" />
                    </div>
                    <div class="form-group">
                        <label>Points</label>
                        <input type="text" class="form-control" name="points" id="points" placeholder="Points" />
                    </div>
                    <div class="form-group">
                        <label>Select Tier</label><br>
                        <input type="radio" id="rd-1" name="tier" value="1">
                        <label>Tier 1</label>
                        <input type="radio" id="rd-2" name="tier" value="2">
                        <label>Tier 2</label>
                        <input type="radio" id="rd-3" name="tier" value="3">
                        <label>Tier 3</label>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="token" value="<?php echo escape(Token::generate()); ?>" />
                        <input type="submit" name="newQuestion" class="btn btn-success" value="Add Question">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
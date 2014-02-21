<?php
require_once '../Init.php';
$user = new User();

$getPending = DB::getInstance()->getAssoc("SELECT * FROM pending_invites WHERE user_id = ?", array($user->data()->id));
foreach($getPending->results() as $results) {
    $pending[] = $results;
}
if(!empty($pending)) {
    $count = count($pending);
}

if(Input::exists()) {
    if (!empty($_POST['accept'])) {
        DB::getInstance()->delete('pending_invites', array('team_name', '=', Input::get('team')));
        $update = DB::getInstance()->update('users', $user->data()->id, array(
            'team' => Input::get('team'),
            'isLeader' => 0,
        ));
        Redirect::to('../dashboard/');
    }
}

?>
<div class="page-container horizontal-menu">
    <header class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="navbar-brand">
                <a href="../dashboard/">
                    <img src="../assets/images/logo-horizontal.png" alt="" />
                </a>
            </div>

            <ul class="navbar-nav">
                <li>
                    <a href="../dashboard/"><i class="entypo-layout"></i><span>Dashboard</span></a>
                </li>
                <li>
                    <a href="../scoreboard/"><i class="entypo-users"></i><span>Global Scoreboard</span></a>
                </li>
                <?php if($user->data()->team != NULL) { ?>
                    <li>
                        <a href="../score/"><i class="entypo-users"></i><span>Submit Solution</span></a>
                    </li>
                <?php } ?>
                <?php if($user->data()->team == NULL) { ?>
                    <li>
                        <a href="../createteam/"><i class="entypo-network"></i><span>Create New Team</span></a>
                    </li>
                <?php } ?>
                <?php if($user->data()->isLeader == 1) { ?>
                    <li>
                        <a href="../team/"><i class="entypo-user-add"></i><span>Manage Team</span></a>
                    </li>
                <?php } ?>
                <?php if($user->hasPermission('admin')) { ?>
                    <li>
                        <a id="fsbutton"><i class="entypo-resize-full"></i></a>
                    </li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-right pull-right">
                <?php if($user->hasPermission('admin')) { ?>
                    <li>
                        <a href="../manage/">Manage CTF</a>
                    </li>
                <?php } ?>
                <li>
                    <?php
                    echo "<a>".strtoupper(escape($user->data()->username))."</a>";
                    ?>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <?php
                        echo '<i class="entypo-layout"></i>';
                        if(isset($count)) {
                            echo '<span class="badge badge-success badge-roundless">'.$count.'</span>';
                        }
                        ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        if(isset($count)) {
                            echo '<li class="top">';
                            ?>
                            <a href="javascript:$('#modal-1').modal('show');" class="btn btn-default">
                                <?php
                                echo "<p>You have ".$count." pending team";
                                if ($count > 1) {
                                    echo " invites</p>";
                                } else {
                                    echo " invite</p>";
                                }
                                ?>
                            </a>
                            <?php
                            echo '</li>';
                        }
                        ?>
                        <li class="top">
                            <a href="../login/logout.php"><p>Logout</p></a>
                        </li>
                    </ul>
                </li>


                <li class="visible-xs">
                    <div class="horizontal-mobile-menu visible-xs">
                        <a href="#" class="with-animation">
                            <i class="entypo-menu"></i>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <?php
    if (isset($count)) {
        ?>
        <div class="modal fade" id="modal-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Pending Invites</h4>
                    </div>

                    <div class="modal-body">
                        <form role="form" id="accept_invite" method="post" action="" class="validate">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <th>Team</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($pending as $team) {
                                    echo '<tr>';
                                    echo '<td>'.escape($team['team_name']).'</td>';
                                    echo '<td align="center">';
                                    ?>

                                    <input type="hidden" name="team" value="<?php echo escape($team['team_name']); ?>" />
                                    <input type="submit" name="accept" class="btn btn-info" placeholder="Accept">
                                    <?php
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="main-content">
        <div class="paddingtable">
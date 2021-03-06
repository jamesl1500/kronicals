<?php
$stylesheet = "timeline";

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Tutorials
use App\Libraries\TutorialSystem;

$tutorials = TutorialSystem::parse('timeline', 'sent');
$validate = TutorialSystem::validate($tutorials, true);
?>
@extends('layouts.logged-in-main')

@section('content')
    <?php
    if($validate > 0)
    {
        // Show
        TutorialSystem::display($tutorials, $validate, 'timeline', 'sent');
    }
    ?>
    <div class="container timelineMiddle">
        <div class="inner row">
            <!-- Left main feed -->
            <div class="leftFeed col-xl-9 col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="top">
                    <ul>
                        <li><a href="{{ route('timeline.index') }}"><h3>Timeline</h3></a></li>

                        <?php
                        $count = count($incog->unreadCount(auth()->user()->unique_salt_id));
                        ?>
                        <div class="rightLinks float-right">
                            <li><a href="{{ route('timeline.anons') }}"><h3>Anons <?php if($count > 0){ ?><span style="position: relative;top: -1px;" class="badge badge-pill badge-danger"><?php echo $count; ?></span><?php } ?></h3></a></li>
                            <li class="active"><a href="{{ route('timeline.sent') }}"><h3>Sent</h3></a></li>
                        </div>
                    </ul>
                </div>
                <div class="bottom card-columns">
                    <?php
                    $response = json_decode($sentmessages, true);

                    if($response['code'] == 1)
                    {
                        // Loop through all the messages
                        foreach($response['messages'] as $message)
                        {
                            // Replies
                            $replies = $incog->displayIncogMessageReplies(['id' => $message['id']]);

                            if($message['hide'] == null or $message['hide'] != 1)
                            {
                            if($message['from_id'] != "")
                            {
                                $from = DB::table('users')->where('unique_salt_id', $message['from_id'])->get()[0];
                            }
                    $to = DB::table('users')->where('unique_salt_id', $message['user_id'])->get()[0];
                    ?>
                    <div class="message card" id="message<?php echo $message['id']; ?>">
                        <div class="sentTo">
                            <p>Sent to <a class="hrefPrimaryColor" href="<?php echo url('/'); ?>/p/<?php echo $to->username; ?>"><?php echo ucwords($to->name); ?></a></p>
                        </div>
                        <div class="innerMessage">
                            <div class="topMessage">
                                <div class="leftProfile">
                                    <div class="pp" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $from->unique_salt_id; ?>/profile_picture);"></div>
                                </div>
                                <div class="rightProfile">
                                    <h3><a href="<?php echo url('/'); ?>/p/<?php echo $from->username; ?>"><?php echo $from->name; ?></a></h3>
                                    <h4 class="date"><?php echo time_elapsed_string($message['date']); ?></h4>
                                </div>
                            </div>
                            <div class="innerMessageMain">
                                <div class="topText">
                                    <p><?php echo Crypt::decrypt($message['message']); ?></p>
                                </div>
                                <div class="bottomText">
                                    <ul>
                                        <?php if($message['from_id'] != ""){ ?>
                                        <li><a data-anonid="<?php echo $message['id']; ?>" data-action="showReplyBox" class="anonActionBtn" href=""><i class="fas fa-reply"></i> Reply</a></li>
                                        <?php } ?>

                                        <?php if($message['from_id'] != auth()->user()->unique_salt_id){ ?>
                                            <li><a class="hideAnon" href="{{ route('incog.hide') }}" data-id="<?php echo $message['id']; ?>" data-t="{{ csrf_token() }}"><i class="far fa-eye-slash"></i> Hide</a></li>
                                        <?php } else { ?>
                                            <?php if($message['anonymous'] == 1){ ?>
                                                <li><a class="confessAnon" href="{{ route('incog.confess') }}" data-id="<?php echo $message['id']; ?>" data-t="{{ csrf_token() }}"><i class="fas fa-user-secret"></i> Confess</a></li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="replyBox" id="replyBox<?php echo $message['id']; ?>">
                                <div class="replyMainHold" id="replyBoxHold<?php echo $message['id']; ?>">
                                    <div class="row">
                                        <?php
                                        foreach($replies as $reply)
                                        {
                                        // Get user info
                                        $user = DB::table('users')->where('unique_salt_id', $reply->user_id)->get();
                                        ?>
                                        <div class="reply">
                                            <div class="leftProfile">
                                                <div class="innerPP" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo $user[0]->unique_salt_id; ?>/profile_picture);"></div>
                                            </div>
                                            <div class="rightProfile">
                                                <h3><a href="<?php echo url('/'); ?>/p/<?php echo $user[0]->username; ?>"><?php echo ucwords($user[0]->name); ?></a> &middot; <span><?php echo time_elapsed_string($reply->date); ?></span></h3>
                                                <p><?php echo Crypt::decrypt($reply->message); ?></p>
                                            </div>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="replyMaker">
                                    <form action="{{ route('incog.reply') }}" method="post" class="replyMakerForm" data-id="<?php echo $message['id']; ?>">
                                        <div class="row">
                                            <div class="pp">
                                                <div class="mainpphold" style="background-image: url(<?php echo url('/'); ?>/user/<?php echo auth()->user()->unique_salt_id; ?>/profile_picture);"></div>
                                            </div>
                                            <div class="input">
                                                <input type="text" class="replyInput" data-id="<?php echo $message['id']; ?>" id="replyInput<?php echo $message['id']; ?>" placeholder="Press enter or return to reply" />
                                                <input type="hidden" class="replyId" id="replyId" value="<?php echo $message['id']; ?>" />
                                                <input type="hidden" class="replyToken" value="{{ csrf_token() }}" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    }
                    }else{
                        ?>
                        <br />
                        <div class="message error">
                            <h3 style="text-align: center;"><?php echo $response['message']; ?></h3>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Right sidebar -->
            @include('timeline.templates.sidebar')
        </div>
    </div>

    <!-- Stuff -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove Ads</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo route('braintree.disablead'); ?>" method="post">
                    <div class="modal-body" style="padding: 15px;">
                        <div class="mainBody" style="border-bottom: 1px solid #eee;">
                            <p>Ads support us and keeps us going! You can still support us even if you want to remove the ads</p>
                            <b><h4>Price: $1</h4></b>
                        </div><br />
                        <h4>Payment Methods</h4><br />
                        <div class="payment" id="payment"></div>
                    </div>
                    <div class="modal-footer">
                        {{ csrf_field() }}
                        <a type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</a>
                        <input type="submit" class="btn btn-primary" value="Remove Ads">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    @section('scripts')
    <!-- <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            // Get token
            $.get("{{ route('braintree.token') }}", function(data){
                var obj = jQuery.parseJSON(data);
                braintree.setup(obj.token, 'dropin', {
                    container: 'payment'
                });
            });

            <?php
            //if(isset($_GET['m'])){
            ?>
            // Scroll to message
            $('html, body').animate({
                scrollTop: $("#message<?php //echo $_GET['m']; ?>").offset().top
            }, 2000, function(){
                $("#message<?php //echo $_GET['m']; ?>").addClass('replyActive');
                $("#replyBox<?php //echo $_GET['m']; ?>").fadeIn('fast');
            });
            <?php
            //}
            ?>
        });
    </script> -->
    @endsection
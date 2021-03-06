<?php
$stylesheet = "incog";

use Illuminate\Support\Facades\DB;
use App\Libraries\User;
use App\Libraries\TutorialSystem;

// Find user
$exists = User::exists($user[0]->id);

// Check
if(count($exists) == 1)
{
    // Then we good
    $profile = $user[0];
    $info = DB::table('user_info')->where('unique_salt_id', $profile->unique_salt_id)->get()[0];

    // Tutorials
    $tutorials = TutorialSystem::parse('incog', 'incog');
    $validate = TutorialSystem::validate($tutorials, true);
}else{
    redirect('/');
}
?>
@extends('layouts.logged-in-main')

@section('content')
    <br /><br /><br /><br />
    <?php
    if($validate > 0)
    {
        // Show
        TutorialSystem::display($tutorials, $validate, 'incog', 'incog');
    }
    ?>
    <div class="profile container">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <!-- Top Profile (with banner) -->
        <div class="userProfileTop">
            <div class="cover">
                <div class="bannerMain" style="background: url(<?php echo url('/'); ?>/user/<?php echo $profile->unique_salt_id; ?>/banner_picture);"></div>
                <div class="mainProfileHold">
                    <div class="profileImage">
                        <img src="<?php echo url('/'); ?>/user/<?php echo $profile->unique_salt_id; ?>/profile_picture" />
                    </div>
                    <div class="rightInfo">
                        <h3><?php echo $profile->name; ?></h3>
                        <h4>@<?php echo $profile->username; ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main system -->
        @include('incog.templates.base')
    </div>

@endsection
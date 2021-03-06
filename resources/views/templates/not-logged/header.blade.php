<div class="header absolute-top transparent container-fluid">
    <div class="inner-header">
        <div class="left-branding pull-left">
            <h3 class=""><a href="<?php echo url('/'); ?>"><?php echo config('app.name'); ?> <?php if(env('APP_STATUS') == "Beta"){ ?><span style="font-weight: 500;position: relative;top: -2px;font-size: .4em;" class="badge badge-pill badge-light"><?php echo env('APP_STATUS'); ?> v<?php echo env('APP_VERSION'); ?></span><?php } ?></a></h3>
        </div>
        <div class="right-navigation pull-right">
            <div class="inner-navigation">
                <ul class="inner-navigation-list" itemscope='itemscope' itemtype='http://schema.org/SiteNavigationElement' role='navigation'>
                    <div class="mobileHide">
                        <li itemprop='url' title="Learn who we are and what we do"><a itemprop='name' href="<?php echo url("/"); ?>/about_us">About Us</a></li>
                        <li itemprop='url' title="Login to your account"><a itemprop='name' href="<?php echo url("/"); ?>/login">Login</a></li>
                        <li class="special" itemprop='url' title="Need an account? Register here"><a itemprop='name' href="<?php echo url("/"); ?>/register">Register</a></li>
                    </div>
                    <li class="special mobileRegister" itemprop='url' title="Need an account? Register here"><a itemprop='name' href="<?php echo url("/"); ?>/register">Register</a></li>
                    <li class="mobileSidebarOpen"><i class="fas fa-bars" aria-hidden="true"></i></li>
                </ul>
            </div>
        </div>
    </div>
</div>
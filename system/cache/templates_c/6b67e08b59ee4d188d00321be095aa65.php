<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <title><?php echo lang ('a_controll_panel'); ?> - Image CMS</title>
        <meta name="description" content="<?php echo lang ('a_controll_panel'); ?> - Image CMS" />
        <link rel="stylesheet" type="text/css" href="<?php if(isset($THEME)){ echo $THEME; } ?>/css/style.css" />
        <!--[if IE 7]><link rel="stylesheet" type="text/css" href="css/ie_7.css" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="css/ie8_7_6.css" /><![endif]-->
        <!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <script src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery-1.7.1.min.js" type="text/javascript"></script>
        <script src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jcarousellite_1.0.1.min.js" type="text/javascript"></script>
        <script src="<?php if(isset($THEME)){ echo $THEME; } ?>/js/jquery.main.js" type="text/javascript"></script>
        <script  type="text/javascript">
            var theme = '<?php if(isset($THEME)){ echo $THEME; } ?>';
            var base_url = '<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>';
        </script>
        <script type="text/javascript" src="<?php if(isset($JS_URL)){ echo $JS_URL; } ?>/mocha/mootools-1.3-core.js"></script>
	<script type="text/javascript" src="<?php if(isset($JS_URL)){ echo $JS_URL; } ?>/mocha/mootools-1.2-more.js"></script>
	<script type="text/javascript" src="<?php if(isset($JS_URL)){ echo $JS_URL; } ?>/plugins/Roar.js"></script>
	<script type="text/javascript" src="<?php if(isset($JS_URL)){ echo $JS_URL; } ?>/mocha/functions.js"></script>
    </head>
    <body><?php $ci = get_instance();
        if($ci->config->item('is_installed') === TRUE AND file_exists(APPPATH.'modules/install/install.php'))
        die('<span style="font-size:18px;"><br/><br/>'.lang('a_delete_install').'/application/modules/install/install.php</div>');
            ?>
            <div class="main_body">
                <div class="container w_665">
                    <div class="logo_with_out_article">
                        <a href="/">
                            <img src="<?php if(isset($THEME)){ echo $THEME; } ?>/images/logo_big.png"/>
                        </a>
                    </div>
                    <div class="order_partner_ship frame_standart_form fonds">
                        <h1 class="t_a_c"><?php echo lang ('a_auth'); ?></h1>

                        <form method="post" action="<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>admin/login/" class="standart_form" id="with_out_article">
                            <?php if($login_failed): ?>
                                <?php if(isset($login_failed)){ echo $login_failed; } ?>
                            <?php endif; ?></br>
                            <label>


                                <?php echo lang ('a_login'); ?>:
                                <input type="text" name="login"/><?php if(isset($login_error)){ echo $login_error; } ?></br>
                            </label>
                            <label>
                                <?php echo lang ('a_pass'); ?>:
                                <input type="password" name="password"/><?php if(isset($password_error)){ echo $password_error; } ?></br>
                            </label>
                            <?php if($use_captcha == "1"): ?>

                                <label style="margin-bottom:50px">
                                    <?php if(isset($lang_captcha)){ echo $lang_captcha; } ?>:<br/>
                                    <div id="captcha"><?php if(isset($cap_image)){ echo $cap_image; } ?></div>
                                        <a href="" onclick="ajax_div('captcha','<?php if(isset($BASE_URL)){ echo $BASE_URL; } ?>/admin/login/update_captcha');return false;"><?php echo lang ('a_code_refresh'); ?></a>
                                        <input type="text" name="captcha" /><?php if(isset($captcha_error)){ echo $captcha_error; } ?>
                                </label>
                            <?php endif; ?>
                            
                            <div class="t_a_c">
                                <label class="d_i_b w_auto h_auto m_r_9 pos_rel">
                                    <input type="checkbox" name="remember" value="1"/><?php echo lang ('a_remember'); ?></label>
                                <div class="button_clean button_blue">
                                    <input type="submit" value="<?php echo lang ('a_send_f'); ?>"/>
                                </div>
                                <div class="o_h">
                                    <a href="/auth/register/"><?php echo lang ('a_reg'); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/auth/forgot_password/"><?php echo lang ('a_forget_pass'); ?></a>
                                </div>
                            </div>
                            <?php echo form_csrf (); ?>
                        </form>
                    </div>
                </div>
                <div class="hfooter"></div>
            </div>
            <footer class="b_c_n">
                <div class="container w_260">
                    <div class="d_t_c l_h_17">
                        <span class="l_h_27"><?php echo lang ('a_site_image_ooo'); ?></span>
                        <?php echo lang ('a_site_image_text'); ?>
                    </div>            
                </div>
            </footer>
    </body>
</html>
<?php $mabilis_ttl=1538465293; $mabilis_last_modified=1450852413; //D:\server\www\projects\articler.img\/templates/administrator/login.tpl ?>
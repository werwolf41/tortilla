<?php
$_lang['area_ec_main']     = 'Main';
$_lang['area_ec_mail']     = 'Mail';
$_lang['area_ec_rating']   = 'Rating and evaluation';
$_lang['area_ec_gravatar'] = 'Gravatar';
$_lang['area_ec_antispam'] = 'Anti-Spam';

$_lang['setting_ec_show_templates']      = 'Display tab at the resources listed templates';
$_lang['setting_ec_show_templates_desc'] = 'List templates id separated by commas indicate * that would show all templates';

$_lang['setting_ec_show_resources']      = 'Display tab in the resources listed ';
$_lang['setting_ec_show_resources_desc'] = 'The list of resource id separated by commas indicate * that would show all resources';

$_lang['setting_ec_frontend_css']      = 'Frontend styles';
$_lang['setting_ec_frontend_css_desc'] = 'The path to the file with the styles. If you want to use your own style - enter the path to it here, or clear and load them manually through Template';

$_lang['setting_ec_frontend_js']      = 'Scripts frontend';
$_lang['setting_ec_frontend_js_desc'] = 'The path to the file with scripts. If you want to use your own scripts - specify the path to it here, or clear and load them manually through Template';

$_lang['setting_ec_thread_grid_fields']      = 'List of strings in the list of fields';
$_lang['setting_ec_thread_grid_fields_desc'] = 'The fields that will be visible in the admin in the conversation list, separated by commas';

$_lang['setting_ec_thread_window_fields']      = 'The list of fields when editing a chain';
$_lang['setting_ec_thread_window_fields_desc'] = 'The fields that will be visible in the admin when editing a string, separated by commas';

$_lang['setting_ec_message_grid_fields']      = 'The list of fields in the list of posts';
$_lang['setting_ec_message_grid_fields_desc'] = 'The fields that will be visible in the admin panel in the message list, separated by commas';

$_lang['setting_ec_message_window_layout']      = 'The marking of the editing window posts';
$_lang['setting_ec_message_window_layout_desc'] = 'Line in JSON format';

$_lang['setting_ec_auto_reply_author']      = 'Automatically fill in the answer Author ';
$_lang['setting_ec_auto_reply_author_desc'] = 'When you reply to the message will be automatically filled Author field';

//$_lang['setting_ec_use_richtext'] = 'Use TinyMCE to answer field ';
//$_lang['setting_ec_use_richtext_desc'] = 'Be sure to have installed TinyMCE';


$_lang['setting_ec_mail_notify_user']      = 'Send notifications to users';
$_lang['setting_ec_mail_notify_user_desc'] = 'If you leave your email, it will be sent a notice of the fact that he left a message on the site';

$_lang['setting_ec_mail_notify_manager']      = 'Send notifications to administrators';
$_lang['setting_ec_mail_notify_manager_desc'] = 'Notify the administrator about new messages on the site';

$_lang['setting_ec_mail_new_subject_user']      = 'Notification letters about the new theme user posts';
$_lang['setting_ec_mail_new_subject_user_desc'] = 'You can override by setting newEmailSubjUser snippet ecForm';

$_lang['setting_ec_mail_new_subject_manager']      = 'Subject of administrator notification letters about the theme of the new posts';
$_lang['setting_ec_mail_new_subject_manager_desc'] = 'You can override by setting newEmailSubjManager snippet ecForm';

$_lang['setting_ec_mail_update_subject_user']      = 'Subject notification letters the user to change his message (published or answered)';
$_lang['setting_ec_mail_update_subject_user_desc'] = 'You can override by setting updateEmailSubjUser snippet ecForm';

$_lang['setting_ec_mail_manager']      = 'E-Mail to notify the administrator';
$_lang['setting_ec_mail_manager_desc'] = 'If empty - will be used by system setting EmailSender';

$_lang['setting_ec_mail_from']      = 'From what address to send notifications about new messages';
$_lang['setting_ec_mail_from_desc'] = 'If empty - will be used by system setting EmailSender';

$_lang['setting_ec_mail_from_name']      = 'On whose behalf to send notifications of new messages';
$_lang['setting_ec_mail_from_name_desc'] = 'If empty - will be used by system setting site_name';


$_lang['setting_ec_rating_max']      = 'The maximum possible evaluation';
$_lang['setting_ec_rating_max_desc'] = 'Used to restrict the data entered by users';

$_lang['setting_ec_rating_wilson_confidence']      = 'The confidence level rating for Wilson';
$_lang['setting_ec_rating_wilson_confidence_desc'] = 'Confidence level. 1.0 = 85%, 1.6 = 95%. See. <a href="http://habrahabr.ru/company/darudar/blog/143188/" target="_blank">http://habrahabr.ru/company/darudar/blog/143188/</a>';

$_lang['setting_ec_rating_visual_editor'] = 'Visual display of the rating in the manager';
$_lang['setting_ec_rating_visual_editor_desc'] = 'Display rating with asterisks or just numbers in the admin area';

$_lang['setting_ec_gravatar_size']    = 'The size of the avatar icons';
$_lang['setting_ec_gravatar_default'] = 'Default avatar icon';

$_lang['setting_ec_captcha_enable'] = 'Enable captcha (Google ReCaptcha v2)';
$_lang['setting_ec_captcha_enable_desc'] = '';

$_lang['setting_ec_recaptcha2_api'] = 'Google ReCaptcha v2 API url';

$_lang['setting_ec_recaptcha2_site_key'] = 'Google ReCaptcha v2 site key';

$_lang['setting_ec_recaptcha2_secret_key'] = 'Google ReCaptcha v2 secret';
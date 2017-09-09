<?php
include_once 'setting.inc.php';

$_lang['easycomm']      = 'Messages';
$_lang['ec']            = 'Messages';
$_lang['ec_menu_desc']  = 'Manage messages from users';

$_lang['ec_threads']            = 'Conversations';
$_lang['ec_threads_intro_msg']  = 'You can select multiple conversations  with Shift or Ctrl.';

$_lang['ec_thread_id']              = 'Id';
$_lang['ec_thread_resource']        = 'Resource';
$_lang['ec_thread_name']            = 'Name';
$_lang['ec_thread_title']           = 'Title';
$_lang['ec_thread_count']           = 'Number of messages ';
$_lang['ec_thread_rating_simple']   = 'Rating (Simple)';
$_lang['ec_thread_rating_wilson']   = ' Rating (Wilson)';
$_lang['ec_thread_extended']        = 'Extended (JSON)';

$_lang['ec_thread_create']           = 'New conversation';
$_lang['ec_thread_update']           = 'Edit';
$_lang['ec_thread_enable']           = 'Enable conversation';
$_lang['ec_threads_enable']          = 'Enable conversations';
$_lang['ec_thread_disable']          = 'Disable conversation';
$_lang['ec_threads_disable']         = 'Disable conversations';
$_lang['ec_threads_manage_messages'] = 'Manage messages from this conversation';
$_lang['ec_thread_remove']           = 'Delete conversation';
$_lang['ec_threads_remove']          = 'Delete conversations';
$_lang['ec_thread_remove_confirm']   = 'Are you sure you want to delete this conversation? All posts related to it will be deleted without possibility of recovery!';
$_lang['ec_threads_remove_confirm']  = 'Are you sure you want to delete this conversations? All posts related to them will be deleted without possibility of recovery!';

$_lang['ec_thread_err_name']   = 'You must specify the name of the conversation';
$_lang['ec_thread_err_ae']     = 'Conversation with the same name already exists.';
$_lang['ec_thread_err_nf']     = 'Conversation not found.';
$_lang['ec_thread_err_ns']     = 'The conversation is not specified.';
$_lang['ec_thread_err_remove'] = 'Error when deleting a conversation.';
$_lang['ec_thread_err_save']   = 'Error saving conversation.';


$_lang['ec_messages']           = 'Messages';
$_lang['ec_messages_intro_msg'] = 'You can select multiple messages  with Shift or Ctrl.';

$_lang['ec_message_id']                 = 'Id';
$_lang['ec_message_thread']             = 'Conversation';
$_lang['ec_message_thread_name']        = 'Conversation';
$_lang['ec_message_thread_resource']    = 'Id of the resource';
$_lang['ec_message_resource_pagetitle'] = 'Resource';
$_lang['ec_message_subject']            = 'Subject';
$_lang['ec_message_date']               = 'Date';
$_lang['ec_message_user_name']          = 'author';
$_lang['ec_message_user_email']         = 'email';
$_lang['ec_message_user_contacts']      = 'contacts';
$_lang['ec_message_text']               = 'Text';
$_lang['ec_message_published']          = 'Published';
$_lang['ec_message_reply_author']       = 'Reply author';
$_lang['ec_message_reply_text']         = 'Reply';
$_lang['ec_message_notify']             = 'Notify the user of the publication (or response) by mail';
$_lang['ec_message_notify_date']        = 'Las notification';
$_lang['ec_message_extended']           = 'Extended( JSON )';
$_lang['ec_message_ip']                 = 'IP address';
$_lang['ec_message_rating']             = 'rating( from 1 to 5)';

$_lang['ec_message_create']     = 'Create';
$_lang['ec_message_update']     = 'Modify';
$_lang['ec_message_publish']    = 'Publish message';
$_lang['ec_messages_publish']   = 'Publish messages';
$_lang['ec_message_unpublish']  = 'Unpublish';
$_lang['ec_messages_unpublish'] = 'Unpublish messages';

$_lang['ec_message_view_on_site'] = 'View on site';

$_lang['ec_message_delete']          = 'Delete message';
$_lang['ec_messages_delete']         = 'Delete messages';
$_lang['ec_message_delete_confirm']  = 'Are you sure you want to delete this message ? Further it can be recovered . ';
$_lang['ec_messages_delete_confirm'] = 'Are you sure you want to delete these messages ? Further they can be recovered . ';

$_lang['ec_message_undelete']          = 'Recover message';
$_lang['ec_messages_undelete']         = 'Recover messages';
$_lang['ec_message_undelete_confirm']  = 'Are you sure you want to recover this message';
$_lang['ec_messages_undelete_confirm'] = 'Are you sure you want to recover these messages';

$_lang['ec_message_remove']          = 'Delete message';
$_lang['ec_messages_remove']         = 'Delete messages';
$_lang['ec_message_remove_confirm']  = 'Are you sure you want to permanently destroy this message ? ';
$_lang['ec_messages_remove_confirm'] = 'Are you sure you want to permanently destroy these messages ? ';

$_lang['ec_message_tab_main']     = 'Message';
$_lang['ec_message_tab_reply']    = 'Answer';
$_lang['ec_message_tab_settings'] = 'Settings';


$_lang['ec_message_err_thread']        = ' You must specify the conversation';
$_lang['ec_message_err_user_name']     = 'You must specify the name';
$_lang['ec_message_err_user_email']    = 'You must specify the email';
$_lang['ec_message_err_user_contacts'] = ' You must provide contact information';
$_lang['ec_message_err_subject']       = ' You must specify a Subject';
$_lang['ec_message_err_rating']        = 'Please, rate us';
$_lang['ec_message_err_text']          = 'You must write the text of the message ';
$_lang['ec_message_err_captcha']       = 'Check "I`m not a robot"';
$_lang['ec_message_err_nf']            = 'Message wasn’t found';
$_lang['ec_message_err_ns']            = 'Message wasn’t indicated';
$_lang['ec_message_err_remove']        = 'Error deleting message';
$_lang['ec_message_err_save']          = 'Error saving message';

$_lang['ec_object_published'] = 'Published';

$_lang['ec_grid_search']  = 'Search';
$_lang['ec_grid_actions'] = 'Actions';

$_lang['ec_fe_message_add']           = 'Write a message';
$_lang['ec_fe_message_user_name']     = 'Your name';
$_lang['ec_fe_message_user_email']    = 'Email';
$_lang['ec_fe_message_user_contacts'] = 'Contact information';
$_lang['ec_fe_message_subject']       = 'Subject';
$_lang['ec_fe_message_rating']        = 'Rating';
$_lang['ec_fe_message_rating_0']      = 'Please rate on a scale of 5';
$_lang['ec_fe_message_rating_1']      = 'Bad';
$_lang['ec_fe_message_rating_2']      = 'Could be better';
$_lang['ec_fe_message_rating_3']      = 'Average';
$_lang['ec_fe_message_rating_4']      = 'Good';
$_lang['ec_fe_message_rating_5']      = 'Great! I would recommend! ';
$_lang['ec_fe_message_text']          = 'Your message';
$_lang['ec_fe_send']                  = 'Send';
$_lang['ec_fe_send_success']          = 'Your message has been successfully sent . It will be published after approval by a site moderator!';

$_lang['ec_fe_message_antismap'] = 'Anti - spam field . It should be hide by css';
$_lang['ec_fe_spam_detected']    = 'Spam detected';

$_lang['ec_unknown_action'] = 'Unknown action';

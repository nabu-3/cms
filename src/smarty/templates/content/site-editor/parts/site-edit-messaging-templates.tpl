{nabu_model model="bootstrap-3.3.7"}
{nabu_form_row}
    {nabu_form_select class="{$class}" from=$edit_source field=messaging_template_new_user options=$templates options_name=name options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned} label={nabu_static key=lbl_new_user}}
    {nabu_form_select class="{$class}" from=$edit_source field=messaging_template_remember_new_user options=$templates options_name=name options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned} label={nabu_static key=lbl_new_user_remember}}
    {nabu_form_select class="{$class}" from=$edit_source field=messaging_template_notify_new_user options=$templates options_name=name options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned} label={nabu_static key=lbl_new_user_notification}}
    {nabu_form_select class="{$class}" from=$edit_source field=messaging_template_forgot_password options=$templates options_name=name options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned} label={nabu_static key=lbl_password_forgoten}}
    {nabu_form_select class="{$class}" from=$edit_source field=messaging_template_invite_user options=$templates options_name=name options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned} label={nabu_static key=lbl_invite_new_user}}
    {nabu_form_select class="{$class}" from=$edit_source field=messaging_template_invite_friend options=$templates options_name=name options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned} label={nabu_static key=lbl_invite_new_friend}}
    {nabu_form_select class="{$class}" from=$edit_source field=messaging_template_new_messsage options=$templates options_name=name options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned} label={nabu_static key=lbl_new_notification}}
{/nabu_form_row}

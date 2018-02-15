{nabu_model model="bootstrap-3.3.7"}
{nabu_form_row}
    {nabu_form_select class="col-md-3 col-sm-4" from=$edit_site field=messaging_template_new_user options=$templates options_name=name label="Nuevo usuario"}
    {nabu_form_select class="col-md-3 col-sm-4" from=$edit_site field=messaging_template_remember_new_user options=$templates options_name=name label="Recordatorio de nuevo usuario"}
    {nabu_form_select class="col-md-3 col-sm-4" from=$edit_site field=messaging_template_notify_new_user options=$templates options_name=name label="Notificación de nuevo usuario"}
    {nabu_form_select class="col-md-3 col-sm-4" from=$edit_site field=messaging_template_forgot_password options=$templates options_name=name label="Contraseña perdida"}
    {nabu_form_select class="col-md-3 col-sm-4" from=$edit_site field=messaging_template_invite_user options=$templates options_name=name label="Invitar a un usuario"}
    {nabu_form_select class="col-md-3 col-sm-4" from=$edit_site field=messaging_template_invite_friend options=$templates options_name=name label="Invitar a un amigo"}
    {nabu_form_select class="col-md-3 col-sm-4" from=$edit_site field=messaging_template_new_messsage options=$templates options_name=name label="Nueva notificación"}
{/nabu_form_row}

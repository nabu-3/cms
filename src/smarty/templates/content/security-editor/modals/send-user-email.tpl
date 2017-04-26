{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=modal_send_email section=send_email}
{nabu_assign var=modal_send_email_success section=send_email_success}
{nabu_assign var=modal_send_email_error section=send_email_error}
{nabu_assign var=api cta=api_user}
{nabu_modal id=modal_send_email caller=edit_zone aria_labelledby=modal_send_email_head}
    {nabu_form layout="horizontal:2:10" method="ajax-get" action_template="{$api.translations[$nb_site.api_language_id].final_url|sprintf:""}?action=send_email"}
        <input type="hidden" name=ids id=send_mail_ids>
        <div class="modal-steps">
            <div class="modal-step" data-step="1">
                {nabu_modal_header dismiss=true aria_label_id=modal_send_email_head}{$modal_send_email.translation.title}{/nabu_modal_header}
                {nabu_modal_body}
                    {$modal_send_email.translation.content}
                    <div class="radio"><label><input type="radio"> Nuevo usuario</label></div>
                {/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="submit" class="btn btn-success">{nabu_static key=btn_send}</button>
                {/nabu_modal_footer}
            </div>
        </div>
        <div class="modal-panels hide">
            <div class="modal-panel" data-action="success">
                {nabu_modal_header dismiss=true}{$modal_send_email_success.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_send_email_success.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                    <a class="btn btn-info"{if is_array($editor) && count($editor)>0} data-toggle="modal-btn-editor" data-editor="{$editor.translation.final_url}"{/if}>Continuar</a>
                {/nabu_modal_footer}
            </div>
            <div class="modal-panel" data-action="error">
                {nabu_modal_header dismiss=true}{$modal_send_email_error.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_send_email_error.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                {/nabu_modal_footer}
            </div>
        </div>
    {/nabu_form}
{/nabu_modal}

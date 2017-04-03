{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=modal_send section=version_send_test}
{nabu_assign var=modal_send_success section=version_send_test_success}
{nabu_assign var=modal_send_error section=version_send_test_error}
{nabu_assign var=api_project cta=api_project}
{nabu_modal id=modal_send_test caller=item_list aria_labelledby=modal_send_test_head}
    {nabu_form layout="horizontal:2:10" method="ajax-get" action="{$api_project.translations[$nb_site.api_language_id].final_url|sprintf:$edit_project.id:""}?action=version_send_test"}
        <div class="modal-steps">
            <div class="modal-step" data-step="1">
                {nabu_modal_header dismiss=true aria_label_id=modal_send_test_head}{$modal_send.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_send.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="submit" class="btn btn-success">Enviar</button>
                {/nabu_modal_footer}
            </div>
        </div>
        <div class="modal-panels hide">
            <div class="modal-panel" data-action="success">
                {nabu_modal_header dismiss=true}{$modal_send_success.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_send_success.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                {/nabu_modal_footer}
            </div>
            <div class="modal-panel" data-action="error">
                {nabu_modal_header dismiss=true}{$modal_send_error.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_send_error.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                {/nabu_modal_footer}
            </div>
        </div>
    {/nabu_form}
{/nabu_modal}

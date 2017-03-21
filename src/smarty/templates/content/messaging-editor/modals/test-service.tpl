{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=modal_test section=test_service}
{nabu_assign var=modal_test_success section=test_service_success}
{nabu_assign var=modal_test_error section=test_service_error}
{nabu_assign var=api cta=api_messaging_services}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {nabu_modal id=modal_test_service size=lg caller=edit_zone aria_labelledby=modal_test_service_head}
        {nabu_form layout="horizontal:2:10" method="ajax-get" action_template="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_messaging.id:'%s'}?action=test"}
            <div class="modal-steps">
                <div class="modal-step" data-step="1">
                    {nabu_modal_header dismiss=true aria_label_id=modal_test_service_head}{$modal_test.translation.title}{/nabu_modal_header}
                    {nabu_modal_body}
                        <div class="row">
                            <aside class="col-sm-3">{$modal_test.translation.opening}</aside>
                            <section class="col-sm-9 col-sm-offset-3">
                                <div class="alert alert-info">{$modal_test.translation.content}</div>
                                {nabu_form_textbox label=De name=from_mail maxlength=256 help="Escribe la dirección de correo del remitente."}
                                {nabu_form_textbox label=Para name=to_mail maxlength=256 help="Escribe la dirección de correo del destinatario."}
                                {nabu_form_textbox type=textarea rows=5 label=Mensaje name=message help="Escribe un texto de prueba para enviarlo."}
                            </section>
                        </div>
                    {/nabu_modal_body}
                    {nabu_modal_footer}
                        <button type="button" class="btn btn-link" data-dismiss="modal">{nabu_static key=btn_cancel}</button>
                        <button type="submit" class="btn btn-success">{nabu_static key=btn_launch_test}</button>
                    {/nabu_modal_footer}
                </div>
            </div>
            <div class="modal-panels hide">
                <div class="modal-panel" data-action="success">
                    {nabu_modal_header dismiss=true}{$modal_test_success.translation.title}{/nabu_modal_header}
                    {nabu_modal_body}
                        <div class="row">
                            <aside class="col-sm-3">{$modal_test.translation.opening}</aside>
                            <section class="col-sm-9 col-sm-offset-3">
                                <div class="alert alert-success">{$modal_test_success.translation.opening}</div>
                            </section>
                        </div>
                    {/nabu_modal_body}
                    {nabu_modal_footer}
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                    {/nabu_modal_footer}
                </div>
                <div class="modal-panel" data-action="error">
                    {nabu_modal_header dismiss=true}{$modal_test_error.translation.title}{/nabu_modal_header}
                    {nabu_modal_body}{$modal_test_error.translation.opening}{/nabu_modal_body}
                    {nabu_modal_footer}
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                    {/nabu_modal_footer}
                </div>
            </div>
        {/nabu_form}
    {/nabu_modal}
{/if}

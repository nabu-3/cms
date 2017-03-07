{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=modal_new section=new_service}
{nabu_assign var=modal_new_success section=new_service_success}
{nabu_assign var=modal_new_error section=new_service_error}
{nabu_assign var=api cta=new_service}
{nabu_modal id=modal_new_service size=lg caller=edit_zone aria_labelledby=modal_new_service_head}
    {nabu_form layout="horizontal:2:10" method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:""}
        <div class="modal-steps">
            <div class="modal-step" data-step="1">
                {nabu_modal_header dismiss=true aria_label_id=modal_new_service_head}{$modal_new.translation.title}{/nabu_modal_header}
                {nabu_modal_body}
                    <div class="row">
                        <aside class="col-sm-3">{$modal_new.translation.opening}</aside>
                        <section class="col-sm-9 col-sm-offset-3">
                            {nabu_form_textbox label=Key name=key maxlength=30 help="Establece una <b>key</b> para usar tu repositorio desde el código."}
                            {nabu_form_textbox label=Nombre name=name index=$nb_language.id maxlength=100 help="Pon un <b>nombre</b> a tu repositorio para identificarlo en todo momento."}
                        </section>
                    </div>
                {/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear</button>
                {/nabu_modal_footer}
            </div>
        </div>
        <div class="modal-panels hide">
            <div class="modal-panel" data-action="success">
                {nabu_modal_header dismiss=true}{$modal_new_success.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_new_success.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                    <a class="btn btn-info"{if is_array($editor) && count($editor)>0} data-toggle="modal-btn-editor" data-editor="{$editor.translation.final_url}"{/if}>Continuar</a>
                {/nabu_modal_footer}
            </div>
            <div class="modal-panel" data-action="error">
                {nabu_modal_header dismiss=true}{$modal_new_error.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_new_error.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
                {/nabu_modal_footer}
            </div>
        </div>
    {/nabu_form}
{/nabu_modal}

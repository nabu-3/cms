{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=modal_new section=new_version}
{nabu_assign var=modal_new_success section=new_version_success}
{nabu_assign var=modal_new_error section=new_version_error}
{nabu_assign var=api cta=api_version}
{nabu_assign var=editor cta=item_edit}
{nabu_modal id=modal_new_version size=lg caller=item_list aria_labelledby=modal_new_version_head}
    {nabu_form layout="horizontal:2:10" method="ajax-post" action=$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_project.id:""}
        <div class="modal-steps">
            <div class="modal-step" data-step="1">
                {nabu_modal_header dismiss=true aria_label_id=modal_new_version_head}{$modal_new.translation.title}{/nabu_modal_header}
                {nabu_modal_body}
                    <div class="row">
                        <aside class="col-sm-3">{$modal_new.translation.opening}</aside>
                        <section class="col-sm-9 col-sm-offset-3" data-toggle="ckeditor">
                            {nabu_form_textbox horizontal=":2:3" label="Versión" name=code maxlength=30 help="Introduce el número de versión."}
                            {nabu_form_textbox size=9 label="Nombre" name="name" index=$nb_language.id maxlength=30 help="Introduce el nombre de la versión."}
                            {nabu_form_textbox id=new_version_description type=textarea label="Descripción" name=description index=$nb_language.id rows=8 help="Describe los elementos que incluye esta versión."}
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

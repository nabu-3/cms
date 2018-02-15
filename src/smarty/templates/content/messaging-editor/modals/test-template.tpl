{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_templates}
{if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
    {nabu_modal id=modal_test_template size=lg caller=edit_zone aria_labelledby=modal_test_template_head}
        {nabu_form layout="horizontal:2:10" method="ajax-post" action_template="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_messaging.id:'%s'}?action=test"}
            {nabu_modal_header dismiss=true aria_label_id=modal_test_template_head}Prueba de envío de la plantilla{/nabu_modal_header}
            {nabu_modal_body}
                <p>Te vamos a enviar una prueba de la plantilla a tu correo &lt;{$nb_user.email}&gt; para que puedas verificarla.<br>Por favor, pulsa en &lt;Lanzar el Test&gt; para proceder al envío.</p>
            {/nabu_modal_body}
            {nabu_modal_footer}
                <button type="button" class="btn btn-link" data-dismiss="modal">{nabu_static key=btn_cancel}</button>
                <button type="submit" class="btn btn-success">{nabu_static key=btn_launch_test}</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/nabu_modal}
{/if}

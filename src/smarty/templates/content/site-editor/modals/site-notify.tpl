{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_templates}
{*if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)*}
    {nabu_modal id=modal_site_notify size=lg caller=edit_zone aria_labelledby=modal_site_notify_head}
        {nabu_form layout="horizontal:2:10" method="ajax-post" action_template="/api/site/%s?action=notify"}
            {nabu_modal_header dismiss=true aria_label_id=modal_site_notify_head}Envío de notificación de actualización{/nabu_modal_header}
            {nabu_modal_body}
                <p>Se va a enviar un e-Mail a todos los usuarios que tengan activas las notificaciones para informarles de una actualización en el sitio.<br>Por favor, pulsa en &lt;Enviar&gt; para proceder al envío.</p>
            {/nabu_modal_body}
            {nabu_modal_footer}
                <button type="button" class="btn btn-link" data-dismiss="modal">{nabu_static key=btn_cancel}</button>
                <button type="submit" class="btn btn-success">{nabu_static key=btn_send}</button>
            {/nabu_modal_footer}
        {/nabu_form}
    {/nabu_modal}
{*/if*}

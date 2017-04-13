{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=modal_download section=modal_download}
{nabu_assign var=modal_download_success section=modal_download_success}
{nabu_assign var=modal_download_error section=modal_download_error}
{nabu_assign var=api_site cta=api_site}
{nabu_modal id=modal_download size=lg caller=site_list aria_labelledby=modal_download_head}
    {nabu_form layout="horizontal:2:10" method="post" action=$api_site.translations[$nb_site.api_language_id].final_url|sprintf:"?action=download"}
        <div class="modal-steps">
            <div class="modal-step" data-step="1">
                {nabu_modal_header dismiss=true aria_label_id=modal_download_head}{$modal_download.translation.title}{/nabu_modal_header}
                {nabu_modal_body}
                    <div class="row">
                        <aside class="col-sm-3">{$modal_download.translation.opening}</aside>
                        <section class="col-sm-9 col-sm-offset-3">
                            {$modal_download.translation.content}
                            <div class="alert alert-info">
                                {$modal_download.translation.aside}
                            </div>
                            {$modal_download.translation.footer}
                            <div id="sites_placeholder">
                            </div>
                        </section>
                    </div>
                {/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">{nabu_static key=btn_cancel}</button>
                    {nabu_form_command type="submit" formfollow="active" class="btn btn-success" anchor_text="{nabu_static key=btn_download}"}
                    <!--button type="submit" class="btn btn-success">{nabu_static key=btn_download}</button-->
                {/nabu_modal_footer}
            </div>
        </div>
        <div class="modal-panels hide">
            <div class="modal-panel" data-action="success">
                {nabu_modal_header dismiss=true}{$modal_download_success.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_download_success.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">{nabu_static key=btn_close}</button>
                {/nabu_modal_footer}
            </div>
            <div class="modal-panel" data-action="error">
                {nabu_modal_header dismiss=true}{$modal_download_error.translation.title}{/nabu_modal_header}
                {nabu_modal_body}{$modal_download_error.translation.opening}{/nabu_modal_body}
                {nabu_modal_footer}
                    <button type="button" class="btn btn-link" data-dismiss="modal">{nabu_static key=btn_close}</button>
                {/nabu_modal_footer}
            </div>
        </div>
    {/nabu_form}
{/nabu_modal}

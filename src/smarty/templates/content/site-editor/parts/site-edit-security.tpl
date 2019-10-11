{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=api cta=api_site}
<div class="edit-container">
    {if is_array($api) && array_key_exists('translations', $api) && is_array($api.translations) && array_key_exists($nb_site.api_language_id, $api.translations)}
        {assign var=url value="{$api.translations[$nb_site.api_language_id].final_url|sprintf:$edit_site.id}"}
        {nabu_form method="ajax-post" layout=vertical multiform=":root:security:" action=$url}
            {nabu_form_fieldset title={nabu_static key=tit_roles_and_users}}
                {nabu_form_row}
                    <div class="col-sm-5 col-md-4">
                        {nabu_form_select from=$edit_site field=default_role_id options=$edit_site.roles options_name=name label={nabu_static key=lbl_anonymous_role} options_default_name={nabu_static key=sel_choose_role}}
                    </div>
                {/nabu_form_row}
            {/nabu_form_fieldset}
            {nabu_form_fieldset title={nabu_static key=tit_sessions}}
                {nabu_form_row}
                    {nabu_form_textbox class="col-sm-3 col-md-2" type=number from=$edit_site field=max_signin_retries label={nabu_static key=lbl_max_signin_retries}}
                    {nabu_form_textbox class="col-sm-3 col-md-2" type=number from=$edit_site field=signin_lock_delay label={nabu_static key=lbl_signin_locked_delay}}
                {/nabu_form_row}
                {nabu_form_row}
                    <div class="col-sm-12">
                        {nabu_form_checkbox from=$edit_site field=require_policies_after_login check=T uncheck=F label={nabu_static key=lbl_require_policies}}
                    </div>
                    <div class="col-sm-12">
                        {nabu_form_checkbox from=$edit_site field=enable_session_strict_policies check=T uncheck=F label={nabu_static key=lbl_session_strict_policies}}
                    </div>
                    <div class="col-sm-12">
                        {nabu_form_checkbox from=$edit_site field=force_cookie_as_secure check=T uncheck=F label={nabu_static key=lbl_force_cookie_as_secure}}
                    </div>
                    <div class="col-sm-12">
                        {nabu_form_checkbox from=$edit_site field=force_cookie_as_httponly check=T uncheck=F label={nabu_static key=lbl_force_cookie_as_httponly}}
                    </div>
                {/nabu_form_row}
            {/nabu_form_fieldset}
            {nabu_form_fieldset title={nabu_static key=tit_clickjacking}}
                {nabu_form_row}
                    <div class="col-sm-12 col-md-12">
                        <label for="x_frame_options">{nabu_static key=lbl_x_frame_options}</label>
                        <div class="form-inline form-group">
                            <div class="input-group">
                                {strip}
                                    <span class="input-group-addon"><input type="radio" name="x_frame_options"{if $edit_site.x_frame_options===null} checked{/if} aria-label="{nabu_static key=opt_unasigned}" value="N"></span>
                                    <p class="form-control-static">{nabu_static key=opt_unasigned}</p>
                                {/strip}
                            </div>
    						<div class="input-group">
                                {strip}
                                    <span class="input-group-addon"><input type="radio" name="x_frame_options"{if $edit_site.x_frame_options===D} checked{/if} aria-label="DENY" value="D"></span>
                                    <p class="form-control-static">DENY</p>
                                {/strip}
                            </div>
                            <div class="input-group">
                                {strip}
                                    <span class="input-group-addon"><input type="radio" name="x_frame_options"{if $edit_site.x_frame_options===S} checked{/if} aria-label="SAMEORIGIN" value="S"></span>
                                    <p class="form-control-static">SAMEORIGIN</p>
                                {/strip}
                            </div>
                            <div class="input-group">
                                {strip}
                                    <span class="input-group-addon"><input type="radio" name="x_frame_options"{if $edit_site.x_frame_options===A} checked{/if} aria-label="ALLOW-FROM" value="A"></span>
                                    <p class="form-control-static">ALLOW-FROM</p>
                                    {nabu_form_textbox layout="input-group" from=$edit_site field=x_frame_options_url}
                                {/strip}
                            </div>
                        </div>
                    </div>
                {/nabu_form_row}
            {/nabu_form_fieldset}
        {/nabu_form}
    {/if}
</div>

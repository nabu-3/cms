{nabu_model model="bootstrap-3.3.7"}
{strip}
    {assign var=target_use_uri value="{$pattern}_target_use_uri"}
    {assign var=target_id value="{$pattern}_target_id"}
    {assign var=target_url value="{$pattern}_target_url"}
    {assign var=target_error_code value="{$pattern}_error_code"}
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group pull-left">
                {if isset($label) && is_string($label) && strlen($label)>0}<label>{$label}</label>{/if}
                <div class="form-inline">
                    <div class="input-group">
                        <span class="input-group-addon"><input type="radio" name="{$target_use_uri}"{if $source[$target_use_uri]==='N'} checked{/if} aria-label="" value="N"></span>
                        <p class="form-control-static">{nabu_static key=opt_unasigned}</p>
                    </div>
                    {nabu_form_select from=$source field=$target_id label=null
                                      addon_left=radiobox addon_left_field=$target_use_uri addon_left_check=T
                                      options=$edit_site.targets options_name=title
                                      options_default_id=0 options_default_name={nabu_static key=sel_template_unasigned}}
                    {nabu_form_textbox from=$source field=$target_url multilang=$edit_site.languages label=null
                                       addon_left=radiobox addon_left_field=$target_use_uri addon_left_check=U
                                       value_left=$source[$target_use_uri]}
                </div>
            </div>
            {if isset($use_http_code) && $use_http_code===true}
                {nabu_form_textbox class="pull-left" from=$source field=$target_error_code mandatory=false size=3 maxlength=3 rule=regex rule_param="^[1-5][0-9]{ldelim}2{rdelim}}$" label="{nabu_static key=lbl_http_code}"}
            {/if}
        </div>
    </div>
{/strip}

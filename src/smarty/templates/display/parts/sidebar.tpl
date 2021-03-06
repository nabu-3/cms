{nabu_model model="bootstrap-3.3.7"}
<div class="col-md-2 col-sm-3 sidebar">
    {if is_array($nb_user)}
        <aside class="panel panel-default">
            <div class="panel-body">
                {nabu_form vertical=true method=POST}
                    {nabu_form_select options=$nb_customer_list options_id=id options_name=fiscal_name label='Cliente'
                                      options_default_name='&lt;Elige un cliente&gt;'
                                      from=$nb_work_customer field=id name='__x_nb_wc' id=__x_nb_wc}
                {/nabu_form}
            </div>
        </aside>
    {/if}
    {nabu_exists sitemap=main_menu}
        {nabu_assign var=sidebar_menu sitemap=main_menu level=1}
        {if count($sidebar_menu.childs)>0}
            <aside>
                <h3>Menu</h3>
                {nabu_navigation sitemap=$sidebar_menu.childs type=pill position=stacked}
            </aside>
        {/if}
    {/nabu_exists}
</div>

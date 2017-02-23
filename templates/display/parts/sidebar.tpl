{nabu_model model="bootstrap-3.3.7"}
<div class="col-md-2 col-sm-3 sidebar">
    <aside class="panel panel-default">
        {nabu_form vertical=true method=POST}
            {nabu_form_select options=$nb_customer_list options_id=id options_name=fiscal_name label='Cliente'
                              options_default_name='&lt;Elige un cliente&gt;'
                              from=$nb_work_customer field=id name='__x_nb_wc' id=__x_nb_wc}
        {/nabu_form}
    </aside>
    <aside>
        <h1>Menu</h1>
        {nabu_assign var=sidebar_menu sitemap=main_menu level=1}
        {nabu_navigation sitemap=$sidebar_menu.childs type=pill position=stacked}
    </aside>
</div>

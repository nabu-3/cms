{nabu_model model="bootstrap-3.3.7"}
{nabu_assign var=main_menu sitemap=main_menu}
{nabu_navbar fixed=top inverse=true class="navbar-inverse" container=fluid header=true collapsable=navbar brand=$main_menu.translation brand_name=$nb_site.translation.name}
    {nabu_navigation sitemap=$main_menu.childs type=navbar deep=1}
    <ul class="nav navbar-nav navbar-right">
        {*<li><a href="#">Bookmark</a></li>
        <li><a href="#">Book</a></li>*}
        {if $nb_user !== null}
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i>&nbsp;{"{$nb_user.first_name} {$nb_user.last_name}"|trim} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/es/mi-perfil"><i class="fa fa-user"></i> Mi perfil</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/es/logout"><i class="fa fa-sign-out"></i>&nbsp;Salir</a></li>
                </ul>
            </li>
        {/if}
    </ul>
{/nabu_navbar}

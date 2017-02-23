{nabu_model model="bootstrap-3.3.7"}
{if strlen($nb_site_target.translation.opening)}{$nb_site_target.translation.opening}{/if}
{nabu_assign var=centre_menu sitemap=main_menu level=1}
{nabu_navigation sitemap=$centre_menu.childs type=pill}

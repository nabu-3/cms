<footer class="navbar navbar-inverse navbar-fixed-bottom">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="{$NABU_LICENSE_TARGET}" target="_blank" class="inline">{$NABU_LICENSE_TITLE}</a>{if $NABU_OWNER!==$NABU_LICENSED}<span> to </span>{if $NABU_LICENSEE_TARGET !== ''}<a href="{$NABU_LICENSEE_TARGET}" class="inline">{$NABU_LICENSED}</a>{else}{$NABU_LICENSED}{/if}{/if}</li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="#">&copy; 2014-{'Y'|date} {$NABU_OWNER}</a></li>
        </ul>
    </div>
</footer>

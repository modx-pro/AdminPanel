<nav class="adminpanel ap-navbar ap-navbar-fixed-bottom [[+class_theme]] [[+class_status]]" role="navigation"
     style="opacity:[[+inactive_opacity]];">
    <ul class="ap-nav ap-navbar-nav">
        <li class="ap-link-first"><a href="[[+resource_update]]&id=[[*id]]" class="ap-navbar-link">[[%ap_edit_this]]</a>
        </li>
        [[+groups]]
    </ul>

    <ul class="ap-nav ap-navbar-nav ap-pull-right">
        <li>
            <a href="https://modstore.pro/adminpanel" class="ap-navbar-brand ap-pull-right" target="_blank">[[%adminpanel]]</a>
        </li>
        <li>
            <a href="#" class="ap-scroll-up" style="display:none;">
                &uarr;
            </a>
        </li>
    </ul>

    [[+msearch2_index:notempty=`
    <form class="ap-navbar-form ap-pull-right ap-msearch2" role="search" method="get" action="[[+msearch2_index]]"
          target="_blank">
        <input type="hidden" name="a" value="[[+msearch2_index_id]]">
        <input type="text" name="query" class="ap-msearch-query" placeholder="[[%ap_mse2_search]]" autocomplete="off">
    </form>
    `]]
</nav>
<div class="ap-close [[+class_theme]]"><b class="ap-caret [[+class_status]]"></b></div>
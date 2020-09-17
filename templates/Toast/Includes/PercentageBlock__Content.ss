<div class="percentageBlock__wrap__item column xmd-{$Width} verticalAlign verticalAlign--top">
    <% if $Media %>
        <div class="percentageBlock__wrap__item__media media {$Position} <% if not $ContentWidth %>full-width<% end_if %> " style="background-image:url('{$Media}')"></div>
    <% end_if %>
    
    <div class="percentageBlock__wrap__item__content content">

        <div class="percentageBlock__wrap__item__content__body" data-equalize-watch>
            $Content
        </div>

        <% if $Link %>
            <% with $Link %>
                <p><a href="{$LinkURL}" {$TargetAttr} class="redirect--arrow percentageBlock__wrap__item__link">{$Title}</a></p>
            <% end_with %>
        <% end_if %>
    </div>
</div>
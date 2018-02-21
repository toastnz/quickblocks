<div class="tabbedContent wysiwyg">
    <div class="innerWrapSmall">

        <% loop $GroupedItems.GroupedBy('GroupNumber') %>
            <div class="tabbedContent__triggers flex">
                <% loop $Children %>
                    <a href="#" class="tabbedContent__triggers__item [ js-tabbed-content-trigger ] <% if $First && $Up.First %>active<% end_if %>" data-id="{$ID}">
                        {$DisplayTitle.UpperCase}
                        <div class="triangle"></div>
                    </a>
                <% end_loop %>
            </div>

            <div class="tabbedContent__copy">
                <% loop $Children %>
                    <div class="tabbedContent__copy__item [ js-tabbed-content ]" <% if $First && $Up.First %>style="display:block;"<% end_if %> data-id="{$ID}">
                        {$Content}
                    </div>
                <% end_loop %>
            </div>

        <% end_loop %>

    </div>
</div>
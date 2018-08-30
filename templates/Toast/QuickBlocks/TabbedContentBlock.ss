<section class="tabsBlock">
    <div class="tabsBlock__content row">
        <div class="column">
            <div class="tabsBlock__content__heading">
                <h4>$Title</h4>
            </div>
        </div>
        <div class="tabsBlock__content__links column lg-6">
            <ul class="tabsBlock__content__links__list">
                <% loop $Tabs %>
                    <li class="tabsBlock__content__links__list__item">
                        <a class="tabsBlock__content__links__list__item__link <% if $First %>[ js-active ]<% end_if %>" href="#" data-tabs-link="{$ID}">$Title</a>
                    </li>
                <% end_loop %>
            </ul>
        </div>
        <div class="tabsBlock__content__panels column lg-6 wysiwygBlock text-left clearfix">
            <% loop $Tabs %>
                <div class="tabsBlock__content__panels__item <% if $First %>[ js-active ]<% end_if %>" data-tabs-panel="{$ID}">
                    $Content
                </div>
            <% end_loop %>
        </div>
    </div>
</section>
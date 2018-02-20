<% if $Statistics %>
    <div class="iconBlock wysiwyg">
        <div class="innerWrapSmall">
            <% if $Heading %>
                <p><strong>{$Heading}</strong></p>
            <% else %>
                <p><strong>Recent statistics</strong></p>
            <% end_if %>
            <div class="flex">
                <div class="iconBlock__item iconBlock__item--icon">
                    $SVG('statistic')
                </div>

                <div class="iconBlock__slider [ js-icon-block-slider ]">
                    <% loop $Statistics %>
                        <div class="iconBlock__item iconBlock__item--text">
                            <h5 class="iconBlock__item__title">{$Title}</h5>
                            <p class="iconBlock__item__link">
                                <a href="#" class="[ js-modal ]" data-api-endpoint="{$ApiURL}">$SVG('read-more') VIEW CHART</a>
                            </p>
                        </div>
                    <% end_loop %>
                </div>
            </div>
        </div>
    </div>
<% end_if %>
<div class="splitList">
    <div class="wysiwyg">
        <div class="flex">
            <div class="splitList__item splitList__item--left splitList__item--purple">
                <div class="splitList__item__wrap">
                    <div class="flex">
                        <div class="splitList__item__wrap__title">
                            <% if $LeftHeading %>
                            <h3 class="colour--white"><strong>{$LeftHeading.UpperCase}</strong></h3>
                            <% end_if %>
                        </div>
                        <div class="splitList__item__wrap__copy">
                            <% if $LeftContent %>
                                {$LeftContent}
                            <% end_if %>
                        </div>
                    </div>
                </div>
            </div>
            <div class="splitList__item splitList__item--right splitList__item--green">
                <div class="splitList__item__wrap">
                    <div class="flex">
                        <div class="splitList__item__wrap__title">
                            <% if $RightHeading %>
                                <h3 class="colour--white"><strong>{$RightHeading.UpperCase}</strong></h3>
                            <% end_if %>
                        </div>
                        <div class="splitList__item__wrap__copy">
                            <% if $RightContent %>
                                {$RightContent}
                            <% end_if %>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
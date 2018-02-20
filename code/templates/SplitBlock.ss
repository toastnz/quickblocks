<div class="splitBlock wysiwyg">
    <div class="flex">

        <div class="splitBlock__item splitBlock__item--purple splitBlock__item--left">
            <div class="splitBlock__item__fauxWrap">
                <div class="flex">
                    <div class="splitBlock__item__fauxWrap__copy">
                        <% if $LeftHeading %>
                            <h3 class="colour--white"><strong>{$LeftHeading.UpperCase}</strong></h3>
                        <% end_if %>
                        <% if $LeftContent %>
                            <p>{$LeftContent}</p>
                        <% end_if %>
                        <% if $LeftLink %>
                            <% with $LeftLink %>
                                <p class="splitBlock__item__fauxWrap__copy__link"><a href="{$LinkURL}" {$TargetAttr}>$SVG('read-more') <strong>{$Title.UpperCase}</strong></a></p>
                            <% end_with %>
                        <% end_if %>
                    </div>
                    <% if $LeftImage %>
                        <div class="splitBlock__item__fauxWrap__image" style="background-image:url('{$LeftImage.Fill(400,400).URL}');"></div>
                    <% end_if %>
                </div>
            </div>
        </div>

        <div class="splitBlock__item splitBlock__item--green splitBlock__item--right">
            <div class="splitBlock__item__fauxWrap">
                <div class="flex">
                    <div class="splitBlock__item__fauxWrap__copy">
                        <% if $RightHeading %>
                            <h3 class="colour--white"><strong>{$RightHeading.UpperCase}</strong></h3>
                        <% end_if %>
                        <% if $RightContent %>
                            <p>{$RightContent}</p>
                        <% end_if %>
                        <% if $RightLink %>
                            <% with $RightLink %>
                                <p class="splitBlock__item__fauxWrap__copy__link"><a href="{$LinkURL}" {$TargetAttr}>$SVG('read-more') <strong>{$Title.UpperCase}</strong></a></p>
                            <% end_with %>
                        <% end_if %>
                    </div>
                    <% if $RightImage %>
                        <div class="splitBlock__item__fauxWrap__image" style="background-image:url('{$RightImage.Fill(400,400).URL}');"></div>
                    <% end_if %>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="heroBlock $ColourClass wysiwyg">
    <div class="flex">
        <% if $Image %>
            <div class="heroBlock__image" style="background-image: url('{$Image.FocusFill(820,520).URL}');"></div>
        <% end_if %>
        <div class="heroBlock__copy">
            <div class="heroBlock__copy__wrap">
                <% if $Heading %>
                    <h2 class="heroBlock__copy__wrap__title">{$Heading.UpperCase}</h2>
                <% end_if %>
                <% if $Content %>
                    <p>{$Content}</p>
                <% end_if %>
                <% if $Link %>
                    <% with $Link %>
                        <p><a href="{$LinkURL}" {$TargetAttr} class="heroBlock__copy__wrap__link">$SVG('read-more') {$Title.UpperCase}</a></p>
                    <% end_with %>
                <% end_if %>
            </div>
        </div>
    </div>
</div>
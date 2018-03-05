<div class="newsContent">
    <div class="innerWrap">
        <div class="inlineVideo [ js-video ]" data-video-id="{$VideoID}">
            <div class="inlineVideo__wrap">
                <% if $Thumbnail %>
                    {$Thumbnail.FocusFill(960,540)}
                <% else %>
                    <img src="https://img.youtube.com/vi/{$VideoID}/maxresdefault.jpg">
                <% end_if %>
                <div class="inlineVideo__play">
                    <div class="responsiveSVG">
                        $SVG('play-large')
                    </div>
                </div>
            </div>
            <% if $Caption %>
                <span class="inlineVideo__caption">{$Caption}</span>
            <% end_if %>
        </div>
    </div>
</div>
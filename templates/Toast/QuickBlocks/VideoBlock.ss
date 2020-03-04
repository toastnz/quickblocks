<%-------------------------------------------------------%>
<%-- Video Block --%>
<%-------------------------------------------------------%>

<div class="block blockVideo wysiwyg">
    <div class="innerWrap">
        <div class="blockVideo__thumbnail [ js-video-modal ]" data-video="$Video.URL" data-video-id="{$Video.VideoID}">
            <% if $ThumbnailID %>
                {$Thumbnail.FocusFill(1920,1080)}
            <% else %>
                <img src="$Video.ThumbnailURL" style="margin: 0 auto;">
            <% end_if %>

            <div class="blockVideo__thumbnail__play">
                $SVG('play')
                <p>WATCH VIDEO</p>
            </div>
        </div>
        <% if $Caption %>
            <p class="blockVideo__caption">
                $Caption
            </p>
        <% end_if %>
    </div>
</div>

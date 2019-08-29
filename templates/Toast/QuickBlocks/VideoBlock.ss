<%--<div class="newsContent">
    <div class="innerWrap">
        <div class="inlineVideo [ js-video ]" data-video-id="{$VideoID}">
            <div class="inlineVideo__wrap">
                <% if $VideoThumbnail %>
                    {$VideoThumbnail($thumbQualityFront)}
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
</div>--%>

<section class="videoBlock quickBlock">
	<div class="row">
		<div class="column">
		<% if $Caption %>
            <h3>$Caption</h3>
        <% end_if %>
			<div class="videoBlock__item [ js-play-video ]" data-src="$Video" style="background-image: url('{$VideoThumbnail($thumbQualityFront)}');">
				$SVG('play-small')
			</div>
		</div>
	</div>
</section>
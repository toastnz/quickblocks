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

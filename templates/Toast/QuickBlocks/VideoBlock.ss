<section class="videoBlock contentBlock">
    <div class="videoBlock__wrap row">
        <div class="column">
            <a href="#" data-video="$Video.URL" style="background-image: url('<% if $ThumbnailID %>$Thumbnail.URL<% else %>$Video.ThumbnailURL<% end_if %>');" class="videoBlock__wrap__media [ js-embed ]">
                $SVG('play')
            </a>
        </div>
    </div>
</section>


<div class="videosBlock wysiwyg">
    <div class="innerWrapSmall">
        <div class="flex">

            <% loop $MediaItems %>
                <% if $File %>
                    <a href="{$FileDownloadLink}" class="mutliDownloads__item">
                        <div class="mutliDownloads__item__icon">
                            $SVG('download-large')
                        </div>
                        <% if $Heading %>
                            <h4 class="mutliDownloads__item__title">{$Heading.UpperCase}</h4>
                        <% else %>
                            <h4 class="mutliDownloads__item__title">{$File.Title.UpperCase}</h4>
                        <% end_if %>
                        <p class="mutliDownloads__item__link">
                            $SVG('download-small') <strong>DOWNLOAD</strong>
                        </p>
                    </a>
                <% else_if $VideoID %>
                    <div class="videosBlock__item [ js-video ]" data-video-id="{$VideoID}">
                        <% if $Thumbnail %>
                            {$Thumbnail.Fill(930,580)}
                        <% else %>
                            <img src="https://img.youtube.com/vi/{$VideoID}/maxresdefault.jpg">
                        <% end_if %>
                        $SVG('play')
                    </div>
                <% else %>
                    <% if $Link %>
                        <a href="{$Link.LinkURL}" {$Link.TargetAttr} class="imageLinkBlock__item">
                            {$Thumbnail.Fill(930,580)}
                        </a>
                    <% else %>
                        <div class="imageLinkBlock__item">
                            {$Thumbnail.Fill(930,580)}
                        </div>

                    <% end_if %>
                <% end_if %>
            <% end_loop %>

        </div>
    </div>
</div>
<% if $Files %>
    <div class="inlineDownload wysiwyg">
        <div class="innerWrap">
            <% loop $Files %>
                <p>
                    <a href="{$DownloadLink}">
                        $SVG('inline-download')
                        <span><strong>{$Title}</strong>{$FileInfo}</span>
                    </a>
                </p>
            <% end_loop %>
        </div>
    </div>
<% end_if %>

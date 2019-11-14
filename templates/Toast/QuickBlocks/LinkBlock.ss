<% if $Items %>
    <section class="linkBlock contentBlock" data-equalize="summary,heading">
        <div class="linkBlock__wrap row md-up-2 xmd-up-$Columns">
            <% loop $Items %>
                <a href="$Link.LinkURL" class="linkBlock__wrap__item column">
                    <% if $Image %>
                        <div class="linkBlock__wrap__item__media media" style="background-image: url('{$Image.fill(640,640).URL}');">
                            <div class="linkBlock__wrap__item__media__content hoverContent">
                                <div class="alignContent">
                                    <div class="contentRow">
                                        <div class="verticalAlign verticalAlign--top" data-equalize-watch="summary">
                                            <p>$Summary</p>
                                        </div>
                                    </div>
                                    <div class="contentRow">
                                        <div class="verticalAlign verticalAlign--bottom">
                                            <span class="linkBlock__wrap__item__link link redirect--arrow">Read more</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <% end_if %>
                    <div class="linkBlock__wrap__item__details" data-equalize-watch="heading">
                        <h5>$Title</h5>
                        $Content
                    </div>
                </a>
            <% end_loop %>
        </div>
    </section>
<% end_if %>
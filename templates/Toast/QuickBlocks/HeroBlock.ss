<section class="heroBlock contentBlock">
    <% if $ContentWidth %><div class="row"><div class="column"><% end_if %>
    <div class="heroBlock__item" data-parallax>

        <% if $BackgroundImage %>
            <div class="heroBlock__item__background" data-parallax-watch style="background-image: url('{$BackgroundImage.URL}');"></div>
        <% end_if %>

        <% if $Content || $Title %>
            <a href="$BlockLink.LinkURL" class="heroBlock__item__wrap height alignContent">
                <div class="verticalAlign">
                    <div class="row">
                        <% if $Content %>
                            <div class="heroBlock__item__wrap__content column heroContent">
                                $Content
                            </div>
                        <% end_if %>
                    </div>
                </div>
            </a>
        <% end_if %>
    </div>
    <% if $ContentWidth %></div></div><% end_if %>
</section>

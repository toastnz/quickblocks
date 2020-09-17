<section class="percentageBlock contentBlock" style="background-image: url('{$DefaultImage.FocusFill(1920,1080).URL}');">
    <div class="percentageBlock__wrap row xmd-up-alignContent <% if not $ContentWidth %>collapse<% end_if %>" data-equalize>
        <% include Toast\Includes\PercentageBlock__Content Position='left', Width=$getWidth('left'), Media=$LeftBackgroundImageURL, ContentWidth=$ContentWidth, Content=$LeftContent, Link=$LeftLink %>
        <% include Toast\Includes\PercentageBlock__Content Position='right', Width=$getWidth('right'), Media=$RightBackgroundImageURL, ContentWidth=$ContentWidth, Content=$RightContent, Link=$RightLink %>
    </div>
</section>

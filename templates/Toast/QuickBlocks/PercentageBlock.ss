<%----------------------------------------------------------------
Static Percentage Block
Modifiers :
width: --25 --33 --50 --66 --75 --100
type: --image --text
colour: --red --light-grey --dark-grey
----------------------------------------------------------------%>

<section class="contentBlock percentageBlock <% if $Fullwidth %>percentageBlock--fullwidth<% end_if %>">

    <div class="contentBlock__wrap percentageBlock__wrap">

        <div class="percentageBlock__wrap__item percentageBlock__wrap__item--{$getColour('left')} percentageBlock__wrap__item--text percentageBlock__wrap__item--{$getWidth('left')}">
            <div class="percentageBlock__wrap__item__pattern" style="background-image:url('{$LeftImage.URL}')"></div>
            <div data-aos="fade-up" data-aos-duration="800">
            <% if $LeftHeading %>
                <h4 class="colour--white"><b>$LeftHeading</b></h4>
                <% end_if %>
                $LeftContent
            </div>
        </div>

        <div class="percentageBlock__wrap__item percentageBlock__wrap__item--{$getColour('right')} percentageBlock__wrap__item--text percentageBlock__wrap__item--{$getWidth('right')}">
            <div class="percentageBlock__wrap__item__pattern" style="background-image:url('{$RightImage.URL}')"></div>
            <div data-aos="fade-up" data-aos-duration="800">
            <% if $RightHeading %>
                <h4 class="colour--white"><b>$RightHeading</b></h4>
            <% end_if %>

                $RightContent
            </div>
        </div>

    </div>

</section>

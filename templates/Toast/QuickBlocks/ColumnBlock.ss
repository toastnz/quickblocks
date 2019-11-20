<section class="columnBlock contentBlock--padding">
    <% if $Heading %>
        <div class="columnBlock__header row">
            <div class="column">
                <h4 class="columnBlock__header__title">$Heading</h4>
            </div>
        </div>
    <% end_if %>
    <div class="columnBlock__wrap row xmd-up-alignContent xmd-up-$getColumns()">

        <div class="columnBlock__wrap__item column verticalAlign verticalAlign--top">
            <div class="columnBlock__wrap__item__content">
                $ContentLeft
            </div>
        </div>

        <div class="columnBlock__wrap__item column verticalAlign verticalAlign--top">
            <div class="columnBlock__wrap__item__content">
                $ContentMiddle
            </div>
        </div>

        <div class="columnBlock__wrap__item column verticalAlign verticalAlign--top">
            <div class="columnBlock__wrap__item__content">
                $ContentRight
            </div>
        </div>

    </div>
</section>
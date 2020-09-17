<section class="accordionBlock contentBlock [ js-accordion ]">
    <div class="accordionBlock__wrap row">
        <div class="column">
            <% loop $Items %>
                <div class="accordionBlock__wrap__item">
                    <a href="#" class="accordionBlock__wrap__item__heading [ js-accordion--trigger ]">
                        <h5 class="accordionBlock__wrap__item__heading__title">$Heading</h5>
                        <div class="plus"></div>
                        <div class="plus"></div>
                    </a>
                    <div class="[ js-accordion--target ]">
                        <div class="accordionBlock__wrap__item__content">
                            $Content
                        </div>
                    </div>
                </div>
            <% end_loop %>
        </div>
    </div>
</section>
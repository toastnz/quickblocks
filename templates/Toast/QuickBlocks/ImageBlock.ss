<section class="imageBlock marginBlock">

    <h1>IMAGE BLOCK</h1>

    <% if $Heading || $Summary %>
        <% include Heading Heading=$Heading, Summary=$Summary %>
    <% end_if %>
    <div class="imageBlock__wrap row">
        <div class="column">
        <% with $Image %>
            <img src="{$Fit(1100,600).URL}" alt="$Title" class="imageBlock__wrap__media">
        <% end_with %>
        </div>
    </div>
</section>
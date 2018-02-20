<div class="modal wysiwyg">
    <div class="modal__header">
        <p class="colour--white"><strong>{$Title}</strong></p>
    </div>
    <div class="modal__content">
        <% loop $Graphs %>
            {$Fit(845,845)}
        <% end_loop %>
    </div>
</div>
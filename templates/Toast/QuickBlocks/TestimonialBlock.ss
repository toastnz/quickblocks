<%--<% if $Alignment == 'left'%>--%>
    <div class="wysiwyg testimonial testimonial--{$Alignment}">
        <div class="innerWrap">
            <div class="flex">
                <h5>
                    <span class="testimonial__icon">$SVG('quotes-dark')</span>
                    {$Testimonial}
                </h5>
                <p><span class="colour--{$Colour}">{$Attribution},</span> $Location</p>
            </div>
        </div>
    </div>
<% else %>
    <div class="wysiwyg testimonial testimonial--{$Alignment}">
        <div class="innerWrap">
            <div class="flex">
                <p><span class="colour--{$Colour}">{$Attribution},</span> $Location</p>
                <h5>
                    <span class="testimonial__icon">$SVG('quotes-dark')</span>
                    {$Testimonial}
                </h5>
            </div>
        </div>
    </div>
<%--<% end_if %>--%>

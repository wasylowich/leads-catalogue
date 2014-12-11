$(function() {

    var $leads             = $('#leads');
    var $links             = $('#links');

    var leadTemplate = $('#lead-template').html();

    // Get a list of leads (paginated)
    function getLeads() {
        $.ajax({
            type: 'GET',
            url: '/leads',
            success: function(data) {
                buildLeads(data['leads']);
                $("#links").html(data['links']);
            },
            error: function(data) {
                alert('Error loading leads.');
            }
        });
    }

    // Build a collection of leads into the page
    function buildLeads(leads) {
        $("#leads").html('');
        $.each(leads, function(idx, lead) {
            addLead(lead);
        });
        $("#leads .preferred_format").hide();
    }

    // Build a single lead into the page
    function addLead(lead) {
        lead.newsletter = (lead.newsletter == 1) ? lead.newsletter_subscription.format : 'Not Subscribed';
        $leads.append(Mustache.render(leadTemplate, lead));
    }

    function flashMessage(type, message) {
        // Adding a 'x' button if the user wants to close manually
        $("#result").html('<div class="alert alert-'+type+'"><button type="button" class="close">×</button>'+message+'</div>');

        // Timing the alert box to close after 5 seconds
        window.setTimeout(function () {
         $(".alert").fadeTo(500, 0).slideUp(500, function () {
             $(this).remove();
         });
        }, 5000);

        // Adding a click event to the 'x' button to close immediately
        $('.alert .close').on("click", function (e) {
         $(this).parent().fadeTo(500, 0).slideUp(500);
        });
    }

    // Reset the newLeadForm with default values
    function resetNewLeadForm() {
        var $form = $("#newLeadForm");

        $form.find('input[name="name"]').val('');
        $form.find('input[name="email"]').val('');
        $form.find('input[name="phone"]').val('');
        $form.find('input[name="address"]').val('');
        $form.find('input[name="postal_code"]').val('');
        $form.find('input[name="city"]').val('');
        $form.find('input[name="newsletter"]').attr('checked', 'checked');
        $form.find('input[name="newsletter_format"]')[0].checked = true;
        $form.find('input[name="newsletter_format"]')[1].checked = false;
    }

    // Default action on page load - get a collection of leads (paginated)
    $.ajax({
        type: 'GET',
        url: '/leads',
        success: function(data) {
            buildLeads(data['leads']);
            // DEPRECATED: Due to pagination issues, can no longer add individual leads dynamcially
            // $.each(data['leads'], function(idx, lead) {
            //     addLead(lead);
            // });
            // $("#leads .preferred_format").hide();
            $("#links").html(data['links']);
        },
        error: function(data) {
            alert('Error loading leads.');
        }
    });

    // Listener for clicks on pagination links
    $links.delegate('.pagination a', 'click', function() {

        event.preventDefault();

        var $url = $(this).attr('href');

        $.ajax({
            type: 'GET',
            url: $url,
            success: function(data) {
                buildLeads(data['leads']);
                // DEPRECATED: Due to pagination issues, can no longer add individual leads dynamcially
                // $.each(data['leads'], function(idx, lead) {
                //     addLead(lead);
                // });
                // $("#leads .preferred_format").hide();
                $("#links").html(data['links']);
            },
            error: function(data) {
                alert('Error loading leads.');
            }
        });
    });

    // Listener for search form submission
    $("#searchForm").submit(function(event) {

        event.preventDefault();

        var $form = $(this);

        search = {
            search_term: $form.find('input').val()
        }

        $.ajax({
            type: 'GET',
            url: '/leads',
            data: search,
            success: function(data) {
                buildLeads(data['leads']);
                $("#links").html(data['links']);
                $form.find('input').val('');
            },
            error: function() {
                var message = 'Error searching.';
                flashMessage('warning', message);
            }
        });
    });

    // Listener for newLeadForm submission
    $("#newLeadForm").submit(function(event) {

        event.preventDefault();

        var $form = $(this);

        var $newsletter = ($form.find('input[name="newsletter"]').prop('checked') === true) ? 1 : 0;

        var lead = {
            name: $form.find('input[name="name"]').val(),
            email: $form.find('input[name="email"]').val(),
            phone: $form.find('input[name="phone"]').val(),
            address: $form.find('input[name="address"]').val(),
            postal_code: $form.find('input[name="postal_code"]').val(),
            city: $form.find('input[name="city"]').val(),
            newsletter: $newsletter,
            newsletter_format: $form.find('input[name="newsletter_format"]:checked').val(),
        };

        $.ajax({
            type: 'POST',
            url: '/leads',
            data: lead,
            success: function(data) {
                buildLeads(data['leads']);
                // DEPRECATED: Due to pagination issues, can no longer add individual leads dynamcially
                // $.each(leads, function(idx, lead) {
                //     addLead(lead);
                // });
                // $("#leads .preferred_format").hide();
                $("#links").html(data['links']);
                $('#newLeadModal').modal('toggle');
                resetNewLeadForm();
                var message = 'Thank you for signing up.';
                flashMessage('success', message);
            },
            error: function() {
                var message = 'Error creating new lead.';
                flashMessage('warning', message);
            }
        });
    });

    // Listener for deleting a lead
    $leads.delegate('.remove', 'click', function() {

        var $panel = $(this).closest('.panel');

        $.ajax({
            type: 'DELETE',
            url: '/leads/' + $(this).attr('data-id'),
            success: function(data) {
                $panel.fadeOut(1000, function() {
                    $panel.remove();
                    getLeads();
                });
            },
            error: function() {
                alert('Error deleting the lead.')
            }
        });
    });

    // Listener for initiating the edit action of a lead
    $leads.delegate('.editLead', 'click', function() {
        var $panel = $(this).closest('.panel');

        $panel.find('input.name').val( $panel.find('span.name').html() );
        $panel.find('input.email').val( $panel.find('span.email').html() );
        $panel.find('input.phone').val( $panel.find('span.phone').html() );
        $panel.find('input.address').val( $panel.find('span.address').html() );
        $panel.find('input.postal_code').val( $panel.find('span.postal_code').html() );
        $panel.find('input.city').val( $panel.find('span.city').html() );
        if ($panel.find('span.newsletter').html() != 'Not Subscribed') {
            $panel.find('input.newsletter').attr('checked', 'checked');
            $format = $panel.find('span.newsletter').html();
            if ($format == 'html') {
                $panel.find('input.newsletter_format')[0].checked = true;
            } else {
                $panel.find('input.newsletter_format')[1].checked = true;
            }
            $panel.find('.preferred_format').show();
        } else {
            $panel.find('input.newsletter_format')[0].checked = true;
        }

        $panel.addClass('edit');

        if ($panel.find('input.newsletter').checked === true) {
            $panel.find('.preferred_format').show();
        }
    });

    // Listener for canceling the edit action of a lead
    $leads.delegate('.cancelEdit', 'click', function() {
        var $panel = $(this).closest('.panel');

        $panel.removeClass('edit');
        $panel.find('.preferred_format').hide();
    });

    // Listener for saving and edited lead
    $leads.delegate('.saveEdit', 'click', function() {
        var $panel = $(this).closest('.panel');
        var $newsletter = ($panel.find('input[name="newsletter"]').prop('checked') === true) ? 1 : 0;

        var lead = {
            name: $panel.find('input[name="name"]').val(),
            email: $panel.find('input[name="email"]').val(),
            phone: $panel.find('input[name="phone"]').val(),
            address: $panel.find('input[name="address"]').val(),
            postal_code: $panel.find('input[name="postal_code"]').val(),
            city: $panel.find('input[name="city"]').val(),
            newsletter: $newsletter,
            newsletter_format: $panel.find('input[name="newsletter_format"]:checked').val(),
        };

        $.ajax({
            type: 'PUT',
            url: '/leads/' + $panel.attr('data-id'),
            data: lead,
            success: function() {
                $panel.find('span.name').html(lead.name);
                $panel.find('span.email').html(lead.email);
                $panel.find('span.phone').html(lead.phone);
                $panel.find('span.address').html(lead.address);
                $panel.find('span.postal_code').html(lead.postal_code);
                $panel.find('span.city').html(lead.city);
                $panel.find('span.newsletter').html(lead.newsletter);

                $panel.removeClass('edit');
                $panel.find('.preferred_format').hide();
            },
            error: function() {
                alert('Error updating the lead.');
            }
        });
    });

    // Listener for toggling the newsletter chackbox in the edit dialogue
    $leads.delegate('.newsletter', 'click', function() {
        var $newsletterOptions = $(this).closest('.newsletter-options');

        if ($(this).prop('checked')) {
            $newsletterOptions.find('.preferred_format').show(300);
        } else {
            $newsletterOptions.find('.preferred_format').hide(300);
        }
    });

    // Listener for toggling the newsletter checkbox in the newLeadForm
    $('#newsletter').on('click', function() {
        var $newsletterOptions = $(this).closest('.newsletter-options');

        if ($(this).prop('checked')) {
            $newsletterOptions.find('.preferred_format').show(300);
        } else {
            $newsletterOptions.find('.preferred_format').hide(300);
        }
    });

    // Listener for refreshing the page
    $('.navbar-brand').on('click', function() {
        getLeads();
    });
});

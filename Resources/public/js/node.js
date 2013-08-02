/*
*
* Maybe some small refactoring here?
*
*/

//nodes on tree click
$('#nodesContainer').on("click", "a[data-remote]", function() {

    var container = $('#' + $(this).data('remote'));

    $.get($(this).attr('href'), function(data) {
        container.html(data.content);

        //attach content selector
        container.find('a[data-select-content]').selectNodeContent();

    }, 'json');

    return false;
});

//node edit form submission
$('#formContainer').on("submit", 'form[data-remote]', function() {
    var form = $(this);
    var querystring = form.serialize();
    $.post(form.attr('action'), querystring, function(data) {
        if (data.verdict == 'success') {
            //refresh list or reload window
            if (false) {
                window.location.reload();
            } else {
                form.parent().empty().append(data.content);
            }
        } else {
            //error - replace the form with populated errors
            form.parent().empty().append(data.content);
        }
    }, 'json');

    return false;
});

//confirmations
$('a[data-confirm]').confirmModal();
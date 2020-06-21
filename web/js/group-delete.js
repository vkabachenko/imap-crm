$(function() {
    $('.email-check').change(function() {
        var checked = false;
        $('.email-check').each(function() {
            if ($(this).prop('checked')) {
                checked = true;
            }
        });
        actionButtonShow(checked);
    });

    $('.all-emails-check').change(function() {
        var checked = $(this).prop('checked');
        $('.email-check').each(function() {
            $(this).prop('checked', checked);
        });
        actionButtonShow(checked);
    });

    $('.group-delete').click(function(evt) {
        evt.preventDefault();
        var checked = [];
        $('.email-check').each(function() {
            if ($(this).prop('checked')) {
                checked.push($(this).closest('td').closest('tr').attr('data-key'));
            }
        });

        $.ajax({
            url: $(this).attr('href'),
            method: 'POST',
            data: {checked: checked}
        }).then(function() {
            location.reload();
        }).catch(function(error) {
            console.log(error.message);
            alert('Ошибка удаления');
        })

    });

    function actionButtonShow(checked) {
        var div = $('.group-delete').closest('div');
        if (checked) {
            div.show();
        } else {
            div.hide();
        }
    }
})
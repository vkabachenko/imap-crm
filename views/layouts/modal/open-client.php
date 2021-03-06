<?php

?>


<!-- Modal -->
<div class="modal fade" id="client-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<?php
$script = <<<JS
    $(document).on('click', '.open-client', function(evt) {
        evt.preventDefault();
        $("#client-modal .modal-body").text('Загружаются данные...');
        $('#client-modal').modal('show');
        
        $.ajax({
            url: $(this).attr("href"),
            method: "GET",
            success: function(html) {
                $("#client-modal .modal-body").html(html);
                $('#client-modal').modal('show');
            },
            error: function (jqXHR, status) {
                $("#client-modal .modal-body").text('Ошибка загрузки данных');
            }
        });
    });


    $(document).on('click', '.btn-add-phone', function(evt) {
        evt.preventDefault();
        $.ajax({
            type: 'GET',
            url: $(this).attr('href'),
            beforeSend: function () {
                $("#client-modal .modal-body").text('Загружаются данные...');
            },
            success: function(html) {
                $("#client-modal .modal-body").html(html);
            },
            error: function (jqXHR, status) {
                $("#client-modal .modal-body").text(status);
            }
        });
    });

JS;

$this->registerJs($script);

?>


<!-- Modal -->
<div class="modal fade" id="star-modal" tabindex="-1" role="dialog" aria-labelledby="myModalStarLabel">
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
    $(document).on('click', '.star-open', function(evt) {
        evt.preventDefault();
        $("#star-modal .modal-body").text('Загружаются данные...');
        $('#star-modal').modal('show');
        
        $.ajax({
            url: $(this).attr("href"),
            method: "GET",
            success: function(html) {
                $("#star-modal .modal-body").html(html);
                $('#star-modal').modal('show');
            },
            error: function (jqXHR, status) {
                $("#star-modal .modal-body").text('Ошибка загрузки данных');
            }
        });
    });


    $(document).on('submit', '#form-star', function(evt) {
        evt.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(html) {
                $('#star-modal').modal('hide');
            },
            error: function (jqXHR, status) {
                $("#star-modal .modal-body").text(status);
            }
        });
    });

JS;

$this->registerJs($script);

?>

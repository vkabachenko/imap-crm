<?php
use yii\bootstrap\Modal;
?>

<?php Modal::begin([
    'id' => 'client-modal',
]); ?>


<?php Modal::end(); ?>


<?php
$script = <<<JS
    $('.open-client').click(function(evt) {
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

JS;

$this->registerJs($script);

?>

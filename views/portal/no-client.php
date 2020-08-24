<?php
/* @var $findBidForm \app\models\FindBidForm */
/* @var $phone string */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
?>

<style>
    .no-client {
        color: red;
        margin-bottom: 20px;
    }
</style>

<div>
    <p class="no-client">Клиент не найден</p>

    <h3>Найти заявку и подставить телефон клиента</h3>

    <?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'id' => 'find-bid-id',
            'action' => ['portal/find-bid', 'phone' => $phone]
        ]);
    ?>

    <div class="row">
        <div class="col-xs-8">
            <?= $form->field($findBidForm, 'bid1Cnumber') ?>
        </div>
        <div class="col-xs-4">
            <?= Html::a('Найти', '#', ['class' => 'btn btn-success btn-find-bid']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS
    $('.btn-find-bid').on('click', function(evt) {
        evt.preventDefault();
        var form = $('#find-bid-id');
        
        $.ajax({
            type: 'POST',
            data: form.serialize(),            
            url: form.attr('action'),
            beforeSend: function () {
                $("#client-modal .modal-body").text('Загружаются данные...');
            },
            success: function(html) {
                $("#client-modal .modal-body").html(html);
            },
            error: function (jqXHR, status) {
                $("#client-modal .modal-body").text('Ошибка загрузки данных');
            }
        });
    });


JS;

$this->registerJs($script);

?>
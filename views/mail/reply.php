<?php /** @noinspection PhpUndefinedFieldInspection */

/* @var $model \app\models\EmailReply */
/* @var $uploadForm \app\models\UploadForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Ответ на письмо';
?>

<div>
    <?php $form = ActiveForm::begin(); ?>

        <?php if (\Yii::$app->user->identity->default_mailbox_id):?>
            <div style="margin: 10px 0;">
                <?= Html::checkbox('mailbox-default', false, ['label' => 'Отправить с ящика по умолчанию']) ?>
            </div>
        <?php endif; ?>

        <?= $form->field($model, 'mailbox_id')->hiddenInput([
                'id' => 'mailbox-id',
                'data-value' => \Yii::$app->user->identity->default_mailbox_id
        ])->label(false);
        ?>
        <?= $form->field($model, 'from')->textInput([
                'id' => 'from',
                'data-value' => \Yii::$app->user->identity->default_mailbox_id
                    ? \Yii::$app->user->identity->defaultMailbox->login
                    : ''
        ]);
        ?>
        <?= $form->field($model, 'to'); ?>
        <?= $form->field($model, 'subject'); ?>
        <?= $form->field($model, 'content')->textarea(['rows' => 10]); ?>
        <?= $form->field($model, 'comment'); ?>

        <?= $form->field($uploadForm, 'files[]')->fileInput(['multiple' => true]); ?>

    <div class="row">

        <div class="form-group  col-md-4">
                <?= Html::submitButton('Отправить',
                    ['class' => 'btn btn-success']) ?>
        </div>

        <div class="form-group  col-md-4">
            <?= Html::a('Черновик',
                Url::to(['mail/reply', 'id' => $model->reply_to_id, 'isDraft' => true]),
                [
                    'class' => 'btn btn-default',
                    'data' => [
                        'method' => 'post'
                    ]
                ]
            ) ?>
        </div>

        <div class="form-group col-md-4">
            <?= Html::a('Отмена',
                Url::to(['mail/release-mail', 'mailId' => $model->reply_to_id]),
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS

    var mailboxIdInit = $('#mailbox-id').val();
    var fromInit = $('#from').val();
    $('[name="mailbox-default"]').change(function() {
        if ($(this).prop('checked')) {
            $('#mailbox-id').val($('#mailbox-id').data('value'));
            $('#from').val($('#from').data('value'));            
        } else {
            $('#mailbox-id').val(mailboxIdInit);
            $('#from').val(fromInit);              
        }
    });
JS;

$this->registerJs($script);
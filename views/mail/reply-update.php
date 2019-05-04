<?php

/* @var $model \app\models\EmailReply */
/* @var $uploadForm \app\models\UploadForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование черновика';
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

        <?php if(!empty($attachmentFileNames)): ?>
            <div id="files-attachment">
                <strong style="margin-right: 10px;">Приложения:</strong>
                <?php foreach ($attachmentFileNames as $fileName): ?>
                    <?= Html::a($fileName,
                        ['mail/download', 'mailId' => $model->id, 'fileName' => $fileName, 'reply' => true],
                        ['style' => 'margin-right: 10px;']);
                    ?>
                <?php endforeach; ?>
            </div>
            <div style="margin: 10px 0;">
                <?= Html::checkbox('allow-upload', false, ['label' => 'Заменить загрузку файлов']) ?>
            </div>
        <?php endif; ?>

        <?= $form->field($uploadForm, 'files[]', ['options' => ['id' => 'id-upload-files']])
            ->fileInput(['multiple' => true]); ?>


    <div class="row" style="margin-top: 10px;">

        <div class="form-group  col-md-4">
            <?= Html::a('Отправить',
                Url::to(['mail/reply-update', 'id' => $model->id, 'isDraft' => false]),
                [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post'
                    ]
                ]
            ) ?>
        </div>

        <div class="form-group  col-md-4">
            <?= Html::submitButton('Черновик',
                ['class' => 'btn btn-default']) ?>
        </div>

        <div class="form-group col-md-4">
            <?= Html::a('Отмена',
                Url::to(['mail-send/index', 'mailboxId' => $model->mailbox_id, 'status' => 'draft']),
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS
    if ($('[name="allow-upload"]').is(':visible')) {
        $('#id-upload-files').hide();
    }
    
    $('[name="allow-upload"]').change(function() {
        var self = $(this);
        if (self.prop('checked')) {
           $('#id-upload-files').show(); 
           $('#files-attachment').hide();
        } else {
           $('#files-attachment').show();
           $('#id-upload-files').hide();
           $('#id-upload-files').val('');
        }
    });

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

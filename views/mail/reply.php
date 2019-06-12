<?php
/** @noinspection PhpUndefinedFieldInspection */
/* @var $model \app\models\EmailReply */
/* @var $uploadForm \app\models\UploadForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $model->reply_to_id ? 'Ответ на письмо' : 'Новое письмо';
?>

<div>
    <?php if ($model->reply_to_id): ?>
        <div>Содержание входящего письма</div>
        <div style="
            font-family: 'Courier New', Courier, monospace;
            margin: 10px;
            padding: 5px;
            max-height: 300px;
            overflow: auto;
            border: 1px solid #999"
        >
            <?= $model->replyTo->content ?>
        </div>
    <?php endif; ?>
    <?php $form = ActiveForm::begin(); ?>

        <?php if (\Yii::$app->user->identity->default_mailbox_id):?>
            <div style="margin: 10px 0;">
                <?= Html::checkbox('mailbox-default', false, [
                        'label' => 'Отправить с ящика по умолчанию',
                        'data-url' => Url::to(['mail/set-sign'])
                ]) ?>
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
        <?= $form->field($model, 'content')->textarea(['rows' => 10, 'id' => 'content']); ?>
        <?= $form->field($model, 'comment'); ?>

        <?= $form->field($uploadForm, 'files[]')->fileInput(['multiple' => true]); ?>

    <div class="row">

        <div class="form-group  col-md-4">
                <?= Html::submitButton('Отправить',
                    ['class' => 'btn btn-success']) ?>
        </div>

        <div class="form-group  col-md-4">
            <?= Html::a('Черновик',
                Url::to($model->reply_to_id
                    ? ['mail/reply', 'id' => $model->reply_to_id, 'isDraft' => true]
                    : ['mail-send/create', 'mailboxId' => $model->mailbox_id, 'isDraft' => true]
                ),
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
                Url::to($model->reply_to_id
                    ? ['mail/release-mail', 'mailId' => $model->reply_to_id]
                    : ['mail-send/index', 'mailboxId' => $model->mailbox_id]
                ),
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
            var mailboxIdPrev = mailboxIdInit;
            $('#mailbox-id').val($('#mailbox-id').data('value'));
            $('#from').val($('#from').data('value'));            
        } else {
            mailboxIdPrev = $('#mailbox-id').data('value');
            $('#mailbox-id').val(mailboxIdInit);
            $('#from').val(fromInit);              
        }
        $.ajax({
            url: $(this).data('url'),
            method: 'POST',
            data: {
                mailboxIdPrev: mailboxIdPrev,
                mailboxId: $('#mailbox-id').val(),
                content: $('#content').val()
            }
        })
        .done(function(result) {
            $('#content').val(result.content)
        })
        .fail(function() {
            console.log('fail')
        });
    });
JS;

$this->registerJs($script);
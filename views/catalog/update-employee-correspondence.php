<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $items \app\models\EmployeeCorrespondence[] */
/* @var $users array */
?>

<?php if (\Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <?= \Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<div>
    <?php $form = ActiveForm::begin(); ?>

    <table class="table table-striped">
        <tr>
            <th>Пользователь 1С</th>
            <th>Пользователь црм</th>
        </tr>

        <?php foreach($items as $index => $item): ?>
            <tr>
                <td>
                    <?= $form
                        ->field($item,"[$index]user_imported")
                        ->textInput(['readonly' => true])
                        ->label(false)
                    ?>
                </td>
                <td>
                    <?= $form
                        ->field($item,"[$index]employee_id")
                        ->dropDownList($users, ['prompt' => ''])
                        ->label(false)
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>


    <?php ActiveForm::end(); ?>
</div>

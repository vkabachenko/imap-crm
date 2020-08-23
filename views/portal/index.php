<?php

/* @var $response array */
 ?>

<style>
    .client-header {
        font-weight: bold;;
    }
</style>

<div>
    <div class="row">
        <div class="col-xs-6 col-sm-3 client-header">
            Клиент
        </div>
        <div class="col-xs-6 col-sm-9 client-content">
            <?= $response['name'] ?>
        </div>
    </div>

    <?php if ($response['full_name'] !=  $response['name']): ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3 client-header">
                Полное наименование
            </div>
            <div class="col-xs-6 col-sm-9 client-content">
                <?= $response['full_name'] ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($response['date_register']): ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3 client-header">
                Дата регистрации
            </div>
            <div class="col-xs-6 col-sm-9 client-content">
                <?= $response['date_register'] ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($response['email']): ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3 client-header">
                Email
            </div>
            <div class="col-xs-6 col-sm-9 client-content">
                <?= $response['email'] ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($response['address_actual']): ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3 client-header">
                Фактический адрес
            </div>
            <div class="col-xs-6 col-sm-9 client-content">
                <?= $response['address_actual'] ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($response['address_legal']): ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3 client-header">
                Юридический адрес
            </div>
            <div class="col-xs-6 col-sm-9 client-content">
                <?= $response['address_legal'] ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($response['description']): ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3 client-header">
                Дополнительная информация
            </div>
            <div class="col-xs-6 col-sm-9 client-content">
                <?= $response['description'] ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($response['comment']): ?>
        <div class="row">
            <div class="col-xs-6 col-sm-3 client-header">
                Комментарий
            </div>
            <div class="col-xs-6 col-sm-9 client-content">
                <?= $response['comment'] ?>
            </div>
        </div>
    <?php endif; ?>

</div>

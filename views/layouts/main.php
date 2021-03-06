<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
AppAsset::register($this);

$thisRule = explode(",",Yii::$app->user->identity->rule);
?><?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>" class="no-js">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed-hide-logo">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<div class="menu-toggler sidebar-toggler">
			</div>
		</div>

                <div class="page-actions">
                        <button type="button" class="btn red-haze btn-sm NewClientBtn" href="#modalCall" data-toggle="modal">
                            <span class="hidden-sm hidden-xs">Добавить клиента/заказ</span>
                            <i class="fa fa-plus"></i>
                        </button>

							<div class="modal fade draggable-modal" id="modalCall" tabindex="-1" role="basic" aria-hidden="true" style="color:#000;">
								<div class="modal-dialog" style=" width:70%;">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
										</div>
										<div class="modal-body">
<div class="row">
<div class="col-md-18">
<div class="note note-success">
                                         <p id="thistelinc"></p>
</div>
</div>
<form method="post" action="<?php echo Url::toRoute(['site/users', 'add'=>'true']); ?>" id="NewClientForm">

<div class="post-comment col-md-6">
<h2 class="modal-title" id="NewClientTitle">Новый клиент</h2>

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="form-group">
<?= Html::label('Телефон','tel') ?>
<?= Html::textInput('tel','', ['class' => 'form-control tel', 'id' => 'NewClientTel']) ?>
</div>
<div class="form-group">
<?= Html::label('Имя','name') ?>
<?= Html::textInput('name','', ['class' => 'form-control', 'id' => 'NewClientName']) ?>
</div>
<div class="form-group">
<?= Html::label('Email','email') ?>
<?= Html::textInput('email','', ['class' => 'form-control', 'id' => 'NewClientEmail']) ?>
</div>
<div class="form-group">
<?= Html::label('Дополнительная информация','text') ?>
<?= Html::textarea('text','', ['class' => 'form-control', 'id' => 'NewClientText']) ?>
</div>

<div class="form-group">
<?= Html::label('Статус:','status') ?> <br />
<?php
$rows = (new \yii\db\Query())->select(['*'])->from('users_status')->all();
$arr = array(); for($i=0;$i<sizeof($rows);$i++){  $arr[$rows[$i]['id']]=$rows[$i]['name'];  }
echo Html::dropDownList('status',0,$arr, ['class' => 'form-control']) ;
?>
</div>

<?= Html::submitButton('Сохранить', ['class' => 'btn blue btn-block', 'name' => 'save']) ?>


</div>

<div class="post-comment col-md-6">
<h2 class="modal-title">Новый заказ</h2>
<div class="form-group">
<?= Html::label('Ответственный:','order_employe_id') ?> <br />
<?php
$rows = (new \yii\db\Query())->select(['*'])->from('employees')->all();
$arr = array(); for($i=0;$i<sizeof($rows);$i++){  $arr[$rows[$i]['id']]=$rows[$i]['name'];  }
echo Html::dropDownList('order_employe_id',Yii::$app->user->id,$arr, ['class' => 'form-control']) ;
?>
</div>

<div class="form-group">
<?= Html::label('Название','order_name') ?>
<?= Html::textInput('order_name',$edit['name'], ['class' => 'form-control']) ?>
</div>


<div class="form-group">
<?= Html::label('Номер заказа','name') ?>
<?= Html::textInput('order_num',$edit['order_num'], ['class' => 'form-control']) ?>
</div>

<div class="form-group">
<?= Html::label('Описание заказа','order_text') ?>
<?= Html::textarea('order_text',$edit['order_text'], ['class' => 'form-control', 'rows' => '5']) ?>
</div>

    <div class="form-group">
<?= Html::label('Статус:','order_status') ?> <br />
<?php
$rows = (new \yii\db\Query())->select(['*'])->from('order_status')->all();
$arr = array(); for($i=0;$i<sizeof($rows);$i++){  $arr[$rows[$i]['id']]=$rows[$i]['name'];  }
echo Html::dropDownList('order_status',0,$arr, ['class' => 'form-control']) ;
?>
</div>

<div class="form-group">
<?= Html::label('Бренд:','order_brand') ?> <br />
<?php
$rows = (new \yii\db\Query())->select(['*'])->from('brand')->all();
$arr = array(); for($i=0;$i<sizeof($rows);$i++){  $arr[$rows[$i]['id']]=$rows[$i]['name'];  }
echo Html::dropDownList('order_brand',0,$arr, ['class' => 'form-control']) ;
?>
</div>
</div>

</div>      </form>

										</div>
										<div class="modal-footer">
											<button type="button" class="btn default" data-dismiss="modal">Закрыть</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
                </div>

        <div class="page-notifications">

        </div>


		<div class="page-top">
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
                            <li class="separator hide"> </li>

					<li class="dropdown dropdown-user dropdown-dark">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<span class="username username-hide-on-mobile">
						<?php echo Yii::$app->user->identity->name; ?></span>
						<!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
						<?php if(isset($_SESSION["user"]) && file_exists(Yii::$app->homeUrl.'img/user'.$_SESSION["user"].'.jpg')){ ?>
                        <img alt="" class="img-circle" src="<?php echo Yii::$app->homeUrl; ?>img/user<?php echo $_SESSION["user"]; ?>.jpg"/>
						<?php }else { ?>
						<img alt="" class="img-circle" src="<?php echo Yii::$app->homeUrl; ?>assets/admin/layout4/img/avatar9.jpg"/>
						<?php } ?>
						</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li>
								<a href="<?php echo Url::toRoute(['site/logout']); ?>">
								<i class="icon-key"></i> Выйти </a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

	</div>
</div>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
<?php $this->beginBody() ?>
<!--
<a href="<?php echo Url::toRoute(['site/us']); ?>"><?php echo Url::toRoute(['site/us']); ?></a>  <br />
<a href="<?php echo Url::toRoute(['site/users']); ?>"><?php echo Url::toRoute(['site/users']); ?></a>
!-->
<div class="wrap">
	<div class="page-sidebar-wrapper">

		<div class="page-sidebar navbar-collapse collapse">

			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li class="<?php if(Url::to()==Url::toRoute(['site/index'])){echo 'active';} ?>">
					<a href="<?php echo Url::toRoute(['site/index']); ?>">
					<i class="icon-home"></i>
					<span class="title">Телефония</span>
					</a>
				</li>

				<li class="<?php if(Url::to()==Url::toRoute(['mail/index'])){echo 'active';} ?>">
					<a href="<?php echo Url::toRoute(['mail/index']); ?>">
					<i class="icon-list"></i>
					<span class="title">Почта</span>
					</a>
				</li>

				<li class="<?php if(Url::to()==Url::toRoute(['site/orders'])){echo 'active';} ?>">
					<a href="<?php echo Url::toRoute(['site/orders']); ?>">
					<i class="icon-notebook"></i>
					<span class="title">Заказы</span>
					</a>
				</li>

				<li class="<?php if(Url::to()==Url::toRoute(['site/users'])){echo 'active';} ?>">
					<a href="<?php echo Url::toRoute(['site/users']); ?>">
					<i class="icon-users"></i>
					<span class="title">Клиенты</span>
					</a>
				</li>

<?php  if(Yii::$app->user->identity->is_admin){  ?>
				<li class="<?php if(Url::to()==Url::toRoute(['site/employees'])){echo 'active';} ?>">
					<a href="<?php echo Url::toRoute(['site/employees']); ?>">
					<i class="icon-user"></i>
					<span class="title">Сотрудники</span>
					</a>
				</li>



				<li class="<?php if(Url::to()==Url::toRoute(['site/sip']) or Url::to()==Url::toRoute(['site/source']) or Url::to()==Url::toRoute(['site/order_status']) or Url::to()==Url::toRoute(['site/users_status']) or Url::to()==Url::toRoute(['site/brand']) or Url::to()==Url::toRoute(['site/mails'])){echo 'active';} ?>">
					<a href="<?php echo Url::toRoute(['site/order_status']); ?>">
					<i class="icon-list"></i>
					<span class="title">Справочники</span>
					<span class="arrow "></span>
					</a>
					<ul class="sub-menu">
						<li<?php if(Url::to()==Url::toRoute(['site/order_status'])){echo ' class="active"';} ?>>
							<a href="<?php echo Url::toRoute(['site/order_status']); ?>">
							<i class="icon-list"></i>
							Статусы заказа</a>
						</li>
						<li<?php if(Url::to()==Url::toRoute(['site/users_status'])){echo ' class="active"';} ?>>
							<a href="<?php echo Url::toRoute(['site/users_status']); ?>">
							<i class="icon-list"></i>
							Статусы клиента</a>
						</li>
						<li<?php if(Url::to()==Url::toRoute(['site/brand'])){echo ' class="active"';} ?>>
							<a href="<?php echo Url::toRoute(['site/brand']); ?>">
							<i class="icon-list"></i>
							Бренды</a>
						</li>
						<li<?php if(Url::to()==Url::toRoute(['mail-status/index'])){echo ' class="active"';} ?>>
							<a href="<?php echo Url::toRoute(['mail-status/index']); ?>">
							<i class="icon-list"></i>
							Общие статусы писем</a>
						</li>
						<li<?php if(Url::to()==Url::toRoute(['site/source'])){echo ' class="active"';} ?>>
							<a href="<?php echo Url::toRoute(['site/source']); ?>">
							<i class="icon-list"></i>
							Источники</a>
						</li>
						<li<?php if(Url::to()==Url::toRoute(['site/sip'])){echo ' class="active"';} ?>>
							<a href="<?php echo Url::toRoute(['site/sip']); ?>">
							<i class="icon-list"></i>
							Сипы</a>
						</li>
                        <li<?php if(Url::to()==Url::toRoute(['catalog/employee-correspondence'])){echo ' class="active"';} ?>>
                            <a href="<?php echo Url::toRoute(['catalog/employee-correspondence']); ?>">
                                <i class="icon-list"></i>
                                Соответствие пользователей с 1С</a>
                        </li>
					</ul>
				</li>

<?php } ?>

    <li class="<?php if(Url::to()==Url::toRoute(['report/adv'])){echo 'active';} ?>">
        <a href="<?php echo Url::toRoute(['report/adv']); ?>">
            <i class="icon-list"></i>
            <span class="title">Отчеты</span>
            <span class="arrow "></span>
        </a>
        <ul class="sub-menu">
            <li<?php if(Url::to()==Url::toRoute(['report/adv'])){echo ' class="active"';} ?>>
                <a href="<?php echo Url::toRoute(['report/adv']); ?>">
                    <i class="icon-list"></i>
                    Рекламные кампании</a>
            </li>
            <li<?php if(Url::to()==Url::toRoute(['report/stat'])){echo ' class="active"';} ?>>
                <a href="<?php echo Url::toRoute(['report/stat']); ?>">
                    <i class="icon-list"></i>
                    Статистика менеджеров</a>
            </li>
        </ul>

    </li>


			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
    <?php
 /*
//if(!Yii::$app->user->isGuest){
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Еще', 'url' => ['/site/about']],
            ['label' => 'Контакты', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Войти', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
//}else {
//echo Nav::isActive(['url' => ['/site/login']]);
//print_r($this->params['breadcrumbs']);
//echo $this->params['breadcrumbs'];
if($this->params['breadcrumbs'][0]!='Login'){
	//Yii::$app->response->redirect(Url::to(['../site/login']), 301)->send();
	}
	//}
	*/




    ?>

	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
                <div class="notification-summary-wrap">
                    <div>
                        <span class="notification-summary-label-main">Звонков:</span>
                        <span class="notification-summary-calls notification-summary-text notification-summary">0</span>
                        <span class="notification-summary-label">Неотв:</span>
                        <span class="notification-summary-calls-lost notification-summary-text notification-summary">0</span>
                        <span class="notification-summary-label">Принято:</span>
                        <span class="notification-summary-calls-in notification-summary-text notification-summary">0</span>
                        <span class="notification-summary-label notification-summary">Вами:</span>
                        <span class="notification-summary-calls-in-user notification-summary-text notification-summary">0</span>
                        <span class="notification-summary-label-main">Писем:</span>
                        <span class="notification-summary-mails notification-summary-text notification-summary">0</span>
                        <span class="notification-summary-label">Неотв:</span>
                        <span class="notification-summary-mails-lost notification-summary-text notification-summary">0</span>
                        <span class="notification-summary-label">Принято:</span>
                        <span class="notification-summary-mails-in notification-summary-text notification-summary">0</span>
                        <span class="notification-summary-label notification-summary">Вами:</span>
                        <span class="notification-summary-mails-in-user notification-summary-text notification-summary">0</span>
                    </div>
                </div>
				<!-- BEGIN PAGE TITLE -->
				<div class="page-title">
					<h1><?php  echo $this->title; ?>
                        <?php  echo isset($this->params['titleBut']) ? $this->params['titleBut'] : ''; ?> </h1>
				</div>
				<!-- END PAGE TITLE -->
				<!-- BEGIN PAGE TOOLBAR -->
				<div class="page-toolbar">

				</div>
				<!-- END PAGE TOOLBAR -->
			</div>
			<!-- END PAGE HEAD -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="portlet light">
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-12">
        <?= $content ?>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
</div>
<div class="page-footer">
	<div class="page-footer-inner">
		 <?php echo date("2005-Y") ?> &copy; CRM.
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>

<?php $this->endBody() ?>

<script>

function checksummary() {
    $.ajax({
        url: '<?= Url::to(['summary/check']); ?>',
        method: "GET",
        dataType: "json",
        success: function (data) {
            $('.notification-summary-wrap .notification-summary-calls').text(data.calls ? data.calls : 0);
            $('.notification-summary-wrap .notification-summary-calls-lost').text(data.callsLost ? data.callsLost : 0);
            $('.notification-summary-wrap .notification-summary-calls-in').text(data.callsIn ? data.callsIn : 0);
            $('.notification-summary-wrap .notification-summary-calls-in-user').text(data.callsInUser ? data.callsInUser : 0);
            $('.notification-summary-wrap .notification-summary-mails').text(data.mails ? data.mails : 0);
            $('.notification-summary-wrap .notification-summary-mails-lost').text(data.mailsLost ? data.mailsLost : 0);
            $('.notification-summary-wrap .notification-summary-mails-in').text(data.mailsIn ? data.mailsIn : 0);
            $('.notification-summary-wrap .notification-summary-mails-in-user').text(data.mailsInUser ? data.mailsInUser : 0);

            setTimeout(checksummary, 5 * 1000 * 60);
        },
        error: function (jqXHR, status) {
            console.log(status);
        }
    });
}


function chekcalls(){
    var urlClient = '<?= Url::to(['portal/index', 'phone' => '']); ?>'
    $.ajax({
        url: '<?= Url::to(['site/getlastcalls']); ?>',
        method: "GET",
        dataType: "json",
        success: function(data) {

            var sids = data.map(function (obj) {
                return 'sid' + obj.sid;
            });

            $('.page-notifications .notification-wrap').each(function() {
                if(sids.indexOf($(this).attr('id')) === -1) {
                    $(this).remove();
                }
            });

            $.each(data, function(index, obj) {
                var status = 'notification-default';
                var statusMsg = 'Не определен';
                switch (obj.status) {
                    case 'start':
                        status = 'notification-start';
                        statusMsg = 'Ожидает';
                        break;
                    case 'up':
                        status = 'notification-up';
                        statusMsg = 'Разговор с ' + obj.sip;
                        break;
                    case 'outgoing':
                        status = 'notification-outgoing';
                        statusMsg = 'Исходящий ' + obj.sip;
                        break;
                    case 'finish':
                        status = 'notification-finish';
                        statusMsg = 'Завершен';
                        break;
                }
                var el1 = $('.page-notifications').find('#sid' + obj.sid);

                if (el1.length > 0) {
                    el1.find('.notification-status').text(statusMsg)
                } else {
                    var el = $('<div>').addClass('notification-wrap').addClass(status).attr('id', 'sid' + obj.sid);
                    $('<span>').addClass('notification-time').text(obj.date.substr(-8)).appendTo(el);
                    $('<span>').addClass('notification-text').text('  Звонок от  ').appendTo(el);
                    $('<span>').addClass('notification-phone').text(obj.tel_from.substr(0, 22)).appendTo(el);
                    var clientClass = obj.client ? 'green' : 'yellow';
                    $('<a>')
                        .attr('href', urlClient + obj.tel_from.substr(0, 22))
                        .addClass('btn btn-xs open-client ' + clientClass)
                        .html('<i class="fa fa-male" aria-hidden="true"></i>')
                        .appendTo(el);
                    $('<span>').addClass('notification-text').text('  на  ').appendTo(el);
                    $('<span>').addClass('notification-phone').text(obj.tel_to.substr(0, 22)).appendTo(el);
                    $('<span>').addClass('notification-text').text('  Статус  ').appendTo(el);
                    $('<span>').addClass('notification-status').text(statusMsg).appendTo(el);
                    el.appendTo('.page-notifications');
                }
            });
            setTimeout(chekcalls, 2000);
        },
        error: function (jqXHR, status) {
            console.log(status);
        }
    })
	}
jQuery(document).ready(function() {

$( ".NewClientBtn" ).click(function() {

$.get( "<?php echo Url::toRoute(['site/getlastcalls']); ?>", function( data ) {
var thisStr='';
	$.each( data, function( k ) {
		if(data[k].type==0){
		if(thisStr){thisStr += ", ";}
		thisStr += "<a href=\"#p\" onclick=\"$('#NewClientTel').val('"+data[k].tel_from+"');\">"+data[k].tel_from+"</a>";
		}
	});
	if(thisStr){thisStr='Звонки от: '+thisStr;}
	$('#thistelinc').html(thisStr);
	if(!thisStr){$('#thistelinc').html('Сейчас нет звонков'); }
}, "json");

});

$( ".btnAddNewTotel" ).click(function() {
	var thisTel = $(this).attr('tel');
	$('#NewClientTel').val(thisTel);
  $( ".NewClientBtn" ).trigger( "click" );

$.get( "<?php echo Url::toRoute(['site/userinfo', 'tel'=>'']); ?>"+thisTel, function( data ) {
if(data.id){
	$("#NewClientForm").attr('action','<?php echo Url::toRoute(['site/users', 'eid'=>'']); ?>'+data.id);
    $('#NewClientName').val(data.name);
    $('#NewClientEmail').val(data.email);
    $('#NewClientText').val(data.text);
    $('#NewClientTitle').text('Старый клиент');
 	}
var calls = data.calls;
var callsStr='';
$.each( calls, function( k ) {
   callsStr += calls[k].tel_from+" - "+calls[k].datetime+"<br />";
	});
if(callsStr){$('.note-info').remove(); $('#NewClientForm').before('<div class="note note-info"><p>'+callsStr+'</p> </div>');  }


}, "json");


});

$('.tel').mask('+9 (999) 999-99-99');
Layout.init();


chekcalls();
checksummary();

            $('.black').click(function(){
            var hrefthis = $(this).attr('href');
                bootbox.confirm("Вы действительно хотите удалить элемент?", function(result) {
                if(result){
                window.location.href = hrefthis;
                }
                });
               return false;
            });

ComponentsPickers.init();

});
</script>

    <?= $this->render('modal/open-client') ?>

</body>
</html>
<?php $this->endPage() ?>

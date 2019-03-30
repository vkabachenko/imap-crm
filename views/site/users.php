<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;
use yii\widgets\LinkPager;
$thisPageId='users';
$thisDbTable='users';
$thisPagelmit=20;
$this->title = 'Клиенты';
$thisRule = explode(",",Yii::$app->user->identity->rule);
if(Yii::$app->user->id==1 or in_array(3.1,$thisRule)){
$this->params['titleBut'] = '<a href="'.Url::toRoute([$thisPageId, 'add' => 'true']).'" class="btn btn-xs green">Добавить <i class="fa fa-plus"></i></a>';
}



//print_r($this);
if(Yii::$app->request->get('add')){


?><div class="post-comment col-md-6">
<form method="post">

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="form-group">
<?= Html::label('Телефон','tel') ?>
<?= Html::textInput('tel','', ['class' => 'form-control tel']) ?>
</div>
<div class="form-group">
<?= Html::label('Имя','name') ?>
<?= Html::textInput('name','', ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Email','email') ?>
<?= Html::textInput('email','', ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Дополнительная информация','text') ?>
<?= Html::textarea('text','', ['class' => 'form-control']) ?>
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

</form>
</div>

<?php
   }elseif(Yii::$app->request->get('eid')){

$edit = $model->GetRow();
?>
<div class="post-comment col-md-6">
<form method="post">

<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="form-group">
<?= Html::label('Телефон','tel') ?>
<?= Html::textInput('tel',$edit['tel'], ['class' => 'form-control tel']) ?>
</div>
<div class="form-group">
<?= Html::label('Имя','name') ?>
<?= Html::textInput('name',$edit['name'], ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Email','email') ?>
<?= Html::textInput('email',$edit['email'], ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Задание','text') ?>
<?= Html::textarea('text',$edit['text'], ['class' => 'form-control']) ?>
</div>
<div class="form-group">
<?= Html::label('Статус:','status') ?> <br />
<?php
$rows = (new \yii\db\Query())->select(['*'])->from('users_status')->all();
$arr = array(); for($i=0;$i<sizeof($rows);$i++){  $arr[$rows[$i]['id']]=$rows[$i]['name'];  }
echo Html::dropDownList('status',$edit['status'],$arr, ['class' => 'form-control']) ;
?>
</div>
<?= Html::submitButton('Сохранить', ['class' => 'btn blue btn-block', 'name' => 'save']) ?>

</form>
</div>
<?php
   }else {
$GetRows = $model->GetRows();
$pages=$GetRows['p'];
$rows=$GetRows['rows'];


?>
<div class="col-md-12">
<form method="post" class="form-inline">
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
											<div class="input-group date date-picker" data-date="">
												<input type="text" name="sort_date1" placeholder="Дата добавления от" value="<?php  echo Yii::$app->request->post('sort_date1'); ?>" size="16" class="form-control input-small">
												<span class="input-group-btn">
												<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>


<input type="text" name="sort_name" class="form-control input-small" placeholder="Имя" value="<?php  echo Yii::$app->request->post('sort_name'); ?>">


<input type="text" name="sort_tel" class="form-control input-small tel" placeholder="Телефон" value="<?php  echo Yii::$app->request->post('sort_tel'); ?>">

<input type="submit" name="save" class="btn blue" value="Показать" />
</form><br /><br />
</div>

  <table class="table table-bordered" id="sample_6">
<thead>
<tr>
<th><a href="<?php $sort2='DESC';  if(Yii::$app->request->get('sort')=='id' and Yii::$app->request->get('sort2')=='DESC'){$sort2='ASC';} Echo Url::Toroute([$thisPageId, 'sort' => 'id', 'sort2' => $sort2]); ?>">#</a></th>
<th><a href="<?php $sort2='DESC';  if(Yii::$app->request->get('sort')=='name' and Yii::$app->request->get('sort2')=='DESC'){$sort2='ASC';} Echo Url::Toroute([$thisPageId, 'sort' => 'name', 'sort2' => $sort2]); ?>">Имя</a></th>
<th>Телефон</th>
<th>Email</th>
<th>Карточка клиента</th>
<th>-</th>
</tr>
</thead>
<tbody>
<?php
foreach ($rows as $value) {
?>
<tr>
<td><?php  echo $value['id'];  ?></td>
<td><?php  echo $value['name'];  ?></td>
<td><?php  echo $value['tel'];  ?></td>
<td><?php  echo $value['email'];  ?></td>
<td><a data-toggle="modal" href="#modal<?php  echo $value['id'];  ?>" v="<?php  echo $value['id'];  ?>" class="btn btn-xs yellow updateModal"> <i class="fa fa-file-o"></i> Открыть</a>
							<div class="modal fade draggable-modal" id="modal<?php  echo $value['id'];  ?>" tabindex="-1" role="basic" aria-hidden="true" style="color:#000;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h2 class="modal-title"><?php  echo $Client['name'];  ?></h2>
										</div>
										<div class="modal-body">
<div class="row">
<div class="post-comment col-md-12" id="mod<?php  echo $value['id'];  ?>">
</div>
<div class=" col-md-12">
<form class="form-inline FormsendPost" v="<?php  echo $value['id'];  ?>">
  <div class="form-group">
    <input type="text" class="form-control sendPost"  placeholder="Добавить заметку">
  </div>
  <button type="submit" class="btn btn-default blue">Сохранить</button>
</form>
</div>
</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn default" data-dismiss="modal">Закрыть</button>
										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
</td>
<td>
<?php  if(Yii::$app->user->id==1 or in_array(3.2,$thisRule)){  ?><a href="<?php Echo Url::Toroute([$thisPageId, 'eid' => $value['id']]); ?>" class="btn default btn-xs purple"><i class="fa fa-edit"></i> Редактировать</a><?php } ?>
<?php  if(Yii::$app->user->id==1 or in_array(3.3,$thisRule)){  ?><a href="<?php Echo Url::Toroute([$thisPageId, 'did' => $value['id']]); ?>" class="btn default btn-xs black"><i class="fa fa-trash-o"></i> Удалить</a><?php } ?>
</td>
</tr>

<?php
}
?>
</tbody>
</table>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
<?php
   }
$this->registerJs('
function timeConverter(UNIX_timestamp){
  var a = new Date(UNIX_timestamp * 1000);
  var year = a.getFullYear();
  var month = a.getMonth();
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var sec = a.getSeconds();
  var time = date + "." + month + "." + year;
  return time;
}
$( ".updateModal" ).click(function(  ) {
var thisId = $(this).attr("v");
$.get( "'.Url::Toroute(['gethistory']).'?uid="+thisId, function( data ) {

var thisStr= " <table class=\"table table-bordered\"><thead><tr><th>#</th><th>Действие</th><th>Дата</th><th>Содержание</th></tr></thead><tbody>";
var thisStatus="";var thisDate="";
$.each( data, function( k ) {
if(data[k].type==0){ thisStatus="Заметка";}
if(data[k].type==1){ thisStatus="Звонок";}
if(data[k].date==0){ thisDate="Без даты";}else {thisDate=timeConverter(data[k].date);}

thisStr += "<tr><td>"+data[k].id+"</td><td>"+thisStatus+"</td><td>"+thisDate+"</td><td>"+data[k].text+"</td></tr>"
});
thisStr += "</tbody></table>"
$("#mod"+thisId).html(thisStr);

}, "json");

});

$( ".FormsendPost" ).submit(function(  ) {
var thisVal = $(this).find(".sendPost").val();
var thisId = $(this).attr("v");
$(this).find(".sendPost").val("");
$.get( "'.Url::Toroute(['gethistory']).'?uid="+thisId+"&newpost="+thisVal, function( data ) {

var thisStr= " <table class=\"table table-bordered\"><thead><tr><th>#</th><th>Действие</th><th>Дата</th><th>Содержание</th></tr></thead><tbody>";
var thisStatus="";var thisDate="";
$.each( data, function( k ) {
if(data[k].type==0){ thisStatus="Заметка";}
if(data[k].type==1){ thisStatus="Звонок";}
if(data[k].date==0){ thisDate="Без даты";}else {thisDate=timeConverter(data[k].date);}

thisStr += "<tr><td>"+data[k].id+"</td><td>"+thisStatus+"</td><td>"+thisDate+"</td><td>"+data[k].text+"</td></tr>"
});
thisStr += "</tbody></table>"
$("#mod"+thisId).html(thisStr);

}, "json");

return false;
});


');
?>
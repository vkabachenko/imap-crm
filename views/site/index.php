<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;

use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$call_status = Yii::$app->request->get('call_status');
$this->title = 'Последние звонки';
if($call_status==3){$this->title = 'Пропущены';}
if($call_status==1){$this->title = 'Входящие';}
if($call_status==2){$this->title = 'Исходящие';}
if($call_status==4){$this->title = 'Обработанные';}
if($call_status==5){$this->title = 'Ждут обработки';}

$h = ' <a href="'.url::toRoute(['index', 'call_status' => '0']).'" class="btn btn-xs green '; if($call_status){$h .= 'bold';} $h .='">Все звонки</a>
<a href="'.url::toRoute(['index', 'call_status' => '3']).'" class="btn btn-xs red '; if($call_status==3){$h .=  'bold';} $h .='">Пропущены</a>
<a href="'.url::toRoute(['index', 'call_status' => '1']).'" class="btn btn-xs blue '; if($call_status==1){$h .=  'bold';} $h .='"><i class="fa fa-arrow-right"></i> Входящие</a>
<a href="'.url::toRoute(['index', 'call_status' => '2']).'" class="btn btn-xs default '; if($call_status==2){$h .=  'bold';} $h .='"><i class="fa fa-arrow-left"></i> Исходящие</a>

<a href="'.url::toRoute(['index', 'call_status' => '4']).'" class="btn btn-xs default '; if($call_status==4){$h .=  'bold';} $h .='"><i class="fa fa-check"></i> Обработанные</a>
<a href="'.url::toRoute(['index', 'call_status' => '5']).'" class="btn btn-xs default '; if($call_status==5){$h .=  'bold';} $h .='"><i class="fa fa-times"></i> Ждут обработки</a>

<a href="'.url::toRoute(['index', 'excel' => '2']).'" class="btn btn-xs red submit">Экспорт <i class="fa fa-file-excel-o"></i></a>
';
$this->params['titleBut']=$h;

$thisRule = explode(",",Yii::$app->user->identity->rule);




?>

			<div class="portlet light">
				<div class="portlet-body">
					<div class="row">
						<div class="col-md-12">

<form method="post" class="form-inline">
<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
											<div class="input-group date date-picker" data-date="">
												<input type="text" name="sort_date1" placeholder="Дата от" value="<?php  echo Yii::$app->request->post('sort_date1'); ?>" size="16" class="form-control input-small">
												<span class="input-group-btn">
												<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>

											<div class="input-group date date-picker" data-date="">
												<input type="text" name="sort_date2" placeholder="Дата до" value="<?php  echo Yii::$app->request->post('sort_date2'); ?>" size="16" class="form-control input-small">
												<span class="input-group-btn">
												<button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>


<input type="text" name="sort_tel" class="form-control input-small tel" placeholder="Телефон" value="<?php  echo Yii::$app->request->post('sort_tel'); ?>">

<input type="submit" name="save" class="btn blue" value="Показать" />
</form><br /><br />

<div style="height:500px; overflow-y:auto;">
  <table class="table table-bordered table-fixed" id="sample_6">
<thead>
<tr>
<th><small>Клиент</small></th>
<th><small>Источник</small></th>
<th style="width:80px;"><small>Длит.</small></th>
<th style="width:100px;"><small>Сип</small></th>
<th style="width:130px;"><small>Дата и время</small></th>
<th style="width:15px;"><small><i class="fa fa-male"></i></small></th>
<th style="width:15px;"><small><i class="fa fa-check"></i></small></th>
<th style="width:15px;"><small><i class="fa fa-plus"></i></small></th>
<th style="width:15px;"><small><i class="fa fa-play"></i></small></th>
</tr>
</thead>
<tbody>
<?php


$GetRows = $model->GetRows();
$pages=$GetRows['p'];
$calls=$GetRows['calls'];

foreach ($calls as $v) {

$user = $model->GetUser($v['tel_from']);;

$sorce = $model->Getsorce($v['tel_to']);

$sip = $model->Getsip($v['sip']);
?>
<tr>
<td>
<?php  if($v['type']==0){  ?>
<a href="#modal" tel="<?php  echo $v['tel_from'];  ?>" class="btn btn-xs blue"> Вход</a>
<?php }else { ?>
<a href="#modal" tel="<?php  echo $v['tel_from'];  ?>" class="btn btn-xs default"> Исход</i></a>
<?php } ?>
(<?php echo $v['tel_from'] ?>) <br /><b>  <?php echo $user[0]['name']; ?></b></td>
<td> (<?php echo $v['tel_to'] ?>) <br /> <b><small><?php echo $sorce[0]['name']; ?></small></b></td>
<td><?php echo $v['time']; ?></td>
<td><?php echo $v['sip']; ?><br /><b><small><?php echo $sip[0]['name']; ?></small></td>

<td><?php echo date("d.m.Y H:i",$v['date']); ?></td>

<td>
        <?= Html::a('<i class="fa fa-male" aria-hidden="true"></i>',
            ['portal/index', 'phone' => $v['tel_from']],
            ['class' => 'btn btn-xs open-client ' . ($pagePhones[$v['tel_from']] ? 'green' : 'yellow')]
        ) ?>
</td>

<td>
<?php  if($v['status']==0){  ?>
<a href="<?php echo Url::toRoute(['index', 'up_status3' => $v['id'], 'call_status' => Yii::$app->request->get('call_status')])?>" class="btn btn-xs red" ><i class="fa fa-check" aria-hidden="true"></i></a>
<?php }else { ?>
<a href="<?php echo Url::toRoute(['index', 'up_status2' => $v['id'], 'call_status' => Yii::$app->request->get('call_status')])?>" class="btn btn-xs green" ><i class="fa fa-check" aria-hidden="true"></i></a>
<?php } ?>
</td>
<td><a href="#modal" tel="<?php  echo $v['tel_from'];  ?>" class="btn btn-xs red btnAddNewTotel"> <i class="fa fa-plus"></i></a>



							<div class="modal fade draggable-modal" id="modal<?php  echo $v['id'];  ?>" tabindex="-1" role="basic" aria-hidden="true" style="color:#000;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h2 class="modal-title"><?php  echo $v['tel_from'];  ?>-<?php  echo $v['tel_to'];  ?></h2>
										</div>
										<div class="modal-body">
<audio controls>
  <source src="<?php echo $v['file'] ?>" type="audio/mpeg">
Your browser does not support the audio element.
</audio>
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
 <td><?php if($v['file']){  ?><a data-toggle="modal" href="#modal<?php  echo $v['id'];  ?>" v="<?php  echo $v['id'];  ?>" class="btn btn-xs yellow updateModal"> <i class="fa fa-play"></i></a><?php }?></td>

</tr>
<?php
}
?>
</tbody>
</table>  </div>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>

			</div></div></div></div>



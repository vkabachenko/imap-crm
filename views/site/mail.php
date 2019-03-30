<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\db\Command;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Почта';

if(Yii::$app->request->get('e_id')){

$connection = Yii::$app->db;
$connection->createCommand()->insert('email_id', [
    'e_id' => Yii::$app->request->get('e_id')
])->execute();

Yii::$app->response->redirect(Url::to(['mail']), 301)->send();

}


?>
<?php
$mailboxes = (new \yii\db\Query())
    ->select(['*'])
    ->from('mails')
    ->all();
?>
	<? if (!count($mailboxes)) { ?>
		<p>Нет почтовых ящиков</p>
	<? } else {

//print_r($mailboxes);
		foreach ($mailboxes as $current_mailbox) {
//$mailbox = new yii\helpers\Mailbox('{imap.mail.ru:993/imap/ssl}INBOX', 'sale@smt-service.ru', '10fs9uka0+', __DIR__);

$mailbox = new yii\helpers\Mailbox($current_mailbox['server'], $current_mailbox['login'], $current_mailbox['pwd'], Yii::$app->basePath.'/web/mailfile/');
// Read all messaged into an array:
$mailsIds = $mailbox->searchMailbox('ALL');

// Get the first message and save its attachment(s) to disk:


//var_dump($mail);
echo "\n\n\n\n\n";
//var_dump($mail->getAttachments());

//exit;

			?>
<h2><?=$current_mailbox['label']?></h2>
  <table class="table table-bordered">
<thead>
<tr>
<th>Обработано</th>
<th>Тема</th>
<th>От кого</th>
<th>Дата</th>
<th>Сообщение</th>
</tr>
</thead>
<tbody>

			<?

					// Get our messages from the last week
					// Instead of searching for this week's message you could search for all the messages in your inbox using: $emails = imap_search($stream,'ALL');
					$emails = $mailbox->searchMailbox('SINCE '. date('d-M-Y',(time()-864000)));

					if (!count($emails)){
					?>
						<p>Нет сообщений.</p>
					<?
					} else {

						// If we've got some email IDs, sort them from new to old and show them
						rsort($emails);
                        $i=0;
						foreach($emails as $email_id){
                           $mail = $mailbox->getMail($email_id);
                           //print_r(($mail));
                           $Attach = $mail->getAttachments();
                           //exit;
							// Fetch the email's overview and show subject, from and date.
							//$overview = imap_fetch_overview($stream,$email_id,0);
							//$overview2 = nl2br(imap_base64(imap_fetchbody($stream,$email_id,1)));
							//if(empty($overview2)){  $overview2 = nl2br(imap_fetchbody($stream,$email_id,1)); }
							//print_r($overview2);
$rows = (new \yii\db\Query())
    ->select(['*'])
    ->from('email_id')
    ->where('e_id='.$email_id)
    ->limit(1)
    ->all();
							?>
<tr>
<td>
<?php  if(sizeof($rows)==0){  ?>
<a href="<?php echo Url::toRoute(['mail', 'e_id' => $email_id])?>" class="btn btn-xs red" ><i class="fa fa-check" aria-hidden="true"></i></a>
<?php }else { ?>
<i class="fa fa-check" aria-hidden="true"></i>
<?php } ?>
</td>
<td><?=$mail->subject?></td>
<td><a href="mailto:<?=($mail->fromAddress)?>" target="_blank"><?=($mail->fromAddress)?></a></td>
<td><?=$mail->date?></td>
<td>
 <a data-toggle="modal" href="#Fotodraggable<?php echo $email_id; ?>" class="btn btn-xs yellow"> <i class="fa fa-file-o"></i> Открыть</a>
							<div class="modal fade draggable-modal" id="Fotodraggable<?php echo $email_id; ?>" tabindex="-1" role="basic" aria-hidden="true" style="color:#000;">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Сообщение</h4>
										</div>
										<div class="modal-body"><div class="row">
                                    <div class="col-md-12">
           <?=nl2br($mail->textPlain)?>
           <hr>
<?php if(sizeof($Attach)>0){echo 'Вложения:<br />';}
foreach($Attach as $a){
$exp1 = explode('web',$a->filePath);
$exp2 = explode('mailfile',$exp1[1]);
//print_r($exp1[1]);
echo '<a href="'.Yii::$app->homeUrl.$exp1[1].'" target="_blank">'.$exp2[1].'</a><br />';
}
 ?>

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
</tr>

							<?
							$i++;
							//if($i>20){break;}
						}
					}


			?>
			</table>
			<?
		} // end foreach
	} ?>
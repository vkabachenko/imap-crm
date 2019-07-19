<?php


namespace app\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;

class EmailsUnionSearch extends Model
{
    public $mailboxes;

    public $model;
    public $id;
    public $created_at;
    public $from;
    public $to;
    public $subject;
    public $comment;
    public $manager_id;
    public $status;
    public $content;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'model',
                    'id',
                    'created_at',
                    'from',
                    'to',
                    'subject',
                    'comment',
                    'manager_id',
                    'status',
                    'content'
                ], 'safe'
            ],
        ];
    }

    public function search($params)
    {
        $queryIncoming = new Query();
        $queryIncoming
            ->select([
                'mailbox_id',
                'model' => new Expression('"emails"'),
                'id',
                'created_at' => 'imap_date',
                'from' => 'imap_from',
                'to' => 'imap_to',
                'subject' => 'imap_subject',
                'comment',
                'manager_id',
                'status' => new Expression('IF(is_deleted, "deleted", NULL)'),
                'content' => 'imap_raw_content'
            ])
            ->from('emails');

        $queryOutgoing = new Query();
        $queryOutgoing
            ->select([
                'mailbox_id',
                'model' => new Expression('"email_reply"'),
                'id',
                'created_at',
                'from',
                'to',
                'subject',
                'comment',
                'manager_id',
                'status',
                'content'
            ])
            ->from('email_reply');

        $queryOutgoing->union($queryIncoming, true);

        $query = new Query();
        $query->select('*')
            ->from(['u' => $queryOutgoing])
            ->where(['mailbox_id' => $this->mailboxes])
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params))) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        $query->andFilterWhere(['like', 'from', $this->from]);
        $query->andFilterWhere(['like', 'to', $this->to]);
        $query->andFilterWhere(['like', 'subject', $this->subject]);

        if ($this->manager_id === 'empty') {
            $query->andWhere(['manager_id' => null]);
        } else {
            $query->andFilterWhere(['manager_id' => $this->manager_id]);
        }
        $query->andFilterWhere(['like', 'comment', $this->comment]);
        $query->andFilterWhere(['like', 'content', $this->content]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['model' => $this->model]);

        return $dataProvider;
    }

    public static function models()
    {
        return [
           'emails' => 'Входящие',
           'email_reply' => 'Исходящие'
        ];
    }


}
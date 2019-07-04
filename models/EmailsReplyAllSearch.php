<?php


namespace app\models;

use yii\data\ActiveDataProvider;

class EmailsReplyAllSearch extends EmailReply
{
    /* @var array */
    public $mailboxes;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'mailbox_id',
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
            [
                ['is_read'], 'boolean'
            ]
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EmailReply::find()
            ->with(['manager'])
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
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 'comment', $this->comment]);
        $query->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
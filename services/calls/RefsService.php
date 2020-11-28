<?php

namespace app\services\calls;

use yii\helpers\Json;

class RefsService
{
    const REF_ATTRIBUTES = [
        'campaign_name',
        'campaign_id',
        'campaign_description',
        'ppc_ad_name',
        'ppc_ad_id',
        'ppc_gr_name',
        'ppc_kw_keyword',
        'ppc_pc_id',
        'ppc_pc_name',
        'referrer',
        'referrer_domain',
        'search_engine',
        'search_query',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_referrer',
        'utm_source',
        'utm_term',
        'tag_id',
        'tag_name'
    ];

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function createRefs()
    {
        $refs = [];
        foreach (self::REF_ATTRIBUTES as $attribute) {
            if (isset($this->request[$attribute]) && !empty($this->request[$attribute])) {
                $refs[$attribute] = $this->request[$attribute];
            }
        }

        return Json::encode($refs);
    }

    public function updateRefs($refs, ...$attributes) {
        $refs = Json::decode($refs);
        foreach ($attributes as $attribute) {
            if (isset($this->request[$attribute]) && !empty($this->request[$attribute])) {
                $refs[$attribute] = $this->request[$attribute];
            }
        }

        return Json::encode($refs);
    }

}
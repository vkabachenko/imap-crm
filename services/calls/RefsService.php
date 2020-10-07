<?php

namespace app\services\calls;

class RefsService
{
    const REF_ATTRIBUTES = [
        'campaign_name',
        'campaign_id',
        'campaign_description',
        'ppc_ad_name',
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
        'utm_term'
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

        return $refs;
    }

}
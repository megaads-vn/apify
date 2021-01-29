<?php
namespace Megaads\Apify\FilterBuilders\Impl;

use Megaads\Apify\FilterBuilders\FilterBuilder;

class MatchBuilder extends FilterBuilder
{
    const regex = '/(^[a-zA-Z0-9\.\_\-]+)\$(.*)/';
    protected $level = 12;
    public function buildQueryParam($filterParam)
    {
        preg_match(self::regex, $filterParam, $matches);
        if (count($matches) == 3) {
            return [
                "field" => $matches[1],
                "value" => $matches[2],
            ];
        } else {
            return false;
        }
    }
    public function buildQuery($query, $filter)
    {
        return $query->whereRaw("MATCH (" . $filter['field'] . ") AGAINST ('" . $filter['value'] . "' IN BOOLEAN MODE)");
    }
}

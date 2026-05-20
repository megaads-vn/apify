<?php
namespace Megaads\Apify\FilterBuilders\Impl;

use Megaads\Apify\FilterBuilders\FilterBuilder;
use Megaads\Apify\Controllers\BaseController;

class EqBuilder extends FilterBuilder
{
    const regex = '/(^[a-zA-Z0-9\.\_\-]+)\=(.*)/';
    protected $level = 11;
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
        if (strtolower($filter['value']) == 'null') {
            $query = $query->whereNull($filter['field']);
        } else {
            if (strrpos(strtolower($filter['field']), '.raw') > 0) {
                if (!BaseController::sanitizeRawExpression($filter['value'])) {
                    return $query;
                }
                $safePattern = '/^([a-zA-Z0-9_\.`]+)\s*(=|!=|<>|>=|<=|>|<|LIKE|NOT\s+LIKE)\s*(.+)$/i';
                $nullPattern = '/^([a-zA-Z0-9_\.`]+)\s+(IS\s+NULL|IS\s+NOT\s+NULL)$/i';
                $query = $query->where(function ($query) use ($filter, $safePattern, $nullPattern) {
                    if (preg_match($nullPattern, $filter['value'], $rawMatches)) {
                        $query->whereRaw($rawMatches[1] . ' ' . strtoupper($rawMatches[2]));
                    } elseif (preg_match($safePattern, $filter['value'], $rawMatches)) {
                        $query->whereRaw($rawMatches[1] . ' ' . $rawMatches[2] . ' ?', [trim($rawMatches[3])]);
                    }
                });
            } else {
                $query = $query->where($filter['field'], '=', $filter['value']);
            }
        }
        return $query;
    }
}

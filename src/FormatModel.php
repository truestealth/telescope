<?php

namespace Laravel\Telescope;

use BackedEnum;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FormatModel
{
    /**
     * Format the given model to a readable string.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    public static function given($model)
    {
        if ($model instanceof Pivot && ! $model->incrementing) {
            $keys = [
                $model->getAttribute($model->getForeignKey()),
                $model->getAttribute($model->getRelatedKey()),
            ];
        } else {
            $keys = $model->getKey();
        }

        return get_class($model).':'.implode('_', array_map(function ($value) {
            if($value instanceof Expression){
                return $value->getValue(DB::connection()->getQueryGrammar());
            }
            return $value instanceof BackedEnum ? $value->value : $value;
        }, Arr::wrap($keys)));
    }
}

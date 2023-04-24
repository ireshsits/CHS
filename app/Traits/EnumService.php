<?php
namespace App\Traits;
/**
 * Created by PhpStorm.
 * User: hosseini
 * Date: 1/27/18
 * Time: 12:43 PM
 */
use DB;

trait EnumService{
    public static function getEnumValues()
    {
        $fields = DB::connection((new static)->connection)->select(
            DB::raw("SHOW COLUMNS FROM " . config('database.connections.'.(new static)->connection.'.prefix') .(new static)->getTable())
        );
        $result = [];
        foreach ($fields as $field) {
            $enum = self::parsEnumValues($field->Type);
            if (!empty($enum))
                $result[$field->Field] = $enum;
        }
        return $result;

    }

    private static function parsEnumValues($type)
    {
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        if (empty($matches))
            return null;

        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = array_add($enum, $v, $v);
        }
        return $enum;
    }
}
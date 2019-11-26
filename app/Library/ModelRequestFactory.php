<?php


namespace App\Library;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prophecy\Exception\Doubler\ClassCreatorException;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use ReflectionClass;

class ModelRequestFactory
{

    private function __construct()
    {

    }

    public static function factory($modelPrototypeClass , Request $request)
    {
        if (!class_exists($modelPrototypeClass)) {
            throw new ClassNotFoundException('Invalid Class ' . $modelPrototypeClass);
        }

        $model = self::createModelObject($modelPrototypeClass);

        return self::fillFieldsWithRequest($model, $request);
    }

    private static function createModelObject($modelPrototypeClass)
    {
        $model = new $modelPrototypeClass();
        if ($model instanceof Model) {
            return $model;
        }
        throw new ClassCreatorException('Invalid Class ' . $modelPrototypeClass);
    }

    private static function fillFieldsWithRequest( Model $model, Request $request)
    {
        $jsonToArray = (array) json_decode($request->getContent());
        $propertiesCanBeFillable = $model->getFillable();

        foreach ($propertiesCanBeFillable as $property) {
            $model->$property = $jsonToArray[$property] ?? $jsonToArray;
        }
        return $model;
    }
}
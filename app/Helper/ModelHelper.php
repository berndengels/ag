<?php

namespace App\Helper;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class ModelHelper
{
    public static function allModels($useLeadingBackslash = true) : Collection
    {
        $models = collect(File::allFiles(app_path('Models')))
            ->map(
                function ($item) use ($useLeadingBackslash) {
                    $path = $item->getRelativePathName();
                    $name = strtr(substr($path, 0, strrpos($path, '.')), '/', '\\');
                    $format = $useLeadingBackslash ? '\%s%s\%s' : '%s%s\%s';
                    $class = sprintf(
                        $format,
                        Container::getInstance()->getNamespace(),
                        'Models',
                        $name
                    );
                    return ['name' => $name, 'class' => $class];
                }
            )
            ->filter(
                function ($item) {
                    $valid = false;
                    if (class_exists($item['class'])) {
                        $reflection = new ReflectionClass($item['class']);
                        $valid = $reflection->isSubclassOf(Model::class) && !$reflection->isAbstract();
                    }
                    return $valid;
                }
            );

        return $models->keyBy('name')->map->class;
    }
}

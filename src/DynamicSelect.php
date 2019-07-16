<?php

namespace Hubertnnn\LaravelNova\Fields\DynamicSelect;

use Hubertnnn\LaravelNova\Fields\DynamicSelect\Traits\DependsOnAnotherField;
use Hubertnnn\LaravelNova\Fields\DynamicSelect\Traits\HasDynamicOptions;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class DynamicSelect extends Field
{
    use HasDynamicOptions;
    use DependsOnAnotherField;

    public $component = 'dynamic-select';

    public function resolve($resource, $attribute = null)
    {
        $this->extractDependentValues($resource);

        return parent::resolve($resource, $attribute);
    }

    public function multiSelect()
    {
        return $this->withMeta(['multiselect' => true]);
    }

    public function meta()
    {
        $this->meta = parent::meta();
        return array_merge([
            'multiselect' => false,
            'options' => $this->getOptions($this->dependentValues),
            'dependsOn' => $this->getDependsOn(),
            'dependValues' => $this->dependentValues,
        ], $this->meta);
    }

    protected function fillAttributeFromRequest(NovaRequest $request,
        $requestAttribute,
        $model,
        $attribute)
    {
        if ($request->exists($requestAttribute)) {
            if(array_key_exists($attribute, $model->getCasts()) && ($model->getCasts()[$attribute] === 'json' || $model->getCasts()[$attribute] === 'array'))
            {
                if($request[$requestAttribute] === null){
                    $model->{$attribute} = [];
                }
                else{
                    $model->{$attribute} =  explode(',', $request[$requestAttribute]);
                }

            }
            else{
                $model->{$attribute} = $request[$requestAttribute];
            }
        }
        else{
            $model->{$attribute} = [];
        }
    }
}

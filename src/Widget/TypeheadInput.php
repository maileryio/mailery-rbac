<?php

declare(strict_types=1);

namespace Mailery\Rbac\Widget;

use Yiisoft\Widget\Widget;
use Yiisoft\Html\Html;
use Mailery\Assets\AssetBundleRegistry;
use Mailery\Rbac\Assets\RbacAssetBundle;

class TypeheadInput extends Widget
{

    /**
     * @var string|null
     */
    public ?string $url = null;

    /**
     * @var string|null
     */
    public ?string $type = 'text';

    /**
     * @var string|null
     */
    public ?string $name = null;

    /**
     * @var string|null
     */
    public ?string $value = null;

    /**
     * @var AssetBundleRegistry
     */
    private AssetBundleRegistry $assetBundleRegistry;

    /**
     * @param AssetBundleRegistry $assetBundleRegistry
     */
    public function __construct(AssetBundleRegistry $assetBundleRegistry)
    {
        $this->assetBundleRegistry = $assetBundleRegistry;
    }

    /**
     * @param string $url
     * @return self
     */
    public function url(string $url): self
    {
        $new = clone $this;
        $new->url = $url;

        return $new;
    }

    /**
     * @param string $type
     * @return self
     */
    public function type(string $type): self
    {
        $new = clone $this;
        $new->type = $type;

        return $new;
    }

    /**
     * @param string $name
     * @return self
     */
    public function name(string $name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @param string $value
     * @return self
     */
    public function value(string $value): self
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    /**
     * @inheritdoc
     */
    protected function run(): string
    {
        $this->assetBundleRegistry->add(RbacAssetBundle::class);

        $attributes = [
            'url' => $this->url,
            'type' => $this->type,
            'name' => $this->name,
            'value' => $this->value,
        ];

        return Html::tag('ui-typeahead', '', $attributes)->render();
    }

}
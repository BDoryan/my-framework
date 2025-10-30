<?php

namespace MyFramework\Component;

use MyFramework\Renderer\Renderer;
use MyFramework\Template\Template;

class Component implements Renderer
{

    const TYPE_SERVER_SIDE = 'server-side';
    const TYPE_CLIENT_SIDE = 'client-side';

    private string $file_path;
    private string $tag;
    private string $type;
    private array $props;

    public function __construct(string $file_path, string $type, array $props = [])
    {
        $this->file_path = $file_path;
        $this->tag = pathinfo($file_path, PATHINFO_FILENAME);
        $this->type = $type;
        $this->props = $props;
    }

    public function isServerSide(): bool
    {
        return $this->type === self::TYPE_SERVER_SIDE;
    }

    public function isClientSide(): bool
    {
        return $this->type === self::TYPE_CLIENT_SIDE;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function addProp(string $key, $value): self
    {
        $this->props[$key] = $value;
        return $this;
    }

    public function addProps(array $props): self
    {
        $this->props = array_merge($this->props, $props);
        return $this;
    }

    public function setProps(array $props): self
    {
        $this->props = $props;
        return $this;
    }

    public function getProp(string $key, $default = null)
    {
        return $this->props[$key] ?? $default;
    }

    public function getProps(): array
    {
        return $this->props;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function render(array $data = []): string
    {
        $pos = strpos($this->file_path, '/components/') + 12;
        $path = '/components/' . substr($this->file_path, $pos);
        return Template::loadTemplate($path, $data);
    }

    public static array $components = [];

    public static function registerComponents(string $directory): void
    {
        $files = glob($directory . '/*');
        $files = array_filter($files, function($file) {
            return is_dir($file) || pathinfo($file, PATHINFO_EXTENSION) === 'php';
        });
        foreach ($files as $file) {
            if(is_dir($file)) {
                self::registerComponents($file);
                continue;
            }
            $component = self::loadComponent($file);
            self::$components[$component->getTag()] = $component;
        }
    }

    public static function make(string $file_path, string $type, array $props = []): self
    {
        return new self($file_path, $type, $props);
    }

    public static function default($file_path): self
    {
        return new self($file_path, self::TYPE_CLIENT_SIDE, []);
    }

    public static function getComponentByTag(string $tag): ?self
    {
        if(!self::hasComponent($tag))
            throw new \RuntimeException("Component not registered: " . $tag);

        return self::$components[$tag] ?? null;
    }

    public static function hasComponent(string $tag): ?bool
    {
        return isset(self::$components[$tag]);
    }

    public static function loadComponent($file_path): self
    {
        if (file_exists($file_path)) {
            ob_start();
            $component = (require $file_path);
            $component = ($component instanceof Component) ? $component : self::default($file_path);
            ob_end_clean();

            return $component;
        }
        throw new \RuntimeException("Component file not found: " . $file_path);
    }
}
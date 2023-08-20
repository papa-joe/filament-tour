<?php

namespace JibayMcs\FilamentTour\Tour;

use Closure;

class Tour
{
    public string $id;

    public array $steps = [];

    public ?string $route = null;

    public array $colors = [];

    public bool $alwaysShow = false;

    public bool $visible = true;

    public function __construct(string $id, array $colors)
    {
        $this->id = $id;
        $this->colors = $colors;
    }

    /**
     * Create the instance of your tour.
     * <br>
     * Define an **$id** to be able to call it later in a livewire event.
     */
    public static function make(string $id): static
    {
        return app(static::class,
            [
                'id' => $id,
                'colors' => [
                    'dark' => 'rgb(var(--gray-600))',
                    'light' => 'rgb(0,0,0)',
                ],
            ]);
    }

    /**
     * Set the route where the tour will be shown.
     *
     * @return $this
     */
    public function route(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Set the steps of your tour.
     *
     * @return $this
     */
    public function steps(Step ...$steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * Set the colors of your background highlighted elements, based on your current filament theme.
     * <br>
     *  - **rgb(0,0,0)** by default for **$light**
     * <br>
     * - **rgb(var(--gray-600))** by default for **$dark**
     *
     * @return $this
     */
    public function colors(string $light, string $dark): self
    {
        $this->colors = [
            'light' => $light,
            'dark' => $dark,
        ];

        return $this;
    }

    /**
     * Set the tour as always visible, even is already viewed by the user.
     *
     * @return $this
     */
    public function alwaysShow(bool|Closure $alwaysShow = true): self
    {
        if (is_bool($alwaysShow)) {
            $this->alwaysShow = $alwaysShow;
        } else {
            $this->alwaysShow = $alwaysShow();
        }

        return $this;
    }

    /**
     * Set the tour as visible or not.
     *
     * @return $this
     */
    public function visible(bool|Closure $visible = true): self
    {
        if (is_bool($visible)) {
            $this->visible = $visible;
        } else {
            $this->visible = $visible();
        }

        return $this;
    }
}

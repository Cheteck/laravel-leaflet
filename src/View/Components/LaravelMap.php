<?php

namespace IJIDeals\Laraleaflet\View\Components;

use Illuminate\View\Component;

class LaravelMap extends Component
{

    public $options = [];
    public $title;
    public $initialMarkers = [];
    public $initialPolygons = [];
    public $initialPolylines = [];
    public $initialRectangles = [];
    public $initialCircles = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($options = null, $title = null, $initialMarkers = null, $initialPolygons = null, $initialPolylines = null, $initialRectangles = null, $initialCircles = null)
    {
        $this->options = $options;
        $this->title = $title;
        $this->initialMarkers = $initialMarkers;
        $this->initialPolygons = $initialPolygons;
        $this->initialPolylines = $initialPolylines;
        $this->initialRectangles = $initialRectangles;
        $this->initialCircles = $initialCircles;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('LaraLeaflet::components.laravel-map',[
            'title' => $this->title,
            'initialMarkers' => $this->initialMarkers,
            'initialPolygons' => $this->initialPolygons,
            'initialPolylines' => $this->initialPolylines,
            'initialRectangles' => $this->initialRectangles,
            'initialCircles' => $this->initialCircles,
            'options' => $this->options

        ]);
    }
}

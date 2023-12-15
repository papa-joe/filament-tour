<?php

namespace JibayMcs\FilamentTour\Livewire;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use JibayMcs\FilamentTour\FilamentTourPlugin;
use JibayMcs\FilamentTour\Highlight\HasHighlight;
use JibayMcs\FilamentTour\Tour\HasTour;
use Livewire\Attributes\On;
use Livewire\Component;

class FilamentTourWidget extends Component
{
    public array $tours = [];

    public array $highlights = [];

    protected $listeners = [
        'driverjs::load-elements' => 'load',
    ];

    public function load(array $request): void
    {
        $classesUsingHasTour = [];
        $classesUsingHasHighlight = [];
        $filamentClasses = [];

        foreach (array_merge(Filament::getResources(), Filament::getPages()) as $class) {
            $instance = new $class;

            if ($instance instanceof Resource) {
                collect($instance->getPages())->map(fn ($item) => $item['class'])
                    ->flatten()
                    ->each(function ($item) use (&$filamentClasses) {
                        $filamentClasses[] = $item;
                    });
            } else {
                $filamentClasses[] = $class;
            }

        }

        foreach ($filamentClasses as $class) {
            $traits = class_uses($class);

            if (in_array(HasTour::class, $traits)) {
                $classesUsingHasTour[] = $class;
            }

            if (in_array(HasHighlight::class, $traits)) {
                $classesUsingHasHighlight[] = $class;
            }
        }

        foreach ($classesUsingHasTour as $class) {

            $class = 'App\Filament\Resources\AbsenceResource\Pages\ListAbsences';

            if ($request['pathname'] == '/') {
                $class = 'App\Filament\Pages\Dashboard';
            } else if ($request['pathname'] == '/schools') {
                $class = 'App\Filament\Resources\SchoolResource\Pages\ListSchools';
            } else if (strpos($request['pathname'], "edit") !== false && strpos($request['pathname'], "schools") !== false) {
                $class = 'App\Filament\Resources\SchoolResource\Pages\EditSchool';
            } else if (strpos($request['pathname'], "edit") !== false && strpos($request['pathname'], "school-classes") !== false) {
                $class = 'App\Filament\Resources\SchoolClassResource\Pages\EditSchoolClass';
            } else if ($request['pathname'] == '/school-classes') {
                $class = 'App\Filament\Resources\SchoolClassResource\Pages\ListSchoolClasses';
            } else if (strpos($request['pathname'], "edit") !== false && strpos($request['pathname'], "students") !== false) {
                $class = 'App\Filament\Resources\StudentResource\Pages\EditStudent';
            }
            
            // dd($request['pathname']);
            $this->tours = array_merge($this->tours, (new $class())->constructTours($class, $request));
        }

        foreach ($classesUsingHasHighlight as $class) {
            $this->highlights = array_merge($this->highlights, (new $class())->constructHighlights($class, $request));
        }

//        dd([
//            'only_visible_once' => config('filament-tour.only_visible_once'),
//            'tours' => $this->tours,
//            'highlights' => $this->highlights,
//        ]);

        $this->emit('driverjs::loaded-elements', [
            'only_visible_once' => config('filament-tour.only_visible_once'),
            'tours' => $this->tours,
            'highlights' => $this->highlights,
        ]);

    }

    public function render()
    {
        return view('filament-tour::livewire.filament-tour-widget');
    }
}

<?php

namespace App\Http\Controllers;

use Parsedown;


class DocumentationController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        // All component
        $components = scandir(base_path() . "/resources/js/core/components/");

        // List of all which are not have readme.md file
        $not_completed_components = array_filter($components, function ($item) {
            if (!file_exists(base_path() . "/resources/js/core/components/" . $item . "/readme.md")) {
                return $item;
            }
        });

        // First component
        $file = base_path() . "/resources/js/core/components/" . $components[2] . "/readme.md";
        $component_name = $components[2];

        // Checks if requested component have a readme.md file else checks requested component exits or not
        if (file_exists($file)) {
            $Parsedown = new Parsedown();
            $content = $Parsedown->text(file_get_contents($file));
        } else {
            $component = base_path() . "/resources/js/core/components/" . $component_name;
            $content = file_exists($component) ? 'there is no readme file' : 'there is no such components';
        }

        // Return with $content,
        return view('doc.document')->with(compact('content', 'components', 'not_completed_components', 'component_name'));
    }

    public function show($component_name): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        // Requested component
        $file = base_path() . "/resources/js/core/components/" . $component_name . "/readme.md";

        // All component
        $components = scandir(base_path() . "/resources/js/core/components/");

        // List of all which are not have readme.md file
        $not_completed_components = array_filter($components, function ($item) {
            if (!file_exists(base_path() . "/resources/js/core/components/" . $item . "/readme.md")) {
                return $item;
            }
        });

        // Checks if requested component have a readme.md file else checks requested component exits or not
        if (file_exists($file)) {
            $Parsedown = new Parsedown();
            $content = $Parsedown->text(file_get_contents($file));
        } else {
            $component = base_path() . "/resources/js/core/components/" . $component_name;
            $content = file_exists($component) ? 'there is no readme file' : 'there is no such components';
        }

        // Return with $content,
        return view('doc.document')->with(compact('content', 'components', 'not_completed_components', 'component_name'));
    }

    public function apiDocumentation(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        // First component
        $file = base_path() ."/api.md";

        // Checks if requested component have a readme.md file else checks requested component exits or not
        if (file_exists($file)) {

            $parseDown = new Parsedown();
            $content = $parseDown->text(file_get_contents($file));
        }

        // Return with $content,
        return view('doc.api')->with(compact('content'));

    }
}

<?php

namespace IJIDeals\Laraleaflet\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class MapController extends Controller
{
    public function index()
    {
        $options = [
            'center' => [
                'lat' => -23.347509137997484,
                'lng' => -47.84753617004771
            ],
            'googleview' => true,
            'zoom' => 18,
            'zoomControl' => true,
            'minZoom' => 13,
            'maxZoom' => 18,
        ];
        $initialMarkers = [
            [
                'position' => [
                    'lat' => -23.347509137997484,
                    'lng' => -47.84753617004771
                ],
                'draggable' => true,
                'title' => 'Tatuí - SP'
            ]
        ];
        $initialPolygons = [
            [
                [-23.34606370264136 , -47.84818410873414],
                [-23.34575341324051 , -47.84759938716888],
                [-23.34615728184211 , -47.84729361534119],
                [-23.34651189716213 , -47.84792125225068]
            ]
        ];
        $initialPolylines = [
            [
                [-23.348914298657980 , -47.850147485733040],
                [-23.347850469110245 , -47.848109006881714],
                [-23.349209805352476 , -47.847293615341194],
                [-23.347781516900888 , -47.844675779342660]               
            ]
        ];
        $initialRectangles = [
            [
                [-23.347683013682527 , -47.85067319869996],
                [-23.346727528670904 , -47.84879565238953]
            ]
        ];
        $initialCircles = [
            [
                'position' => [ 
                    'lat' => -23.346569922234977, 
                    'lng' => -47.84376382827759
                ],
                'radius' => 80.68230575309364
            ]
        ];
        $title = 'Initial Map';

        return view('LaraLeaflet::map', compact('options','title','initialMarkers','initialPolygons','initialPolylines','initialRectangles','initialCircles'));
    }

    public function geocode(Request $request)
    {
        $address = $request->input('address');
        $response = Http::get('https://nominatim.openstreetmap.org/search', [
            'q' => $address,
            'format' => 'json',
            'limit' => 1
        ]);
        
        return $response->json();
    }

    public function calculateRoute(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        
        $response = Http::get('http://router.project-osrm.org/route/v1/driving/'.$start['lng'].','.$start['lat'].';'.$end['lng'].','.$end['lat'], [
            'overview' => 'full',
            'geometries' => 'geojson'
        ]);
        
        return $response->json();
    }
}

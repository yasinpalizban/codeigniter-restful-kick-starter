<?php namespace Modules\App\Controllers;


use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class Graph extends ApiController
{

    public function index()
    {


        $chartBarV = [
            [
                "name" => "China",
                "series" => [
                    [
                        "name" => "2018",
                        "value" => 2243772
                    ],
                    [
                        "name" => "2017",
                        "value" => 1227770
                    ]
                ]
            ],

            [
                "name" => "USA",
                "series" => [
                    [
                        "name" => "2018",
                        "value" => 1126000
                    ],
                    [
                        "name" => "2017",
                        "value" => 764666
                    ]
                ]
            ],

            [
                "name" => "Norway",
                "series" => [
                    [
                        "name" => "2018",
                        "value" => 296215
                    ],
                    [
                        "name" => "2017",
                        "value" => 209122
                    ]
                ]
            ],

            [
                "name" => "Japan",
                "series" => [
                    [
                        "name" => "2018",
                        "value" => 257363
                    ],
                    [
                        "name" => "2017",
                        "value" => 205350
                    ]
                ]
            ],

            [
                "name" => "Germany",
                "series" => [
                    [
                        "name" => "2018",
                        "value" => 196750
                    ],
                    [
                        "name" => "2017",
                        "value" => 129246
                    ]
                ]
            ],

            [
                "name" => "France",
                "series" => [
                    [
                        "name" => "2018",
                        "value" => 204617
                    ],
                    [
                        "name" => "2017",
                        "value" => 149797
                    ]
                ]
            ]
        ];


        $pieChart = [
            ["name" => "Mobiles", "value" => 105000],
            ["name" => "Laptop", "value" => 55000],
            ["name" => "AC", "value" => 15000],
            ["name" => "Headset", "value" => 150000],
            ["name" => "Fridge", "value" => 20000]
        ];
        $pieGrid = [
            ["name" => "Mobiles", "value" => 8000],
            ["name" => "Laptop", "value" => 5600],
            ["name" => "AC", "value" => 5500],
            ["name" => "Headset", "value" => 15000],
            ["name" => "Fridge", "value" => 2100]
        ];
        return $this->respond([
            'pieGrid' => $pieGrid,
            'pieChart' => $pieChart,
            'chartBar' => $chartBarV,
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));

    }


}

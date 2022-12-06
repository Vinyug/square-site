<?php declare(strict_types=1);

namespace App\Controllers;

use Faker\Factory;
use VGuyomarch\Foundation\AbstractController;
use VGuyomarch\Foundation\View;

class BaseController extends AbstractController
{
    public function index(): void
    {
        $faker = Factory::create();
        View::render('index', [
            'city' => $faker->city, 
        ]);
    }
}
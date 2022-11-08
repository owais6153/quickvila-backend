<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\Video;

class TestimonialAndVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testimonial = Testimonial::create([
            'title' => 'John Doe',
            'subtitle' => 'Lorem ipsum dolor',
            'image' => asset('images/testimonial.png'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis libero cras quis eu pretium tincidunt. Odio eget felis morbi tristique auctor porttitor orci tempor gravida pellentesque. Egestas nunc tempor gravida velit.',
            'sort' => 1,
        ]);

        $testimonial = Testimonial::create([
            'title' => 'Mike Taylor',
            'subtitle' => 'Lorem ipsum dolor',
            'image' => asset('images/testimonial.png'),
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis libero cras quis eu pretium tincidunt. Odio eget felis morbi tristique auctor porttitor orci tempor gravida pellentesque. Egestas nunc tempor gravida velit.',
            'sort' => 1,
        ]);

        $video = Video::create([
            'title' => 'First',
            'video' => 'someurl',
            'thumbnail' => asset('images/Rectangle 10.png'),
            'sort' => 1,
        ]);
        $video = Video::create([
            'title' => 'Second',
            'video' => 'someurl2',
            'thumbnail' => asset('images/Rectangle 10.png'),
            'sort' => 2,
        ]);
    }
}

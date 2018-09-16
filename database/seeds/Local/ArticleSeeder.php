<?php

namespace Seeds\Local;

use App\Models\Article;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder {
    public function run() {
        foreach( ['th', 'en', 'ja'] as $locale ) {
            foreach( range( 1, 10 ) as $index ) {
                $image = factory( Image::class )->create(
                    [
                        'url' => 'http://placehold.it/1400x900/588C73/EEDF92',
                    ]
                );
                factory( Article::class )->create(
                    [
                        'locale'           => $locale,
                        'cover_image_id'   => $image->id,
                        'publish_ended_at' => null,
                    ]
                );
            }
        }
    }
}

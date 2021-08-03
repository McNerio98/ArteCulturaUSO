<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\Category;
use App\Section;
use App\SectionByCategory;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cat_musicos = Category::create(['name'=>'Músicos']);
        $cat_cine = Category::create(['name'=>'Cine']);
        $cat_circenses = Category::create(['name'=>'Circenses']);
        $cat_danza = Category::create(['name'=>'Danza']);
        $cat_escenicas = Category::create(['name'=>'Escénicas']);
        $cat_escultural = Category::create(['name'=>'Escultural']);
        $cat_literatura = Category::create(['name'=>'Literatura']);
        $cat_visuales = Category::create(['name'=>'Visuales']);
        $cat_promotores = Category::create(['name'=>'Promotores']);
        $cat_aprendizaje = Category::create(['name'=>'Centros de aprendizaje']);
        $cat_default = Category::create(['name'=>'Predeterminado']);
        
        
        // ::::::::::::::::::::::::::::::::::::::::
        
        //Esto esta segun el documento 


    	// Estos deben ocultarse en el admin panel para evitar eliminacion 
    	Tag::create(['name'=>'Actores','category_id' => $cat_cine->id]);
    	Tag::create(['name'=>'Productores','category_id' => $cat_cine->id]);

    	Tag::create(['name'=>'Acróbatas','category_id' => $cat_circenses->id]);
        Tag::create(['name'=>'Contorsionistas','category_id' => $cat_circenses->id]);
        Tag::create(['name'=>'Equilibristas','category_id' => $cat_circenses->id]);
    	Tag::create(['name'=>'Malabaristas','category_id' => $cat_circenses->id]);	
        Tag::create(['name'=>'Mimos','category_id' => $cat_circenses->id]);
        Tag::create(['name'=>'Payasos','category_id' => $cat_circenses->id]);

        Tag::create(['name'=>'Grupo de danza','category_id' => $cat_danza->id]);

        Tag::create(['name'=>'Grupo de teatro','category_id' => $cat_escenicas->id]);
        Tag::create(['name'=>'Payasos (Clown)','category_id' => $cat_escenicas->id]);

        Tag::create(['name'=>'Escultores','category_id' => $cat_escenicas->id]);

        Tag::create(['name'=>'Escritores','category_id' => $cat_literatura->id]);
        Tag::create(['name'=>'Declamadores','category_id' => $cat_literatura->id]);

        Tag::create(['name'=>'Grupos de música','category_id' => $cat_musicos->id]);
        Tag::create(['name'=>'Solistas','category_id' => $cat_musicos->id]);

        Tag::create(['name'=>'Fotógrafos','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Grafiteros','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Muralistas','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Dibujantes','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Pintores','category_id' => $cat_visuales->id]);

        Tag::create(['name'=>'Casas de la cultura','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Asociaciones ONG’s','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Exposiciones permanentes','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Museos','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Cafeterías o bares culturales','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Colectivos artísticos','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Asociaciones de artistas','category_id' => $cat_promotores->id]);


        Tag::create(['name'=>'Escuelas de Cine','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuelas Circenses','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuelas de Danza','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuelas Escénicas','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuelas Esculturales ','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuelas de Literatura','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuelas de música','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuelas Visuales','category_id' => $cat_aprendizaje->id]);


        Tag::create(['name'=>'Otros','category_id' => $cat_default->id]);
    }
}

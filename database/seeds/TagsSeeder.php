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

        //Esto esta segun el documento 
        $cat_cine           = Category::create(['name'=>'Cine']);
        $cat_circenses      = Category::create(['name'=>'Circenses']);
        $cat_danza          = Category::create(['name'=>'Danza']);
        $cat_escenicas      = Category::create(['name'=>'Escénicas']);
        $cat_escultura      = Category::create(['name'=>'Escultura']);
        $cat_literatura     = Category::create(['name'=>'Literatura']);
        $cat_musica         = Category::create(['name'=>'Música']);
        $cat_visuales       = Category::create(['name'=>'Visuales']);

        $cat_gremios        = Category::create(['name'=>'Gremios']);
        $cat_eventos        = Category::create(['name'=>'Eventos']);
        
        $cat_promotores     = Category::create(['name'=>'Promotores']);
        $cat_galerias       = Category::create(['name'=>'Galerías']);

        $cat_res_imag       = Category::create(['name'=>'Reseñas e Imágenes']);
        $cat_audio_video    = Category::create(['name'=>'Audio y Video']);
        $cat_lib_rev        = Category::create(['name'=>'Libros y Revistas']);


    	// Estos deben ocultarse en el admin panel para evitar eliminacion 
    	Tag::create([
            'name'=>'Actores',
            'category_id' => $cat_cine->id
            ]);

    	Tag::create([
            'name'=>'Productores',
            'category_id' => $cat_cine->id
            ]);

    	Tag::create([
            'name'=>'Acrobatas',
            'category_id' => $cat_circenses->id
            ]);

        Tag::create([
            'name'=>'Contorsionistas',
            'category_id' => $cat_circenses->id
            ]);

        Tag::create([
            'name'=>'Equilibrista',
            'category_id' => $cat_circenses->id
            ]);

    	Tag::create([
            'name'=>'Malabarista',
            'category_id' => $cat_circenses->id
            ]);	

        Tag::create([
            'name'=>'Mimo',
            'category_id' => $cat_circenses->id
            ]);

        Tag::create([
            'name'=>'Payasos',
            'category_id' => $cat_circenses->id
            ]);

        Tag::create([
            'name'=>'Grupo de danza',
            'category_id' => $cat_danza->id
            ]);

        Tag::create([
            'name'=>'Grupo de teatro',
            'category_id' => $cat_escenicas->id
            ]);

        Tag::create([
            'name'=>'Payasos (Clown)',
            'category_id' => $cat_escenicas->id
            ]);

        Tag::create([
            'name'=>'Escultores',
            'category_id' => $cat_escenicas->id
            ]);

        Tag::create([
            'name'=>'Escritores',
            'category_id' => $cat_literatura->id
            ]);

        Tag::create([
            'name'=>'Declamadores',
            'category_id' => $cat_literatura->id
            ]);

        Tag::create([
            'name'=>'Grupos de música',
            'category_id' => $cat_musica->id
            ]);

        Tag::create([
            'name'=>'Solistas',
            'category_id' => $cat_musica->id
            ]);

        Tag::create([
            'name'=>'Fotógrafos',
            'category_id' => $cat_visuales->id
            ]);

        Tag::create([
            'name'=>'Grafiteros',
            'category_id' => $cat_visuales->id
            ]);

        Tag::create([
            'name'=>'Muralistas',
            'category_id' => $cat_visuales->id
            ]);

        Tag::create([
            'name'=>'Dibujantes',
            'category_id' => $cat_visuales->id
            ]);

        Tag::create([
            'name'=>'Pintores',
            'category_id' => $cat_visuales->id
            ]);

        
        Tag::create([
            'name'=>'Colectivos artísticos',
            'category_id' => $cat_gremios->id
            ]);

        Tag::create([
            'name'=>'Asociaciones de artistas',
            'category_id' => $cat_gremios->id
            ]);
        
        //Eventos se omite 
        //*

        Tag::create([
            'name'=>'Casas de la cultura',
            'category_id' => $cat_promotores->id
            ]);

        Tag::create([
            'name'=>'Asociaciones ONG’s',
            'category_id' => $cat_promotores->id
            ]);

        Tag::create([
            'name'=>'Exposiciones permanentes',
            'category_id' => $cat_galerias->id
            ]);

        Tag::create([
            'name'=>'Museos',
            'category_id' => $cat_galerias->id
            ]);

        Tag::create([
            'name'=>'Cafeterías o bares culturales',
            'category_id' => $cat_galerias->id
            ]);


        $sec_artistas       = Section::create(['name'=>'Artistas']);
        $sec_expresiones    = Section::create(['name'=>'Expresiones']);
        $sec_promotores     = Section::create(['name'=>'Promotores']);
        $sec_escuelas       = Section::create(['name'=>'Escuelas']);
        $sec_recursos       = Section::create(['name'=>'Recursos']);
        $sec_biografias     = Section::create(['name'=>'Biografias']);
        $sec_homenajes      = Section::create(['name'=>'Homenajes']);
        $sec_eventos        = Section::create(['name'=>'Eventos']);


        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_cine->id 
        ]);


        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_circenses->id 
        ]);
        
        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_danza->id 
        ]);
        
        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_escenicas->id 
        ]);
        
        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_escultura->id 
        ]);   
        
        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_literatura->id 
        ]); 
        
        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_musica->id 
        ]); 
        
        SectionByCategory::create([
            'section_id' => $sec_artistas->id,
            'category_id' => $cat_visuales->id 
        ]); 
        
        SectionByCategory::create([
            'section_id' => $sec_expresiones->id,
            'category_id' => $cat_gremios->id 
        ]);         

        SectionByCategory::create([
            'section_id' => $sec_promotores->id,
            'category_id' => $cat_promotores->id 
        ]);  

        SectionByCategory::create([
            'section_id' => $sec_promotores->id,
            'category_id' => $cat_galerias->id 
        ]);  

    }
}

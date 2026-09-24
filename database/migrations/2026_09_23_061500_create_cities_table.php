<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'cities',
            function (Blueprint $table) {

                $table->id();


                $table->foreignId(
                    'province_id'
                )
                ->constrained(
                    'provinces'
                )
                ->cascadeOnDelete();


                $table->string(
                    'name'
                );


                $table->enum(
                    'type',
                    [
                        'Kabupaten',
                        'Kota'
                    ]
                );


                $table->string(
                    'slug'
                );


                /*
                |--------------------------------------------------------------------------
                | KOORDINAT
                |--------------------------------------------------------------------------
                */

                $table->decimal(
                    'latitude',
                    10,
                    7
                )->nullable();


                $table->decimal(
                    'longitude',
                    10,
                    7
                )->nullable();


                $table->text(
                    'description'
                )->nullable();


                $table->timestamps();


                $table->unique(
                    [
                        'province_id',
                        'slug'
                    ]
                );

            }
        );
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'cities'
        );
    }
};
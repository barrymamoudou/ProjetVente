<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name'); // nom product 
            // $table->integer('category_id'); //
            // $table->integer('supplier_id'); //
            $table->unsignedBigInteger('category_id')->nullable(); // ID de la catégorie
            $table->unsignedBigInteger('supplier_id')->nullable(); // ID du fournisseur
            $table->string('product_code'); // qr code
            $table->string('product_garage')->nullable(); // Garage (facultatif)
            $table->string('product_image'); // image
            $table->string('product_store')->nullable(); // Magasin où est stocké le produit
            $table->string('buying_date')->nullable(); //date_d'achat
            $table->string('expire_date')->nullable(); // date_expiration
            $table->string('buying_price')->nullable(); // prix_achat
            $table->string('selling_price')->nullable(); // prix_de-vente
            // Définir les clés étrangères
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

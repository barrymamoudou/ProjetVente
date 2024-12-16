<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable(); // ID de la catégorie
          
            $table->string('order_date'); //date_de-commande
            $table->string('order_status'); //statut_commande
            $table->string('total_products'); //total_produits
            $table->string('sub_total')->nullable(); //sub_total
            $table->string('vat')->nullable();
            $table->string('invoice_no')->nullable(); // facture numero
            $table->string('total')->nullable();
            $table->string('payment_status')->nullable(); //statut_paiement
            $table->string('pay')->nullable(); //pay
            $table->string('due')->nullable();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}

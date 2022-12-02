<?php

use App\Models\Snackbar;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const DEMO_UUID = 'e2155862-cd10-4f6a-99f3-99123dc99093';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $snackbar = new Snackbar();
        $snackbar->uuid = self::DEMO_UUID;
        $snackbar->name = "Franks Frikadellenfabriek";
        $snackbar->saveOrFail();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Snackbar::whereUuid(self::DEMO_UUID)->delete();
    }
};

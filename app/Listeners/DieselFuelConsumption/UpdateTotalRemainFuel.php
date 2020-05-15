<?php

namespace App\Listeners\DieselFuelConsumption;

use App\DieselFuelConsumption;
use App\Events\DieselFuelConsumptionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateTotalRemainFuel
{
    /**
     * Handle the event.
     *
     * @param \App\Events\DieselFuelConsumptionCreated $event
     * @return void
     */
    public function handle(DieselFuelConsumptionCreated $event)
    {
        // Ambil data konsumsi solar terakhir dari gedung.
        $latestFuelConsumption = DieselFuelConsumption::query()
            ->where('building_id', $event->dieselFuelConsumption->building_id)
            ->where('id', '<', $event->dieselFuelConsumption->id)
            ->orderBy('id', 'desc')
            ->first();

        // Cek apakah tidak ada konsumsi solar terakhir
        if (!$latestFuelConsumption) {
            /**
             * Jika tidak ada, maka total sisa solar adalah jumlah yang di input.
             * Dalam hal ini, inputan harus bertipe "incoming". untuk mencegah hasil negatif.
             */
            $totalRemainFuel = $event->dieselFuelConsumption->amount;
        } else {
            /**
             * Jika ada data terakhir, maka hitung sesuai dengan tipe inputan.
             *   - Incoming: total_remain = latest total remain + amount
             *   - Remain: total_remain = latest total remain - amount
             */
            if ($event->dieselFuelConsumption->type == 'incoming') {
                $totalRemainFuel = $latestFuelConsumption->total_remain + $event->dieselFuelConsumption->amount;
            }

            if ($event->dieselFuelConsumption->type == 'remain') {
                $totalRemainFuel = $latestFuelConsumption->total_remain - $event->dieselFuelConsumption->amount;
            }
        }

        $event->dieselFuelConsumption->forceFill(['total_remain' => $totalRemainFuel ?? 0])->save();
    }
}

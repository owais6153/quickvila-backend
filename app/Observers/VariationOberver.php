<?php

namespace App\Observers;

use App\Models\Variation;
use App\Services\ProductServices\PricingService;

class VariationOberver
{
    /**
     * Handle the Variation "created" event.
     *
     * @param  \App\Models\Variation  $variation
     * @return void
     */
    function __construct(Product $product)
    {
        $this->service = new PricingService();
    }

    public function created(Variation $variation)
    {
        return $this->service->updateVariationDisplayPrice($variation->id);
    }

    /**
     * Handle the Variation "updated" event.
     *
     * @param  \App\Models\Variation  $variation
     * @return void
     */
    public function updated(Variation $variation)
    {
        return $this->service->updateVariationDisplayPrice($variation->id);
        //
    }

    /**
     * Handle the Variation "deleted" event.
     *
     * @param  \App\Models\Variation  $variation
     * @return void
     */
    public function deleted(Variation $variation)
    {
        //
    }

    /**
     * Handle the Variation "restored" event.
     *
     * @param  \App\Models\Variation  $variation
     * @return void
     */
    public function restored(Variation $variation)
    {
        //
    }

    /**
     * Handle the Variation "force deleted" event.
     *
     * @param  \App\Models\Variation  $variation
     * @return void
     */
    public function forceDeleted(Variation $variation)
    {
        //
    }
}

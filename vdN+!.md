DB::flushQueryLog();
DB::enableQueryLog();
$products = App\Models\Product::take(10)->get();
foreach ($products as $p) {
    $images = $p->images; 
}
$queriesN1 = count(DB::getQueryLog());



DB::flushQueryLog();
$productsWith = App\Models\Product::with('images')->take(10)->get();
foreach ($productsWith as $p) {
    $images = $p->images; 
}
$queriesOptimized = count(DB::getQueryLog());


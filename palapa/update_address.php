<?php

$reports = App\Models\Report::whereNull('address')->get();
foreach($reports as $r) {
    if($r->latitude && $r->longitude) {
        try {
            $res = \Illuminate\Support\Facades\Http::withHeaders([
                'User-Agent' => 'Palapa-App/1.0'
            ])->timeout(5)->get('https://nominatim.openstreetmap.org/reverse', [
                'format'=>'json',
                'lat'=>$r->latitude,
                'lon'=>$r->longitude
            ]);
            
            if($res->successful()) {
                $r->address = $res->json('display_name');
                $r->save();
                echo "Updated {$r->report_id} dengan alamat: {$r->address}\n";
                sleep(1); // avoid rate limit
            }
        } catch(\Exception $e) {
            echo "Failed {$r->report_id}\n";
        }
    }
}

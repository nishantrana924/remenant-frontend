<?php
$w = \App\Models\Warehouse::first();
if($w) {
    $w->update([
        'contact_person' => 'THUMMAR JIMMY JAYPRA',
        'contact_name' => 'THUMMAR JIMMY JAYPRA',
        'phone' => '7567776796',
        'address' => '224 Ambika Pinnacle Mall Lajamani Chowk Mota Varachha',
        'address_2' => 'Surat Gujarat 394101',
        'city' => 'SURAT',
        'state' => 'Gujarat',
        'pincode' => '394101',
        'gst_number' => '24CBUPT5159C1Z8'
    ]);
    echo 'Updated';
}

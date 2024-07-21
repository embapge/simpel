<?php

use App\Enums\PaymentBank;
use Illuminate\Support\Facades\Storage;

function terbilang($bilangan)
{
    $bilangan = strval($bilangan);
    $angka = ['0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'];
    $kata = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
    $tingkat = ['', 'Ribu', 'Juta', 'Milyar', 'Triliun'];

    $panjang_bilangan = strlen($bilangan);
    $kalimat = $subkalimat = $kata1 = $kata2 = $kata3 = '';
    $i = $j = 0;
    /* pengujian panjang bilangan */
    if ($panjang_bilangan > 15) {
        $kalimat = 'Diluar Batas';
        return $kalimat;
    }

    /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
    for ($i = 1; $i <= $panjang_bilangan; $i++) {
        $angka[$i] = substr($bilangan, -$i, 1);
    }

    $i = 1;
    $j = 0;
    $kalimat = '';

    while ($i <= $panjang_bilangan) {
        $subkalimat = '';
        $kata1 = '';
        $kata2 = '';
        $kata3 = '';

        if ($angka[$i + 2] != '0') {
            if ($angka[$i + 2] == '1') {
                $kata1 = 'Seratus';
            } else {
                $kata1 = $kata[$angka[$i + 2]] . ' Ratus';
            }
        }

        if ($angka[$i + 1] != '0') {
            if ($angka[$i + 1] == '1') {
                if ($angka[$i] == '0') {
                    $kata2 = 'Sepuluh';
                } elseif ($angka[$i] == '1') {
                    $kata2 = 'Sebelas';
                } else {
                    $kata2 = $kata[$angka[$i]] . ' Belas';
                }
            } else {
                $kata2 = $kata[$angka[$i + 1]] . ' Puluh';
            }
        }

        if ($angka[$i] != '0') {
            if ($angka[$i + 1] != '1') {
                $kata3 = $kata[$angka[$i]];
            }
        }

        if ($angka[$i] != '0' || $angka[$i + 1] != '0' || $angka[$i + 2] != '0') {
            $subkalimat = $kata1 . ' ' . $kata2 . ' ' . $kata3 . ' ' . $tingkat[$j] . ' ';
        }

        $kalimat = $subkalimat . $kalimat;
        $i = $i + 3;
        $j = $j + 1;
    }

    if ($angka[5] == '0' && $angka[6] == '0') {
        $kalimat = str_replace('Satu Ribu', 'Seribu', $kalimat);
    }

    return trim(preg_replace('/\s{2,}/', ' ', $kalimat)) . ' Rupiah';
}

function getDocument($path)
{
    if (!Storage::exists($path)) {
        abort(404, "File Not Found");
    }
    $mime = Storage::mimeType($path);
    return response()->file("../storage/app/{$path}", ['content-type' => $mime]);
}

function ppn()
{
    return 11 / 100;
}

function companyData()
{
    return collect(["name" => "Sinar Lautan Maritim", "address" => "Jln. Jalan bersaama", "email" => "billing@simpel.com", "phone_number" => "+(62) 896-2157-3281"]);
}

function paymentColor($status)
{
    if ($status == "pending") {
        $color = "warning";
    } elseif ($status == "expire" || $status == "cancel") {
        $color = "danger";
    } else {
        $color = "success";
    }

    return $color;
}

function paymentVendor($response)
{
    if ($response['payment_type'] == "bank_transfer") {
        if (array_key_exists("permata_va_number", $response)) {
            return PaymentBank::PERMATA->value;
        } else if (array_key_exists("biller_code", $response)) {
            return PaymentBank::MANDIRI->value;
        } elseif (array_key_exists("va_numbers", $response) && $response["va_numbers"][0]["bank"] == "bca") {
            return PaymentBank::BCA->value;
        }
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Collection;

class ActivityChart
{
    private const MONTH_LABELS = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    /**
     * Bangun data bar chart dari koleksi model yang punya kolom tanggal.
     * Kalau $month diisi -> pecah per minggu dalam bulan itu (Minggu 1-5).
     * Kalau $month kosong (semua bulan dalam 1 tahun) -> pecah per bulan (Jan-Des).
     *
     * @param  Collection  $items  Koleksi model (mis. DailyReport / JobTask)
     * @param  string  $dateColumn  Nama kolom tanggal (harus sudah di-cast ke Carbon)
     * @return Collection<int, array{label: string, value: int, percent: int}>
     */
    public static function build(Collection $items, ?int $month, string $dateColumn = 'report_date'): Collection
    {
        if ($month) {
            $chart = collect(range(1, 5))->map(function ($week) use ($items, $dateColumn) {
                $count = $items->filter(fn($item) => (int) ceil($item->{$dateColumn}->day / 7) === $week)->count();

                return ['label' => "Minggu {$week}", 'value' => $count];
            })->filter(fn($row, $index) => $index < 4 || $row['value'] > 0)->values();
        } else {
            $chart = collect(range(1, 12))->map(function ($m) use ($items, $dateColumn) {
                $count = $items->filter(fn($item) => $item->{$dateColumn}->month === $m)->count();

                return ['label' => self::MONTH_LABELS[$m - 1], 'value' => $count];
            });
        }

        $max = $chart->max('value') ?: 1;

        return $chart->map(function ($row) use ($max) {
            $row['percent'] = (int) round(($row['value'] / $max) * 100);

            return $row;
        });
    }

    /**
     * Label sumbu-X: per minggu dalam bulan (kalau $month diisi, jumlah minggu
     * menyesuaikan jumlah hari di bulan itu) atau per bulan Jan-Des (kalau tidak).
     * Dikembalikan sebagai array PHP biasa (bukan Collection) supaya aman dipakai
     * langsung di @json() Blade tanpa risiko salah pecah koma.
     */
    public static function bucketLabels(int $year, ?int $month): array
    {
        if ($month) {
            $daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
            $weeks = (int) ceil($daysInMonth / 7);

            return collect(range(1, $weeks))->map(fn($w) => "Minggu {$w}")->values()->all();
        }

        return self::MONTH_LABELS;
    }

    /**
     * Nomor bucket (1-based) tempat sebuah tanggal jatuh, konsisten dengan bucketLabels().
     */
    public static function bucketOf(\Carbon\Carbon $date, ?int $month): int
    {
        return $month ? (int) ceil($date->day / 7) : $date->month;
    }

    /**
     * Palet warna untuk membedakan tiap seri (mis. tiap karyawan) di grafik.
     */
    public static function palette(): array
    {
        return [
            '#ef4444',
            '#f97316',
            '#f59e0b',
            '#84cc16',
            '#10b981',
            '#14b8a6',
            '#0ea5e9',
            '#6366f1',
            '#8b5cf6',
            '#ec4899',
            '#f43f5e',
            '#64748b',
        ];
    }
}

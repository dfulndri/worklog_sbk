<form method="GET" class="d-flex flex-wrap gap-2 align-items-center period-filter">
    <select name="year" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
        @foreach ($availableYears as $y)
            <option value="{{ $y }}" @selected($year == $y)>Tahun {{ $y }}</option>
        @endforeach
    </select>

    <select name="month" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
        <option value="">Semua Bulan</option>
        @foreach (['1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April', '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus', '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $value => $label)
            <option value="{{ $value }}" @selected((string) $month === $value)>{{ $label }}</option>
        @endforeach
    </select>

    @if ($month)
        <a href="{{ request()->url() . '?year=' . $year }}" class="btn btn-outline-secondary btn-sm">Reset Bulan</a>
    @endif
</form>

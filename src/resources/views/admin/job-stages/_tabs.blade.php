<ul class="nav nav-tabs mb-3">
    @foreach ($kategoriList as $k)
        <li class="nav-item">
            <a class="nav-link {{ (int) $currentKategoriId === $k->id ? 'active' : '' }}"
                href="{{ route('admin.job-stages.index', ['kategori_id' => $k->id]) }}">{{ $k->name }}</a>
        </li>
    @endforeach
</ul>

@php
    $currentTab = request()->routeIs('admin.authorities.*')
        ? 'kewenangan'
        : $tab ?? ($level ?? ($category->level ?? 'kategori'));
@endphp
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $currentTab === 'kategori' ? 'active' : '' }}"
            href="{{ route('admin.document-categories.index', ['tab' => 'kategori']) }}">Kategori</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $currentTab === 'sub_kategori' ? 'active' : '' }}"
            href="{{ route('admin.document-categories.index', ['tab' => 'sub_kategori']) }}">Sub Kategori</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $currentTab === 'dokumen' ? 'active' : '' }}"
            href="{{ route('admin.document-categories.index', ['tab' => 'dokumen']) }}">Jenis Dokumen</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $currentTab === 'kewenangan' ? 'active' : '' }}"
            href="{{ route('admin.authorities.index') }}">Status Kewenangan</a>
    </li>
</ul>

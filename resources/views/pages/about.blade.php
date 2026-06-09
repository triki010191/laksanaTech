@extends('layouts.app')

@section('content')
    <x-page-header compact title="Tentang {{ config('laksana.brand.company') }}" />

    <section class="mx-auto max-w-4xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="prose-laksana">
            <p>CV. Laksana Solution adalah perusahaan yang bergerak di bidang Konsultan Teknologi Informasi (IT) dan Pengadaan Barang dan Jasa yang berkomitmen memberikan layanan profesional, inovatif, dan terpercaya untuk mendukung transformasi digital di berbagai sektor.</p>
            <p>Didirikan dengan semangat menghadirkan teknologi yang mudah diakses dan memberikan manfaat nyata bagi pelanggan, kami berpengalaman dalam pengembangan website, aplikasi berbasis web, sistem informasi, serta penyediaan perangkat teknologi dan infrastruktur pendukung bisnis maupun instansi.</p>
            <h2>Visi</h2>
            <p>Menjadi perusahaan teknologi informasi yang profesional, inovatif, dan terpercaya dalam menyediakan solusi digital dan pengadaan teknologi di Indonesia.</p>
            <h2>Misi</h2>
            <ul>
                <li>Mengembangkan solusi digital yang berkualitas dan sesuai kebutuhan pelanggan.</li>
                <li>Memberikan layanan teknologi yang profesional dan berorientasi pada hasil.</li>
                <li>Mendukung transformasi digital bagi UMKM, pendidikan, perusahaan, dan instansi pemerintah.</li>
                <li>Menjalin kemitraan jangka panjang yang saling menguntungkan.</li>
                <li>Terus berinovasi mengikuti perkembangan teknologi informasi.</li>
            </ul>
            <p class="mt-6"><strong>Direktur:</strong> {{ config('laksana.brand.director') }}</p>
        </div>
    </section>
@endsection

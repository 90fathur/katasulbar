{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>KATA SULBAR - Suara Sulbar, Untuk Semua</title>
        <link>{{ url('/') }}</link>
        <description>Portal Berita Terkini dan Terpercaya Sulawesi Barat</description>
        <language>id</language>
        <pubDate>{{ now()->toRfc2822String() }}</pubDate>
        <atom:link href="{{ route('feed') }}" rel="self" type="application/rss+xml" />

        @foreach($articles as $art)
            <item>
                <title><![CDATA[{{ $art->title }}]]></title>
                <link>{{ route('news.show', $art->slug) }}</link>
                <description><![CDATA[{{ $art->excerpt }}]]></description>
                <category>{{ $art->category ? $art->category->name : 'Sulbar' }}</category>
                <author>{{ $art->author ? $art->author->name : 'Redaksi Kata Sulbar' }}</author>
                <pubDate>{{ $art->published_at ? $art->published_at->toRfc2822String() : $art->created_at->toRfc2822String() }}</pubDate>
                <guid isPermaLink="true">{{ route('news.show', $art->slug) }}</guid>
            </item>
        @endforeach
    </channel>
</rss>
